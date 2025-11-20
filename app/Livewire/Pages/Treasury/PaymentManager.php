<?php

namespace App\Livewire\Pages\Treasury;

use App\Models\CashSession;
use App\Models\Institution;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\Voucher;
use App\Models\VoucherItem;
use App\Models\VoucherSeries;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentManager extends Component
{
    // --- BÚSQUEDA ---
    public $search = '';
    public Collection $searchResults;
    public ?Student $selectedStudent = null;

    // --- ESTADO DE CUENTA ---
    public Collection $pendingPayments;
    public Collection $paidPayments;

    // --- MODAL DE PAGO ---
    public $isModalOpen = false;
    public ?StudentPayment $paymentToRegister = null;

    // Campos del formulario de pago
    public $payment_date = '';
    public $payment_method = 'cash';
    public $voucher_type = '';
    public $transaction_number = '';
    public $observations = '';

    public $availableVoucherTypes = [];

    public function mount()
    {
        $this->searchResults = collect();
        $this->pendingPayments = collect();
        $this->paidPayments = collect();
        $this->payment_date = now()->format('Y-m-d\TH:i');

        // [NUEVO] Cargar solo los tipos que tienen una serie ACTIVA en la base de datos
        $this->availableVoucherTypes = VoucherSeries::where('status', 'active')
            ->distinct()
            ->pluck('voucher_type')
            ->toArray();

        // Seleccionar el primero por defecto si existe
        if (!empty($this->availableVoucherTypes)) {
            $this->voucher_type = $this->availableVoucherTypes[0];
        }
    }

    // ... (updatedSearch y selectStudent sin cambios) ...
    public function updatedSearch($value)
    {
        if (strlen($value) < 3) {
            $this->searchResults = collect();
            return;
        }
        $this->searchResults = Student::with('user')
            ->whereHas('user', function ($query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%')
                ->orWhere('email', 'like', '%' . $value . '%')
                ->orWhere('document_number', 'like', '%' . $value . '%');
            })
            ->orWhere('code', 'like', '%' . $value . '%')
            ->take(5)
            ->get();
    }

    public function selectStudent(Student $student)
    {
        $this->selectedStudent = $student;
        $this->search = $student->user->name;
        $this->searchResults = collect();
        $this->loadStudentPayments();
    }

    public function loadStudentPayments()
    {
        if (!$this->selectedStudent) return;

        $this->pendingPayments = StudentPayment::with('paymentConcept')
            ->where('student_id', $this->selectedStudent->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date')
            ->get();

        // Cargamos también el Voucher relacionado
        $this->paidPayments = StudentPayment::with(['paymentConcept', 'voucher'])
            ->where('student_id', $this->selectedStudent->id)
            ->where('status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->take(10)
            ->get();
    }

    public function openPaymentModal(StudentPayment $payment)
    {
        $this->paymentToRegister = $payment;
        $this->payment_date = now()->format('Y-m-d\TH:i');
        $this->payment_method = 'cash';

        // Mantener el tipo seleccionado o resetear al primero disponible
        if (empty($this->voucher_type) && !empty($this->availableVoucherTypes)) {
            $this->voucher_type = $this->availableVoucherTypes[0];
        }

        $this->transaction_number = '';
        $this->observations = '';
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->paymentToRegister = null;
    }

    /**
     * [MÉTODO ACTUALIZADO]
     * Registra el pago, genera el voucher y actualiza la caja.
     */
    public function registerPayment()
    {
        $this->validate([
            'payment_date' => 'required|date',
            'payment_method' => 'required',
            'voucher_type' => 'required|in:' . implode(',', $this->availableVoucherTypes), // Validación dinámica
            'transaction_number' => 'nullable|string|max:50',
        ]);

        if (!$this->paymentToRegister) return;

        // 1. Verificar si hay caja abierta
        $activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (!$activeSession) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Caja Cerrada', 'text' => 'Debe abrir una sesión de caja para cobrar.']);
            return;
        }

        try {
            DB::transaction(function () use ($activeSession) {
                $institution = Institution::first();

                // 2. Obtener Correlativo
                $series = VoucherSeries::where('institution_id', $institution->id)
                    ->where('voucher_type', $this->voucher_type)
                    ->where('status', 'active')
                    ->lockForUpdate()
                    ->first();

                if (!$series) {
                    throw new \Exception("No hay serie configurada para {$this->voucher_type}.");
                }

                $nextNumber = $series->current_number + 1;
                $series->update(['current_number' => $nextNumber]);

                // 3. Crear el Voucher
                $voucher = Voucher::create([
                    'cash_session_id' => $activeSession->id,
                    'issuer_id' => Auth::id(),
                    'client_id' => $this->selectedStudent->user_id, // El usuario del estudiante
                    'voucher_type' => $this->voucher_type,
                    'series' => $series->series,
                    'number' => $nextNumber,
                    'total_amount' => $this->paymentToRegister->final_amount,
                    'payment_method' => $this->payment_method,
                    'transaction_code' => $this->transaction_number,
                    'observations' => $this->observations ?: 'Pago de deuda: ' . $this->paymentToRegister->paymentConcept->description,
                    'status' => 'issued',
                    'issued_at' => $this->payment_date,
                ]);

                // 4. Crear el Ítem del Voucher
                VoucherItem::create([
                    'voucher_id' => $voucher->id,
                    'payment_concept_id' => $this->paymentToRegister->payment_concept_id,
                    'description' => $this->paymentToRegister->paymentConcept->description,
                    'quantity' => 1,
                    'unit_price' => $this->paymentToRegister->final_amount,
                    'total_price' => $this->paymentToRegister->final_amount,
                ]);

                // 5. Actualizar la Deuda (StudentPayment)
                $this->paymentToRegister->update([
                    'status' => 'paid',
                    'payment_date' => $this->payment_date,
                    'payment_method' => $this->payment_method,
                    'transaction_number' => $this->transaction_number,
                    'registered_by_user_id' => Auth::id(),
                    'voucher_id' => $voucher->id, // Enlazar al voucher
                    'notes' => $this->observations,
                ]);

                // 6. Descargar PDF
                $this->dispatch('open-pdf', url: route('treasury.voucher.download', $voucher->id));

                $this->closeModal();
                $this->loadStudentPayments(); // Recargar listas

                $this->dispatch('swal', [
                    'icon' => 'success',
                    'title' => '¡Pago Registrado!',
                    'text' => "Se generó el comprobante {$series->series}-" . str_pad($nextNumber, 6, '0', STR_PAD_LEFT),
                ]);
            });
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pages.treasury.payment-manager');
    }
}

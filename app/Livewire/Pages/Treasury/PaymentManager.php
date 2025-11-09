<?php

namespace App\Livewire\Pages\Treasury;

use App\Models\Student;
use App\Models\StudentPayment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
    public $payment_date = '';
    public $payment_method = 'cash';
    public $transaction_number = '';

    /**
     * Hook 'mount': Inicializa colecciones.
     */
    public function mount()
    {
        $this->searchResults = collect();
        $this->pendingPayments = collect();
        $this->paidPayments = collect();
        $this->payment_date = now()->format('Y-m-d\TH:i'); // Formato para datetime-local
    }

    /**
     * Hook: Se ejecuta cuando el 'search' cambia.
     */
    public function updatedSearch($value)
    {
        if (strlen($value) < 3) {
            $this->searchResults = collect();
            return;
        }

        // Busca estudiantes por nombre, email o código
        $this->searchResults = Student::with('user')
            ->whereHas('user', function ($query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%')
                    ->orWhere('email', 'like', '%' . $value . '%');
            })
            ->orWhere('code', 'like', '%' . $value . '%')
            ->take(5)
            ->get();
    }

    /**
     * Acción: Selecciona un estudiante de los resultados.
     */
    public function selectStudent(Student $student)
    {
        $this->selectedStudent = $student;
        $this->search = $student->user->name; // Pone el nombre en la barra
        $this->searchResults = collect(); // Cierra los resultados
        $this->loadStudentPayments();
    }

    /**
     * Carga el estado de cuenta del estudiante seleccionado.
     */
    public function loadStudentPayments()
    {
        if (!$this->selectedStudent) return;

        $this->pendingPayments = StudentPayment::with('paymentConcept')
            ->where('student_id', $this->selectedStudent->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date')
            ->get();
        
        $this->paidPayments = StudentPayment::with('paymentConcept')
            ->where('student_id', $this->selectedStudent->id)
            ->where('status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->take(10) // Solo los 10 últimos
            ->get();
    }

    // --- LÓGICA DEL MODAL DE PAGO ---

    public function openPaymentModal(StudentPayment $payment)
    {
        $this->paymentToRegister = $payment;
        $this->payment_date = now()->format('Y-m-d\TH:i');
        $this->payment_method = 'cash';
        $this->transaction_number = '';
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->paymentToRegister = null;
    }

    /**
     * Registra el pago (acción principal).
     */
    public function registerPayment()
    {
        $this->validate([
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,debit_card',
            'transaction_number' => 'nullable|string|max:50',
        ]);

        if (!$this->paymentToRegister) return;

        try {
            $this->paymentToRegister->update([
                'status' => 'paid',
                'payment_date' => $this->payment_date,
                'payment_method' => $this->payment_method,
                'transaction_number' => $this->transaction_number,
                'registered_by_user_id' => Auth::id(),
            ]);

            $this->closeModal();
            $this->loadStudentPayments(); // Recargar el estado de cuenta
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Pago Registrado!',
                'text' => 'El pago se ha registrado exitosamente.',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pages.treasury.payment-manager');
    }
}
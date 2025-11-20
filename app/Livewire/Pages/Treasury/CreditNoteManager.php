<?php

namespace App\Livewire\Pages\Treasury;

use App\Models\CashSession;
use App\Models\CreditNote;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CreditNoteManager extends Component
{
    use WithPagination;

    // --- BÚSQUEDA ---
    public $search = ''; // Buscar por número de voucher o nombre de cliente

    // --- MODAL DE ANULACIÓN ---
    public $isModalOpen = false;
    public ?Voucher $voucherToAnnul = null;
    public $reason = '';

    // --- ESTADO ---
    public $activeSession;

    public function mount()
    {
        // Verificar si hay una sesión de caja activa (necesaria para registrar la nota)
        $this->activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();
    }

    public function openAnnulmentModal(Voucher $voucher)
    {
        if (!$this->activeSession) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Caja Cerrada', 'text' => 'Debe abrir caja para emitir una nota de crédito.']);
            return;
        }

        if ($voucher->status == 'annulled') {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Ya Anulado', 'text' => 'Este comprobante ya ha sido anulado.']);
            return;
        }

        $this->voucherToAnnul = $voucher;
        $this->reason = '';
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->voucherToAnnul = null;
        $this->resetErrorBag();
    }

    /**
     * Procesa la anulación.
     */
    public function processAnnulment()
    {
        $this->validate([
            'reason' => 'required|string|min:10|max:255',
        ]);

        if (!$this->activeSession) return;

        try {
            DB::transaction(function () {
                // 1. Crear la Nota de Crédito
                CreditNote::create([
                    'voucher_id' => $this->voucherToAnnul->id,
                    'user_id' => Auth::id(), // Quién anula
                    'cash_session_id' => $this->activeSession->id, // En qué turno se anula
                    'reason' => $this->reason,
                    'amount' => $this->voucherToAnnul->total_amount, // Monto negativo contable
                ]);

                // 2. Actualizar estado del Voucher
                $this->voucherToAnnul->update(['status' => 'annulled']);

                // 3. Revertir los Pagos de Estudiante (Si existen)
                // Esto vuelve a poner la deuda como "Pendiente" para que se pueda cobrar bien o eliminar.
                foreach ($this->voucherToAnnul->studentPayments as $payment) {
                    $payment->update([
                        'status' => 'pending',
                        'payment_date' => null,
                        'payment_method' => null,
                        'voucher_id' => null, // Desvincular del voucher anulado
                        'notes' => $payment->notes . " | Pago anulado por Nota de Crédito (Voucher {$this->voucherToAnnul->series}-{$this->voucherToAnnul->number})",
                    ]);
                }
            });

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Anulado!',
                'text' => 'El comprobante ha sido anulado y la nota de crédito generada.',
            ]);

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function render()
    {
        $query = Voucher::with(['client', 'issuer'])
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('number', 'like', '%' . $this->search . '%')
                    ->orWhere('series', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', fn($sq) => $sq->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        $vouchers = $query->paginate(10);

        return view('livewire.pages.treasury.credit-note-manager', [
            'vouchers' => $vouchers
        ]);
    }
}

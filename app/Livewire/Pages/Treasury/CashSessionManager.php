<?php

namespace App\Livewire\Pages\Treasury;

use App\Models\CashSession;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CashSessionManager extends Component
{
    public ?CashSession $activeSession = null;

    // --- FORMULARIO DE APERTURA ---
    public $opening_balance = 0.00;

    // --- FORMULARIO DE CIERRE ---
    public $closing_balance = 0.00; // Lo que el cajero "cuenta"
    public $calculated_balance = 0.00; // Lo que el sistema *calcula*
    public $total_payments = 0;
    public $difference = 0.00;

    public function mount()
    {
        $this->loadActiveSession();
    }

    /**
     * Carga la sesión de caja activa (si existe) para el cajero logueado.
     */
    public function loadActiveSession()
    {
        $this->activeSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if ($this->activeSession) {
            $this->calculateSessionTotals();
        }
    }

    /**
     * Acción: Abrir una nueva sesión de caja.
     */
    public function openSession()
    {
        $this->validate([
            'opening_balance' => 'required|numeric|min:0',
        ]);

        // Verificar si ya tiene una sesión abierta
        if ($this->activeSession) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Ya tienes una sesión de caja abierta.']);
            return;
        }

        CashSession::create([
            'user_id' => Auth::id(),
            'opening_time' => now(),
            'opening_balance' => $this->opening_balance,
            'status' => 'open',
        ]);

        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Caja Abierta!', 'text' => 'Se ha iniciado tu sesión de caja.']);
        $this->loadActiveSession();
    }

    /**
     * Calcula los totales de la sesión activa (para el cierre).
     */
    public function calculateSessionTotals()
    {
        if (!$this->activeSession) return;

        // Sumar todos los comprobantes emitidos en esta sesión
        // (En el futuro, filtrar por método de pago, ej. "cash")
        $this->total_payments = Voucher::where('cash_session_id', $this->activeSession->id)
            ->where('status', 'issued') // Solo los emitidos
            ->sum('total_amount');

        // El saldo calculado es lo que entró + lo que había
        $this->calculated_balance = $this->activeSession->opening_balance + $this->total_payments;

        // Calcular la diferencia (Sobrante/Faltante)
        $this->calculateDifference();
    }

    /**
     * Hook: Recalcula la diferencia cuando el cajero escribe el monto de cierre.
     */
    public function updatedClosingBalance()
    {
        $this->calculateDifference();
    }

    public function calculateDifference()
    {
        $this->difference = (float)$this->closing_balance - (float)$this-> calculated_balance;
    }

    /**
     * Acción: Cerrar la sesión de caja activa.
     */
    public function closeSession()
    {
        if (!$this->activeSession) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No hay ninguna sesión activa para cerrar.']);
            return;
        }

        $this->calculateSessionTotals(); // Recalcular por seguridad

        $this->activeSession->update([
            'closing_time' => now(),
            'closing_balance' => $this->closing_balance,
            'calculated_balance' => $this->calculated_balance,
            'difference' => $this->difference,
            'status' => 'closed',
        ]);

        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Caja Cerrada!', 'text' => 'Tu sesión de caja ha finalizado.']);
        $this->activeSession = null;
        $this->reset('opening_balance', 'closing_balance');
    }


    public function render()
    {
        return view('livewire.pages.treasury.cash-session-manager');
    }
}

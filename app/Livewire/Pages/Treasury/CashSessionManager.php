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
    public $closing_balance_cash = 0.00; // Lo que el cajero "cuenta"
    public $calculated_cash = 0.00; // Lo que el sistema *calcula*
    public $total_other_methods = 0.00; // Total Tarjetas, Yape, etc.
    public $total_vouchers = 0; // Total de pagos (suma)
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

        // Total en Efectivo
        $this->calculated_cash = Voucher::where('cash_session_id', $this->activeSession->id)
            ->where('status', 'issued')
            ->where('payment_method', 'Efectivo') // Asumiendo que el método se llama 'Efectivo'
            ->sum('total_amount');

        // Total en Otros Métodos
        $this->total_other_methods = Voucher::where('cash_session_id', $this->activeSession->id)
            ->where('status', 'issued')
            ->whereNot('payment_method', 'Efectivo')
            ->sum('total_amount');

        // Total General
        $this->total_vouchers = $this->calculated_cash + $this->total_other_methods;

        // El saldo calculado es lo que entró (en efectivo) + lo que había
        $this->calculated_cash = $this->activeSession->opening_balance + $this->calculated_cash;

        // Calcular la diferencia (Sobrante/Faltante)
        $this->calculateDifference();
    }

    /**
     * Hook: Recalcula la diferencia cuando el cajero escribe el monto de cierre.
     */
    public function updatedClosingBalanceCash()
    {
        $this->calculateDifference();
    }

    public function calculateDifference()
    {
        $this->difference = (float)$this->closing_balance_cash - (float)$this->calculated_cash;
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
            'closing_balance_cash' => $this->closing_balance_cash,
            'calculated_cash' => $this->calculated_cash,
            'total_other_methods' => $this->total_other_methods,
            'difference' => $this->difference,
            'status' => 'closed',
        ]);

        // [NUEVO] Guardar ID antes de resetear
        $sessionId = $this->activeSession->id;

        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Caja Cerrada!', 'text' => 'Tu sesión de caja ha finalizado.']);
        $this->dispatch('open-pdf', url: route('treasury.cash-session.report', ['session' => $sessionId, 'type' => 'z']));
        $this->activeSession = null;
        $this->reset('opening_balance', 'closing_balance_cash');
    }


    public function render()
    {
        return view('livewire.pages.treasury.cash-session-manager');
    }
}

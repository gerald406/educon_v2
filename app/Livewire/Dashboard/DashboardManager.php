<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardManager extends Component
{
    // Propiedad para almacenar qué dashboard debemos mostrar
    public $viewToRender = '';

    /**
     * Hook 'mount': Decide qué vista se debe renderizar.
     */
    public function mount()
    {
        $user = Auth::user();

        if ($user->hasRole('Administrador')) {
            $this->viewToRender = 'dashboard.admin-dashboard';
        
        } elseif ($user->hasAnyRole(['Docente', 'Coordinador'])) {
            $this->viewToRender = 'dashboard.teacher-dashboard';

        } elseif ($user->hasRole('Estudiante')) {
            $this->viewToRender = 'dashboard.student-dashboard';
        
        } elseif ($user->hasAnyRole(['Secretario Academico', 'Tesoreria'])) {
            $this->viewToRender = 'dashboard.admin-dashboard';
        
        } else {
            // Fallback por si un usuario no tiene rol
            $this->viewToRender = 'dashboard.admin-dashboard';
        }
    }

    /**
     * Renderiza la vista 'dashboard-manager' que actuará como un router.
     */
    public function render()
    {
        return view('livewire.dashboard.dashboard-manager');
    }
}
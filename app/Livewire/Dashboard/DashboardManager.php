<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardManager extends Component
{
    /**
     * Renderiza el dashboard correspondiente basado en el rol.
     * [CÓDIGO CORREGIDO]
     */
    public function render()
    {
        $user = Auth::user();

        if ($user->hasRole('Administrador')) {
            // Carga la *vista* del AdminDashboard
            return view('livewire.dashboard.admin-dashboard');
        
        } elseif ($user->hasAnyRole(['Docente', 'Coordinador'])) {
            // Carga la *vista* del TeacherDashboard
            return view('livewire.dashboard.teacher-dashboard');

        } elseif ($user->hasRole('Estudiante')) {
            // Carga la *vista* del StudentDashboard
            return view('livewire.dashboard.student-dashboard');
        
        } elseif ($user->hasRole('Secretario Academico') || $user->hasRole('Tesoreria')) {
            // Cargamos la vista de Admin para otros roles de gestión
            return view('livewire.dashboard.admin-dashboard');
        }

        // Un dashboard por defecto si no tiene rol
        return view('livewire.dashboard.admin-dashboard');
    }
}
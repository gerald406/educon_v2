<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    /**
     * Renderiza la vista del menú lateral.
     */
    public function render()
    {
        // [CORREGIDO] Comprueba si el usuario tiene *alguno* de los permisos de gestión
        // Si es solo Docente o Estudiante (que no tienen estos permisos), no se mostrará.
        if (!Auth::user()?->hasAnyPermission([
            'gestionar-configuracion',
            'gestionar-estructura-academica',
            'gestionar-prerrequisitos',
            'gestionar-docentes',
            'gestionar-estudiantes',
            'gestionar-periodos',
            'gestionar-carga-academica',
            'gestionar-horarios',
            'aprobar-silabos',
            'registrar-pagos',
            'gestionar-certificacion',
            'gestionar-biblioteca'
        ])) {
            return '<div></div>'; // No renderizar nada
        }

        // Si es un rol de gestión, renderiza el menú
        return view('livewire.layout.sidebar');
    }
}
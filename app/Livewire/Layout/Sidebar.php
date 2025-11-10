<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth; // <-- [NUEVO] Importar Auth
use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        // [MODIFICADO] Comprueba si el usuario tiene *alguno* de los roles de gestión
        if (!Auth::user()?->hasAnyRole(['Administrador', 'Secretario Academico', 'Coordinador', 'Tesoreria'])) {
            return '<div></div>'; // No renderizar nada si es Docente o Estudiante
        }

        // Si es un rol de gestión, renderiza el menú
        return view('livewire.layout.sidebar');
    }
}

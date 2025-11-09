<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth; // <-- [NUEVO] Importar Auth
use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {   
        // [NUEVO] Comprobar el tipo de usuario
        if (Auth::user()?->user_type !== 'administrator') {
            return '<div></div>'; // No renderizar nada si no es admin
        }
        // Si es admin, renderiza el menú
        return view('livewire.layout.sidebar');
    }
}

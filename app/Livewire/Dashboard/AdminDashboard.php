<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')] // <-- ¡Importante! Usa nuestro layout
class AdminDashboard extends Component
{
    // (Aquí añadiremos métricas en la próxima fase)
    
    public function render()
    {
        return view('livewire.dashboard.admin-dashboard');
    }
}
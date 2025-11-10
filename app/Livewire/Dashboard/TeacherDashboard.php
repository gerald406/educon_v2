<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')] // <-- ¡Importante!
class TeacherDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard.teacher-dashboard');
    }
}
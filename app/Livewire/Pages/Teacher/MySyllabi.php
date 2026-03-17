<?php

namespace App\Livewire\Pages\Teacher;

use App\Models\AcademicPeriod;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MySyllabi extends Component
{
    public ?AcademicPeriod $activePeriod = null;
    public $assignments = []; // Cursos asignados al docente

    public function mount()
    {
        // 1. Obtenemos el periodo activo
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $teacher = Auth::user()->teacher;

        // 2. Si el docente existe y hay periodo activo, cargamos sus cursos
        if ($teacher && $this->activePeriod) {
            $this->assignments = TeacherAssignment::where('teacher_id', $teacher->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->with(['didacticUnit.module', 'syllabus', 'shift']) // Optimizamos Eager Loading
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.pages.teacher.my-syllabi');
    }
}

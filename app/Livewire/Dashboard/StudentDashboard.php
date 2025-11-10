<?php

namespace App\Livewire\Dashboard;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentDashboard extends Component
{
    public ?AcademicPeriod $activePeriod = null;
    public ?Student $student = null;
    public ?Enrollment $currentEnrollment = null;
    public Collection $schedules;

    public function mount()
    {
        $this->student = Auth::user()->student;
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->schedules = collect();

        if ($this->student && $this->activePeriod) {
            $this->currentEnrollment = Enrollment::where('student_id', $this->student->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->first();
                
            if ($this->currentEnrollment) {
                // Si está matriculado, cargar su horario
                $this->loadSchedules();
            }
        }
    }
    
    public function loadSchedules()
    {
        $registrations = $this->currentEnrollment
            ->registrations()
            ->with('teacherAssignment.schedules.classroomResource')
            ->get();
            
        $this->schedules = $registrations
            ->pluck('teacherAssignment.schedules')
            ->flatten()
            ->sortBy('day_of_week')
            ->sortBy('start_time');
    }

    public function render()
    {
        return view('livewire.dashboard.student-dashboard');
    }
}
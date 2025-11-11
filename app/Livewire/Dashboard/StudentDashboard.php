<?php

namespace App\Livewire\Dashboard;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Announcement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentDashboard extends Component
{
    public ?AcademicPeriod $activePeriod = null;
    public ?Student $student = null;
    public ?Enrollment $currentEnrollment = null;
    public Collection $schedules;
    // [NUEVO] Propiedad para los anuncios
    public Collection $announcements;

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

        // --- [NUEVA LÓGICA] ---
        // Cargar los anuncios para el estudiante
        $this->announcements = Announcement::where('status', 'published')
            ->where('publish_date', '<=', now())
            ->where(function ($query) {
                // Que no hayan expirado
                $query->whereNull('expiration_date')
                      ->orWhere('expiration_date', '>', now());
            })
            ->where(function ($query) {
                // Dirigidos a 'Todos' O 'Solo Estudiantes'
                $query->where('target_audience', 'all')
                      ->orWhere('target_audience', 'students');
            })
            ->orderBy('publish_date', 'desc')
            ->take(5) // Mostrar los 5 más recientes
            ->get();
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
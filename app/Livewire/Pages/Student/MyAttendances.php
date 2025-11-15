<?php

namespace App\Livewire\Pages\Student;

use App\Models\AcademicPeriod;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MyAttendances extends Component
{
    // --- CONTEXTO ---
    public ?AcademicPeriod $activePeriod = null;
    public ?Student $student = null;
    public ?Enrollment $currentEnrollment = null;

    // --- LISTAS ---
    public Collection $enrolledCourses; // Los cursos (registrations) del estudiante
    public Collection $attendances; // Las asistencias del curso seleccionado

    // --- ESTADO ---
    public $selectedRegistrationId = null;

    // --- RESUMEN ---
    public $summary = [
        'present' => 0,
        'late' => 0,
        'absent' => 0,
        'justified' => 0,
        'percentage' => 100,
    ];

    public function mount()
    {
        $this->student = Auth::user()->student;
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->enrolledCourses = collect();
        $this->attendances = collect();

        if ($this->student && $this->activePeriod) {
            $this->currentEnrollment = Enrollment::where('student_id', $this->student->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->first();

            if ($this->currentEnrollment) {
                $this->enrolledCourses = $this->currentEnrollment->registrations()
                    ->with('teacherAssignment.didacticUnit')
                    ->get();
            }
        }
    }

    /**
     * Hook: Se dispara al seleccionar un curso.
     */
    public function updatedSelectedRegistrationId($regId)
    {
        if (empty($regId)) {
            $this->attendances = collect();
            $this->reset('summary');
            return;
        }

        // 1. Cargar el detalle de asistencias
        $this->attendances = Attendance::where('registration_id', $regId)
            ->orderBy('class_date', 'desc')
            ->get();

        // 2. Calcular el resumen
        $counts = $this->attendances->countBy('attendance_type');

        $present = $counts->get('present', 0);
        $late = $counts->get('late', 0);
        $absent = $counts->get('absent', 0);
        $justified = $counts->get('justified', 0);

        $validAssist = $present + $late + $justified;
        $totalSessions = $present + $late + $absent + $justified;

        $this->summary = [
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'justified' => $justified,
            'percentage' => ($totalSessions > 0) ? ($validAssist / $totalSessions) * 100 : 100,
        ];
    }

    public function render()
    {
        return view('livewire.pages.student.my-attendances');
    }
}

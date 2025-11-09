<?php

namespace App\Livewire\Pages\Evaluation\Attendances;

use App\Models\AcademicPeriod;
use App\Models\Attendance;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AttendanceManager extends Component
{
    // --- PROPIEDADES DE ESTADO Y FILTROS ---
    public ?AcademicPeriod $activePeriod = null;
    public ?Teacher $currentTeacher = null;
    public $selectedAssignmentId = '';
    public $selectedScheduleId = '';
    public $selectedDate = '';

    // --- COLECCIONES PARA DROPDOWNS ---
    public Collection $assignments; // Secciones del docente
    public Collection $schedules;   // Horarios de la sección seleccionada

    // --- DATOS DE LA LISTA ---
    public Collection $registrations; // Estudiantes matriculados
    
    // Array para el data binding (wire:model)
    // Formato: $attendances[registration_id] = 'present'
    public $attendances = [];
    public $isAttendanceTaken = false; // ¿Ya se guardó la asistencia para este día?

    /**
     * Hook 'mount': Carga datos iniciales.
     */
    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->currentTeacher = Auth::user()->teacher;
        
        $this->loadAssignments();
        
        $this->schedules = collect();
        $this->registrations = collect();
        $this->selectedDate = now()->format('Y-m-d');
    }

    /**
     * Carga las asignaciones (secciones) del docente.
     */
    public function loadAssignments()
    {
        if ($this->currentTeacher && $this->activePeriod) {
            $this->assignments = TeacherAssignment::where('teacher_id', $this->currentTeacher->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->with('didacticUnit', 'shift')
                ->get()
                ->mapWithKeys(fn($a) => 
                    [$a->id => "{$a->didacticUnit->name} (Sec. {$a->section} - {$a->shift->name})"]
                );
        } else {
            $this->assignments = collect();
        }
    }

    /**
     * Hook: Cuando se selecciona una sección (asignación).
     */
    public function updatedSelectedAssignmentId($value)
    {
        $this->selectedScheduleId = '';
        $this->registrations = collect();
        $this->attendances = [];
        
        if (empty($value)) {
            $this->schedules = collect();
            return;
        }

        // Cargar los horarios (bloques) de esta sección
        $this->schedules = Schedule::where('teacher_assignment_id', $value)
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
            ->orderBy('start_time')
            ->get();
            
        // [CÓDIGO CORREGIDO]
        // Cargar los estudiantes usando la relación correcta
        $this->registrations = Registration::where('teacher_assignment_id', $value)
            ->with('enrollment.student.user') // <-- RUTA CORREGIDA
            ->where('status', 'enrolled')
            ->get()
            ->sortBy('enrollment.student.user.name'); // <-- RUTA CORREGIDA
            
        // Auto-seleccionar el primer horario si solo hay uno
        if ($this->schedules->count() == 1) {
            $this->selectedScheduleId = $this->schedules->first()->id;
            $this->updatedSelectedScheduleId($this->selectedScheduleId);
        }
    }

    /**
     * Hook: Cuando se selecciona un bloque de horario.
     */
    public function updatedSelectedScheduleId($value)
    {
        $this->loadAttendances();
    }
    
    /**
     * Hook: Cuando se cambia la fecha.
     */
    public function updatedSelectedDate($value)
    {
        $this->loadAttendances();
    }
    
    /**
     * Carga las asistencias existentes para la sección, horario y fecha seleccionados.
     */
    public function loadAttendances()
    {
        if (empty($this->selectedScheduleId) || empty($this->selectedDate)) {
            $this->attendances = [];
            $this->isAttendanceTaken = false;
            return;
        }

        // Buscar asistencias existentes
        $existingAttendances = Attendance::where('schedule_id', $this->selectedScheduleId)
            ->where('class_date', $this->selectedDate)
            ->get()
            ->keyBy('registration_id'); // Indexar por ID de estudiante

        $this->attendances = [];
        $this->isAttendanceTaken = $existingAttendances->isNotEmpty();

        foreach ($this->registrations as $registration) {
            $regId = $registration->id;
            
            if ($this->isAttendanceTaken) {
                // Si ya se tomó, cargar el valor guardado
                $this->attendances[$regId] = $existingAttendances->get($regId)?->attendance_type ?? 'absent';
            } else {
                // Si es la primera vez, poner "presente" por defecto
                $this->attendances[$regId] = 'present';
            }
        }
    }

    /**
     * Guarda todas las asistencias de la lista.
     */
    public function saveAttendances()
    {
        if (empty($this->selectedScheduleId) || empty($this->selectedDate)) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Seleccione una sección, horario y fecha.']);
            return;
        }
        
        // Validar que todos los estudiantes tengan un estado
        foreach ($this->registrations as $registration) {
            if (empty($this->attendances[$registration->id])) {
                $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Todos los estudiantes deben tener un estado de asistencia.']);
                return;
            }
        }

        try {
            DB::transaction(function () {
                foreach ($this->registrations as $registration) {
                    $regId = $registration->id;
                    $attendanceType = $this->attendances[$regId];

                    Attendance::updateOrCreate(
                        [
                            'registration_id' => $regId,
                            'schedule_id' => $this->selectedScheduleId,
                            'class_date' => $this->selectedDate,
                        ],
                        [
                            'attendance_type' => $attendanceType,
                            'late_minutes' => ($attendanceType == 'late') ? 10 : 0, // Lógica simple de tardanza
                            'registered_by_user_id' => Auth::id(),
                        ]
                    );
                }
            });

            $this->isAttendanceTaken = true; // Marcar como guardado
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Hecho!',
                'text' => 'Asistencia guardada correctamente.',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al Guardar',
                'text' => $e->getMessage(),
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        if (!$this->currentTeacher) {
            return view('livewire.pages.evaluation.grades.grade-manager-no-teacher')
                   ->with('message', 'Su cuenta de usuario no está asociada a un perfil de docente.');
        }
        
        return view('livewire.pages.evaluation.attendances.attendance-manager');
    }
}
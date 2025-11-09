<?php

namespace App\Livewire\Pages\Enrollment;

use App\Models\AcademicPeriod;
use App\Models\AcademicRecord;
use App\Models\DidacticUnit; // <-- [NUEVO] Importar
use App\Models\Enrollment;
use App\Models\Registration;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\TeacherAssignment;
use App\Models\PaymentConcept;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EnrollmentProcess extends Component
{
    public ?AcademicPeriod $activePeriod = null;
    public ?Student $student = null;
    public ?Enrollment $currentEnrollment = null;

    // --- DATOS PARA LA VISTA ---
    public Collection $coursesToEnroll;  // Cursos que el sistema ha determinado
    public Collection $confirmedSchedules; // Horario de la matrícula confirmada
    public Collection $academicHistory;  // Cursos aprobados

    // --- ESTADO DE LA MATRÍCULA ---
    public $step = 'loading'; // loading, payment, confirmation, confirmed
    public ?StudentPayment $pendingEnrollmentPayment = null;
    public $hasConflicts = false; // Flag para bloquear la matrícula

    /**
     * Hook 'mount': Carga el estado del estudiante.
     */
    public function mount()
    {
        $this->student = Auth::user()->student;
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();

        if (!$this->student || !$this->activePeriod) {
            $this->step = 'error';
            return;
        }

        // 1. Cargar historial de cursos aprobados
        $this->academicHistory = AcademicRecord::where('student_id', $this->student->id)
            ->where('course_status', 'approved')
            ->pluck('didactic_unit_id')
            ->toBase();

        // 2. Revisar si ya está matriculado en este periodo
        $this->currentEnrollment = Enrollment::where('student_id', $this->student->id)
            ->where('academic_period_id', $this->activePeriod->id)
            ->first();
            
        // 3. Revisar pago de matrícula
        $this->checkPaymentStatus();
    }

    /**
     * Revisa el estado del pago de matrícula.
     */
    public function checkPaymentStatus()
    {
        $enrollmentConcept = PaymentConcept::where('code', 'MAT-REG')->first();
        if (!$enrollmentConcept) {
            $this->step = 'error';
            return;
        }

        $this->pendingEnrollmentPayment = StudentPayment::where('student_id', $this->student->id)
            ->where('academic_period_id', $this->activePeriod->id)
            ->where('payment_concept_id', $enrollmentConcept->id)
            ->where('status', 'pending')
            ->first();
            
        if ($this->pendingEnrollmentPayment) {
            $this->step = 'payment'; // El estudiante DEBE pagar primero
        } else {
            $this->calculateEnrollmentCourses(); // El estudiante ya pagó, calcular cursos
        }
    }

    /**
     * [NUEVA LÓGICA]
     * Calcula los cursos que el estudiante debe llevar.
     */
    public function calculateEnrollmentCourses()
    {
        if ($this->currentEnrollment) {
            // Si ya está matriculado, cargar los cursos seleccionados
            $this->step = 'confirmed';
            $this->loadConfirmedEnrollment();
        } else {
            // Si no está matriculado, calcular y mostrar la selección
            $this->step = 'confirmation';
            
            // 1. Obtener cursos desaprobados de periodos anteriores
            $failed_unit_ids = AcademicRecord::where('student_id', $this->student->id)
                ->where('course_status', 'failed')
                ->pluck('didactic_unit_id');

            // 2. Obtener cursos del semestre actual del estudiante
            $current_semester_unit_ids = DidacticUnit::where('semester', $this->student->current_semester)
                ->whereHas('module.studyPlan', fn($q) => $q->where('id', $this->student->study_plan_id))
                ->pluck('id');

            // 3. Combinar listas (Regla de Negocio #2 y #3)
            $unit_ids_to_take = $failed_unit_ids->merge($current_semester_unit_ids)
                                ->unique()
                                ->diff($this->academicHistory); // Quitar los ya aprobados

            // 4. Encontrar las secciones (TeacherAssignment) para esos cursos
            //    (Asumimos que solo hay 1 sección por curso, ej. "Sección A")
            $this->coursesToEnroll = TeacherAssignment::with(['didacticUnit', 'shift', 'schedules.classroomResource'])
                ->where('academic_period_id', $this->activePeriod->id)
                ->where('status', 'active')
                ->whereIn('didactic_unit_id', $unit_ids_to_take)
                // Agrupar por curso y tomar la primera sección (ej. 'A')
                // Esta lógica debe mejorarse si hay múltiples secciones (A, B, C)
                ->get()
                ->keyBy('didactic_unit_id') 
                ->map(fn($assignment) => $this->validateCourseAvailability($assignment)); // Validar c/u

            // 5. Validar conflictos de horario
            $this->validateScheduleConflicts();
        }
    }
    
    /**
     * Carga los datos de una matrícula ya confirmada.
     */
    public function loadConfirmedEnrollment()
    {
        $registrations = $this->currentEnrollment
            ->registrations()
            ->with(['teacherAssignment.didacticUnit', 'teacherAssignment.teacher.user', 'teacherAssignment.shift', 'teacherAssignment.schedules.classroomResource'])
            ->get();
        
        $this->coursesToEnroll = $registrations->pluck('teacherAssignment');
        $this->confirmedSchedules = $this->coursesToEnroll
            ->pluck('schedules')
            ->flatten()
            ->sortBy('day_of_week')
            ->sortBy('start_time');
    }
    
    /**
     * Valida vacantes y prerrequisitos (la lógica de prerrequisitos ya está en la consulta).
     */
    private function validateCourseAvailability(TeacherAssignment $assignment)
    {
        $assignment->validation_status = 'available';
        $assignment->validation_message = '';

        if ($assignment->current_enrolled >= $assignment->max_capacity) {
            $assignment->validation_status = 'unavailable';
            $assignment->validation_message = 'Sin vacantes';
            $this->hasConflicts = true;
        }
        return $assignment;
    }
    
    /**
     * Valida cruces de horario en la lista de cursos a matricular.
     */
    public function validateScheduleConflicts()
    {
        $scheduleSlots = [];
        $this->hasConflicts = false; // Resetear flag

        foreach ($this->coursesToEnroll as $course) {
            // Si el curso ya tiene un error de vacantes, no lo proceses
            if ($course->validation_status !== 'available') continue;

            foreach ($course->schedules as $schedule) {
                $slot = $schedule->day_of_week . '_' . $schedule->start_time . '_' . $schedule->end_time;
                if (isset($scheduleSlots[$slot])) {
                    // ¡Conflicto!
                    $this->hasConflicts = true;
                    $course->validation_status = 'conflict';
                    $course->validation_message = 'Cruce de horario';
                    
                    $conflictingCourseId = $scheduleSlots[$slot];
                    if (isset($this->coursesToEnroll[$conflictingCourseId])) {
                        $this->coursesToEnroll[$conflictingCourseId]->validation_status = 'conflict';
                        $this->coursesToEnroll[$conflictingCourseId]->validation_message = 'Cruce de horario';
                    }
                } else {
                    $scheduleSlots[$slot] = $course->id;
                }
            }
        }
    }

    /**
     * Acción final: Confirmar la matrícula.
     */
    public function confirmEnrollment()
    {
        if ($this->hasConflicts || $this->step !== 'confirmation') return;
        
        try {
            DB::transaction(function () {
                $enrollment = Enrollment::create([
                    'student_id' => $this->student->id,
                    'academic_period_id' => $this->activePeriod->id,
                    'semester_enrolled' => $this->student->current_semester,
                    'enrollment_type' => 'continuing', 
                    'payment_status' => 'paid',
                    'status' => 'active',
                ]);
                
                foreach ($this->coursesToEnroll as $course) {
                    Registration::create([
                        'enrollment_id' => $enrollment->id,
                        'teacher_assignment_id' => $course->id,
                    ]);
                    $course->increment('current_enrolled');
                }
                
                $this->currentEnrollment = $enrollment;
                $this->loadConfirmedEnrollment();
                $this->step = 'confirmed';
            });
            
            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Matrícula Exitosa!', 'text' => 'Te has matriculado correctamente.']);

        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo completar la matrícula. ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        // Si estamos en el paso de selección, cargamos el horario para la vista
        if ($this->step == 'confirmation') {
             $this->confirmedSchedules = $this->coursesToEnroll
                ->pluck('schedules')
                ->flatten()
                ->sortBy('day_of_week')
                ->sortBy('start_time');
        }
        
        return view('livewire.pages.enrollment.enrollment-process');
    }
}
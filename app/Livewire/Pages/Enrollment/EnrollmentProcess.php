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
use App\Models\Institution; // <-- [NUEVO]

use Illuminate\Support\Facades\Storage; // <-- [NUEVO]
use Barryvdh\DomPDF\Facade\Pdf; // <-- [NUEVO]
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

    // ✅ AGREGADA: Propiedad que faltaba
    public Collection $schedules; // Horarios para la vista

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

        // ✅ CORREGIDO: Inicializar TODAS las colecciones
        $this->coursesToEnroll = collect(); // Inicializar como colección vacía
        $this->schedules = collect();       // ✅ AGREGADO
        $this->confirmedSchedules = collect(); // ✅ AGREGADO
        $this->academicHistory = collect(); // ✅ AGREGADO

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
            $this->coursesToEnroll = TeacherAssignment::with(['didacticUnit', 'shift', 'schedules.classroomResource', 'teacher.user'])
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
        // ✅ CORREGIDO: Asignamos correctamente a $coursesToEnroll
        $this->coursesToEnroll = $this->currentEnrollment
            ->registrations()
            ->with([
                'teacherAssignment.didacticUnit',
                'teacherAssignment.teacher.user',
                'teacherAssignment.shift',
                'teacherAssignment.schedules.classroomResource'
            ])
            ->get()
            ->pluck('teacherAssignment'); // Obtenemos las TeacherAssignments

        $this->loadSchedules(); // Cargar horarios
    }

    /**
     * Carga la lista de horarios de los cursos seleccionados.
     */
    public function loadSchedules()
    {
        // ✅ CORREGIDO: Simplificado para usar siempre coursesToEnroll
        $this->schedules = $this->coursesToEnroll
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

    /**
     * Genera y descarga la Ficha de Matrícula en PDF.
     */
    public function downloadEnrollmentForm()
    {
        // 1. Asegurarse de que el usuario está matriculado
        if ($this->step !== 'confirmed' || !$this->currentEnrollment) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Aún no se ha completado la matrícula.']);
            return;
        }

        $institution = Institution::first();

        // 2. Lógica del Logo Base64 (sin cambios)
        $logoData = null;
        if ($institution?->logo_url && Storage::disk('public')->exists($institution->logo_url)) {
            $path = Storage::disk('public')->path($institution->logo_url);
            $fileContent = file_get_contents($path);
            $mime = mime_content_type($path);
            $logoData = 'data:' . $mime . ';base64,' . base64_encode($fileContent);
        }

        // ✅ CORREGIDO: coursesToEnroll ya contiene los TeacherAssignment correctos
        // gracias a loadConfirmedEnrollment()
        $coursesToRender = $this->coursesToEnroll;

        $data = [
            'institution' => $institution,
            'logoData' => $logoData,
            'activePeriod' => $this->activePeriod,
            'student' => $this->student,
            'enrollment' => $this->currentEnrollment,
            'courses' => $coursesToRender, // ✅ Ya son TeacherAssignments
        ];

        $pdf = Pdf::loadView('reports.enrollment-form-pdf', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'ficha-matricula-' . $this->activePeriod->code . '-' . $this->student->code . '.pdf');
    }

    public function render()
    {
        // ✅ CORREGIDO: Cargar horarios confirmados tanto para confirmation como confirmed
        if ($this->step == 'confirmation' || $this->step == 'confirmed') {
            $this->confirmedSchedules = $this->coursesToEnroll
                ->pluck('schedules')
                ->flatten()
                ->sortBy('day_of_week')
                ->sortBy('start_time');
        }

        return view('livewire.pages.enrollment.enrollment-process');
    }
}

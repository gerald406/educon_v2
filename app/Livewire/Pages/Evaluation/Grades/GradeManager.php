<?php

namespace App\Livewire\Pages\Evaluation\Grades;

use App\Models\AcademicPeriod;
use App\Models\EvaluationType;
use App\Models\Grade;
use App\Models\Registration;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\AcademicRecord;
use App\Models\Institution;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app')]
class GradeManager extends Component
{
    // --- PROPIEDADES DE ESTADO Y FILTROS ---
    public ?AcademicPeriod $activePeriod = null;
    public ?Teacher $currentTeacher = null;
    public $selectedAssignmentId = '';

    // --- COLECCIONES PARA DROPDOWNS ---
    public Collection $assignments; // Secciones del docente

    // --- DATOS DE LA MATRIZ ---
    public Collection $registrations; // Estudiantes matriculados
    public Collection $evaluationTypes; // Columnas (Parcial, Final...)
    
    // Array para el data binding (wire:model)
    // Formato: $grades[registration_id][evaluation_type_id] = 13.5
    public $grades = [];

    // [NUEVAS PROPIEDADES]
    public $finalGrades = []; // Array para guardar los promedios
    public $isLocked = false;  // Para bloquear la edición
    public $minPassingGrade = 13; // Nota mínima (debería venir de SystemSettings)


    // --- [PROPIEDADES DE BLOQUEO ACTUALIZADAS] ---
    public $isOutOfDate = false; // Bloqueado por fecha
    public $gradeEntryMessage = ''; // Mensaje para mostrar al docente

    /**
     * Hook 'mount': Carga datos iniciales del docente y periodo.
     */
    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->currentTeacher = Auth::user()->teacher; 
        
        // Cargar la nota mínima desde la BD (si existe)
        $setting = SystemSetting::where('key_name', 'minimum_passing_grade')->first();
        if ($setting) {
            $this->minPassingGrade = (int)$setting->value;
        }

        $this->loadAssignments();
        $this->loadMatrixData();
        // --- [NUEVA LÓGICA DE VERIFICACIÓN DE FECHAS] ---
        $this->checkGradeEntryWindow();
    }


    /**
     * [NUEVO] Verifica si el docente está dentro de la ventana de registro de notas.
     */
    public function checkGradeEntryWindow()
    {
        if (!$this->activePeriod) {
            $this->isOutOfDate = true;
            $this->gradeEntryMessage = 'No hay un periodo académico activo.';
            return;
        }

        $now = now();
        $startDate = $this->activePeriod->grade_entry_start_date;
        $endDate = $this->activePeriod->grade_entry_end_date;

        if (!$startDate || !$endDate) {
            $this->isOutOfDate = true;
            $this->gradeEntryMessage = 'Las fechas para el registro de notas no han sido configuradas por administración.';
        } elseif ($now->isBefore($startDate)) {
            $this->isOutOfDate = true;
            $this->gradeEntryMessage = 'El registro de notas iniciará el: ' . $startDate->format('d/m/Y h:i A');
        } elseif ($now->isAfter($endDate)) {
            $this->isOutOfDate = true;
            $this->gradeEntryMessage = 'El registro de notas finalizó el: ' . $endDate->format('d/m/Y h:i A');
        } else {
            // Está dentro de la ventana
            $this->isOutOfDate = false;
            $this->gradeEntryMessage = 'El registro de notas cierra el: ' . $endDate->format('d/m/Y h:i A');
        }
    }

    /**
     * Carga las asignaciones (secciones) del docente en el periodo activo.
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
     * Hook: Se dispara cuando el usuario selecciona una sección.
     */
    public function updatedSelectedAssignmentId($value)
    {
        $this->loadMatrixData();
    }

    /**
     * Carga los estudiantes, tipos de evaluación y las notas existentes.
     */
    public function loadMatrixData()
    {
        if (empty($this->selectedAssignmentId)) {
            $this->registrations = collect();
            $this->evaluationTypes = collect();
            $this->grades = [];
            $this->isLocked = false; // [CAMBIO] Resetea el bloqueo
            return;
        }

        // 1. Cargar Estudiantes (Filas)
        $this->registrations = Registration::where('teacher_assignment_id', $this->selectedAssignmentId)
            ->with('enrollment.student.user') 
            ->where('status', 'enrolled')
            ->get()
            ->sortBy('enrollment.student.user.name'); 

        // 2. Cargar Tipos de Evaluación (Columnas)
        $this->evaluationTypes = EvaluationType::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        // [NUEVO] 3. Comprobar si las notas ya están finalizadas
        $assignment = TeacherAssignment::find($this->selectedAssignmentId);
        $existingRecord = AcademicRecord::where('academic_period_id', $this->activePeriod->id)
            ->where('didactic_unit_id', $assignment->didactic_unit_id)
            ->whereIn('student_id', $this->registrations->pluck('enrollment.student.id'))
            ->exists();
            
        $this->isLocked = $existingRecord; // Bloquea la UI si ya existen récords

        // 4. Cargar Notas existentes y poblar el array 'grades'
        $existingGrades = Grade::whereIn('registration_id', $this->registrations->pluck('id'))
            ->get()
            ->keyBy(fn($g) => $g->registration_id . '-' . $g->evaluation_type_id);

        // ... (loop existente para poblar $this->grades) ...
        $this->grades = [];
        foreach ($this->registrations as $registration) {
            foreach ($this->evaluationTypes as $type) {
                $key = $registration->id . '-' . $type->id;
                $this->grades[$registration->id][$type->id] = $existingGrades->get($key)?->grade;
            }
        }
        
        // [NUEVO] 5. Calcular promedios al cargar
        $this->calculateFinalGrades();
    }

    /**
     * [NUEVO] Muestra la alerta de confirmación
     */
    public function confirmFinalizeGrades()
    {
        // Validar que todos los promedios estén calculados
        foreach ($this->finalGrades as $grade) {
            if ($grade === null) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Proceso Incompleto',
                    'text' => 'No se puede finalizar. Faltan notas por registrar en uno o más estudiantes.',
                ]);
                return;
            }
        }

        $this->dispatch('swal:confirm', [
            'id' => $this->selectedAssignmentId, // Pasamos el ID de la asignación
            'title' => '¿Finalizar Registro de Notas?',
            'text' => '¡Esta acción es irreversible! Se calcularán los promedios finales y se bloqueará la edición.',
            'onConfirmed' => 'finalizeGrades'
        ]);
    }

    /**
     * [NUEVO] Bloquea las notas y las guarda en el historial académico.
     */
    #[On('finalizeGrades')]
    public function finalizeGrades()
    {
        if ($this->isLocked) return; // Doble chequeo

        $assignment = TeacherAssignment::find($this->selectedAssignmentId);

        try {
            DB::transaction(function () use ($assignment) {
                foreach ($this->registrations as $registration) {
                    $finalGrade = $this->finalGrades[$registration->id] ?? 0;
                    $status = ($finalGrade >= $this->minPassingGrade) ? 'approved' : 'failed';

                    AcademicRecord::updateOrCreate(
                        [
                            // Claves únicas para buscar
                            'student_id' => $registration->enrollment->student_id,
                            'didactic_unit_id' => $assignment->didactic_unit_id,
                            'academic_period_id' => $this->activePeriod->id,
                        ],
                        [
                            // Datos para actualizar o crear
                            'final_grade' => $finalGrade,
                            'credits_earned' => ($status == 'approved') ? $assignment->didacticUnit->credits : 0,
                            'course_status' => $status,
                            'times_taken' => 1, // (Mejorar esta lógica a futuro)
                        ]
                    );
                }
            });

            $this->isLocked = true; // Bloquea la UI
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Registro Finalizado!',
                'text' => 'Las notas han sido consolidadas y bloqueadas.',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al Finalizar',
                'text' => 'Ocurrió un error: ' . $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Guarda una nota específica (cuando el input pierde el foco).
     */
    public function saveGrade($registrationId, $evaluationTypeId)
    {
        // [NUEVO] No guardar si está bloqueado
        if ($this->isLocked) return;

        $value = $this->grades[$registrationId][$evaluationTypeId] ?? null;

        // Validar la nota (ej. 0-20)
        if ($value !== null && ($value < 0 || $value > 20)) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'La nota debe estar entre 0 y 20.']);
            // Revertir el valor
            $this->loadMatrixData(); 
            return;
        }
        
        // Guardar en la BD
        Grade::updateOrCreate(
            [
                'registration_id' => $registrationId,
                'evaluation_type_id' => $evaluationTypeId,
            ],
            [
                'grade' => $value,
                'evaluation_date' => now(),
                'registered_by_user_id' => Auth::id(),
            ]
        );

        // [NUEVO] Recalcular promedios después de guardar
        $this->calculateFinalGrades();
        
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Nota guardada',
            'toast' => true,
            'position' => 'bottom-end',
            'timer' => 2000,
        ]);
    }

    /**
     * [NUEVO] Calcula los promedios finales
     */
    private function calculateFinalGrades()
    {
        $this->finalGrades = [];
        foreach ($this->registrations as $registration) {
            $finalGrade = 0;
            $totalWeight = 0;
            
            foreach ($this->evaluationTypes as $type) {
                $grade = $this->grades[$registration->id][$type->id] ?? null;
                if ($grade !== null) {
                    $weight = $type->weight_percentage;
                    $finalGrade += ($grade * $weight / 100);
                    $totalWeight += $weight;
                }
            }
            
            // Solo muestra el promedio si se han ingresado todas las notas (peso = 100%)
            // (Puedes ajustar esta lógica si permites promedios parciales)
            if ($totalWeight >= 99.9) { 
                $this->finalGrades[$registration->id] = round($finalGrade);
            } else {
                $this->finalGrades[$registration->id] = null; // No mostrar si faltan notas
            }
        }
    }

    /**
     * Genera y descarga el Acta de Notas Finales en PDF.
     */
    public function downloadFinalGradesPdf()
    {
        // Solo permitir si las notas están bloqueadas
        if (!$this->isLocked) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Debe finalizar el registro de notas antes de descargar el acta.']);
            return;
        }

        $assignment = TeacherAssignment::with([
            'didacticUnit.module.studyPlan.career',
            'shift'
        ])->find($this->selectedAssignmentId);

        // Cargar los registros finales (los que se crearon con finalizeGrades)
        $finalRecords = AcademicRecord::with(['student.user'])
            ->where('academic_period_id', $this->activePeriod->id)
            ->where('didactic_unit_id', $assignment->didactic_unit_id)
            ->whereIn('student_id', $this->registrations->pluck('enrollment.student.id'))
            ->orderBy('student.user.name') // Ordenar por nombre
            ->get();

        if ($finalRecords->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se encontraron registros de notas finales.']);
            return;
        }

        $institution = Institution::first();

        // Lógica del Logo Base64 (de Fase 71)
        $logoData = null;
        if ($institution?->logo_url && Storage::disk('public')->exists($institution->logo_url)) {
            $path = Storage::disk('public')->path($institution->logo_url);
            $fileContent = file_get_contents($path);
            $mime = mime_content_type($path);
            $logoData = 'data:' . $mime . ';base64,' . base64_encode($fileContent);
        }

        $data = [
            'finalRecords' => $finalRecords,
            'assignment' => $assignment,
            'teacher' => $this->currentTeacher,
            'activePeriod' => $this->activePeriod,
            'institution' => $institution,
            'logoData' => $logoData,
        ];

        $pdf = Pdf::loadView('reports.final-grades-pdf', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'acta-notas-' . $assignment->didacticUnit->code . '.pdf');
    }

    // --- RENDER ---
    public function render()
    {
        // Advertencia si el usuario no es docente
        if (!$this->currentTeacher) {
            return view('livewire.pages.evaluation.grades.grade-manager-no-teacher');
        }
        
        return view('livewire.pages.evaluation.grades.grade-manager');
    }
}
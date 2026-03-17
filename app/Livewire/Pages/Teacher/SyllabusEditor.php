<?php

namespace App\Livewire\Pages\Teacher;

use App\Models\Syllabus;
use App\Models\SyllabusIndicator;
use App\Models\SyllabusUnit;
use App\Models\TeacherAssignment;
use Livewire\Attributes\Layout;
use Livewire\Component;

// AÑADIR junto a los otros use al inicio del archivo
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class SyllabusEditor extends Component
{
    public TeacherAssignment $assignment;
    public Syllabus $syllabus;

    public $activeTab = 'general';
    public $confirmingSubmission = false;

    // AÑADIR después de: public $confirmingSubmission = false;
    public function isEditable(): bool
    {
        return in_array($this->syllabus->status, ['draft', 'observed']);
    }

    // --- DATOS GENERALES ---
    public $study_program;
    public $study_plan;
    public $module_name;
    public $course_name;
    public $credits_info;
    public $total_hours;
    public $weekly_hours_info;
    public $period_name;
    public $academic_cycle;
    public $date_range;
    public $shift_name;
    public $teacher_name;
    public $teacher_email;

    // --- CONTENIDO DEL SÍLABO ---
    public $sumilla;
    public $unit_competence;
    public $course_capacity;
    public $employability_content;
    public $methodology;
    public $environments;
    public $resources;
    public $evaluation_system;
    public $bibliography;
    public $web_sources;

    // --- ESTRUCTURAS COMPLEJAS ---
    public $indicators;
    public $total_weeks_programmed = 0;

    // ✅ LISTENERS para sincronización con CKEditor
    protected $listeners = [
        'updateSumilla' => 'handleSumillaUpdate',
        'updateUnitCompetence' => 'handleUnitCompetenceUpdate',
        'updateCourseCapacity' => 'handleCourseCapacityUpdate',
        'updateEmployability' => 'handleEmployabilityUpdate',
        'updateMethodology' => 'handleMethodologyUpdate',
        'updateEnvironments' => 'handleEnvironmentsUpdate',
        'updateResources' => 'handleResourcesUpdate',
        'updateEvaluation' => 'handleEvaluationUpdate',
        'updateBibliography' => 'handleBibliographyUpdate',
        'updateWebSources' => 'handleWebSourcesUpdate',
    ];

    // REGLAS DE VALIDACIÓN
    // DESPUÉS
    protected $rules = [
        'indicators.*.units.*.name' => 'nullable|string',
        'indicators.*.units.*.content' => 'nullable|string',
        'indicators.*.units.*.learning_outcome' => 'nullable|string',
        'indicators.*.units.*.evaluation_instrument' => 'nullable|string',
        'teacher_email' => 'required|email',
        'teacher_name' => 'required|string|max:200',
        'study_program' => 'required|string|max:150',
        'study_plan' => 'required|string|max:100',
        'module_name' => 'required|string|max:150',
        'course_name' => 'required|string|max:200',
        'credits_info' => 'nullable|string|max:50',
        'total_hours' => 'nullable|integer|min:1',
        'weekly_hours_info' => 'nullable|string|max:50',
        'period_name' => 'nullable|string|max:100',
        'academic_cycle' => 'nullable|integer|min:1|max:6',
        'date_range' => 'nullable|string|max:100',
        'shift_name' => 'nullable|string|max:100',
        'sumilla' => 'nullable',
        'unit_competence' => 'nullable',
        'course_capacity' => 'nullable',
        'methodology' => 'nullable',
        'resources' => 'nullable',
        'evaluation_system' => 'nullable',
        'bibliography' => 'nullable',
        'web_sources' => 'nullable',
        'employability_content' => 'nullable'
    ];

    // ========================================
    // HANDLERS para recibir datos de CKEditor
    // ========================================

    public function handleSumillaUpdate($content)
    {
        $this->sumilla = $content;
    }

    public function handleUnitCompetenceUpdate($content)
    {
        $this->unit_competence = $content;
    }

    public function handleCourseCapacityUpdate($content)
    {
        $this->course_capacity = $content;
    }

    public function handleEmployabilityUpdate($content)
    {
        $this->employability_content = $content;
    }

    public function handleMethodologyUpdate($content)
    {
        $this->methodology = $content;
    }

    public function handleEnvironmentsUpdate($content)
    {
        $this->environments = $content;
    }

    public function handleResourcesUpdate($content)
    {
        $this->resources = $content;
    }

    public function handleEvaluationUpdate($content)
    {
        $this->evaluation_system = $content;
    }

    public function handleBibliographyUpdate($content)
    {
        $this->bibliography = $content;
    }

    public function handleWebSourcesUpdate($content)
    {
        $this->web_sources = $content;
    }

    // ========================================
    // INICIALIZACIÓN
    // ========================================

    public function mount(TeacherAssignment $assignment)
    {
        if ($assignment->teacher_id !== auth()->user()->teacher->id) {
            abort(403, 'Acceso denegado.');
        }

        $this->assignment = $assignment->load([
            'didacticUnit.module.studyPlan.career',
            'academicPeriod',
            'teacher.user',
            'shift'
        ]);

        $this->syllabus = Syllabus::firstOrCreate(
            ['teacher_assignment_id' => $assignment->id],
            ['status' => 'draft', 'version' => '1.0']
        );

        $this->loadGeneralData();

        // Carga de campos de texto con valores por defecto
        $this->sumilla = $this->syllabus->sumilla ?? '';
        $this->unit_competence = $this->syllabus->unit_competence ?? '';
        $this->course_capacity = $this->syllabus->course_capacity ?? '';

        // Empleabilidad
        $storedEmployability = $this->syllabus->employability_competencies;
        $this->employability_content = is_array($storedEmployability) ? ($storedEmployability[0] ?? '') : ($storedEmployability ?? '');

        $this->methodology = $this->syllabus->methodology ?? '';
        $this->environments = $this->syllabus->environments ?? '';
        $this->resources = $this->syllabus->resources ?? '';
        $this->evaluation_system = $this->syllabus->evaluation_system ?? '';

        // DESPUÉS
        if (empty($this->evaluation_system)) {
            $this->evaluation_system = '
            <ul>
                <li>El sistema de calificación es vigesimal y la nota mínima aprobatoria para las unidades didácticas es 13.</li>
                <li>Se considera aprobado el módulo, siempre que se haya aprobado todas las unidades didácticas respectivas y la experiencia formativa en situaciones reales de trabajo, de acuerdo al plan de estudios.</li>
                <li>Los estudiantes podrán rendir evaluaciones de recuperación a fin de lograr la aprobación de la unidad didáctica dentro del mismo periodo de estudio, considerando criterios de calidad académica y de acuerdo a los lineamientos establecidos en el reglamento institucional.</li>
                <li>Los estudiantes que tengan unidades didácticas desaprobadas al final del semestre académico podrán volver a matricularse en el siguiente semestre académico o cuando se programe.</li>
                <li>El estudiante que acumulará inasistencias injustificadas en número mayor al 30% del total de horas programadas en la Unidad Didáctica, será desaprobado en forma automática con calificación cero, sin derecho a recuperación.</li>
                <li>La evaluación será permanente durante el desarrollo de las actividades de aprendizaje evaluando las evidencias y/o productos de cada indicador de evaluación.</li>
            </ul>';
        }

        $this->bibliography = $this->syllabus->bibliography ?? '';
        $this->web_sources = $this->syllabus->web_sources ?? '';
        $this->teacher_email = $this->assignment->teacher->user->email;

        $this->loadIndicatorsWithUnits();
    }

    private function loadGeneralData()
    {
        $unit = $this->assignment->didacticUnit;
        $plan = $unit->module->studyPlan;
        $this->study_program = $plan->career->name;
        $this->study_plan    = $plan->name;
        $this->module_name   = $unit->module->name;
        $this->course_name   = $unit->name;
        $this->credits_info  = "{$unit->credits} Créditos";
        $this->total_hours   = $unit->total_hours;
        $calculatedWeekly = ($unit->weekly_hours > 0) ? $unit->weekly_hours : ($unit->total_hours / 16);
        $this->weekly_hours_info = "{$calculatedWeekly} horas semanales";
        $this->period_name   = $this->assignment->academicPeriod->name;
        $this->academic_cycle = $unit->semester;
        $start = \Carbon\Carbon::parse($this->assignment->academicPeriod->start_date)->format('d/m/Y');
        $end   = \Carbon\Carbon::parse($this->assignment->academicPeriod->end_date)->format('d/m/Y');
        $this->date_range = "{$start} – {$end} (18 semanas)";
        $this->shift_name    = $this->assignment->shift->name ?? 'No asignado';
        $this->teacher_name  = $this->assignment->teacher->user->lastname . ' ' . $this->assignment->teacher->user->name;
    }

    /**
     * ✅ MANTENER COMO COLLECTION (NO usar toArray())
     */
    public function loadIndicatorsWithUnits()
    {
        $this->indicators = $this->syllabus->indicators()
            ->with(['units' => function ($q) {
                $q->orderBy('session_number');
            }])
            ->orderBy('sort_order')
            ->get(); // ← SIN ->toArray()

        $this->calculateTotalWeeks();
    }

    /**
     * ✅ Trabajar con Collection
     */
    public function calculateTotalWeeks()
    {
        $this->total_weeks_programmed = $this->indicators->sum(function ($indicator) {
            return $indicator->units->count();
        });
    }

    // ========================================
    // LÓGICA DE PROGRAMACIÓN (TAB VI)
    // ========================================

    public function addSession($indicatorId)
    {
        $indicator = SyllabusIndicator::find($indicatorId);
        if (!$indicator) return;

        $nextSessionNumber = $this->total_weeks_programmed + 1;

        if ($nextSessionNumber > 18) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Límite alcanzado', 'text' => 'Ya has programado las 18 semanas.']);
            return;
        }

        $indicator->units()->create([
            'session_number' => $nextSessionNumber,
            'name' => 'Nueva Sesión',
            'content' => '',
            'learning_outcome' => '',
            'evaluation_instrument' => 'Lista de Cotejo',
        ]);

        $this->loadIndicatorsWithUnits();
    }

    // DESPUÉS
    public function removeSession($unitId)
    {
        // Verificar que la sesión pertenece al sílabo del docente autenticado
        $unit = SyllabusUnit::whereHas('indicator', function ($q) {
            $q->where('syllabus_id', $this->syllabus->id);
        })->find($unitId);

        if (!$unit) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Acceso denegado',
                'text' => 'No tienes permiso para eliminar esta sesión.'
            ]);
            return;
        }

        $unit->delete();
        $this->reorderSessions();
        $this->loadIndicatorsWithUnits();
    }

    private function reorderSessions()
    {
        $allUnits = SyllabusUnit::whereHas('indicator', function ($q) {
            $q->where('syllabus_id', $this->syllabus->id);
        })->orderBy('id')->get();

        foreach ($allUnits as $index => $unit) {
            $unit->update(['session_number' => $index + 1]);
        }
    }

    // ========================================
    // MÉTODOS DE GUARDADO
    // ========================================

    // DESPUÉS
    public function saveGeneral()
    {
        $this->validate([
            'teacher_email' => 'required|email',
            'teacher_name'  => 'required|string|max:200',
        ]);

        try {
            // 1. Actualizar datos del usuario (email y nombre del docente)
            $nameParts = explode(' ', trim($this->teacher_name), 2);
            $this->assignment->teacher->user->update([
                'name'     => $nameParts[0] ?? $this->teacher_name,
                'lastname' => $nameParts[1] ?? '',
                'email'    => $this->teacher_email,
            ]);

            // 2. Actualizar datos de la unidad didáctica
            $this->assignment->didacticUnit->update([
                'name'         => $this->course_name,
                'total_hours'  => $this->total_hours,
                'credits'      => (int) filter_var($this->credits_info, FILTER_SANITIZE_NUMBER_INT),
                'semester'     => $this->academic_cycle,
            ]);

            // 3. Actualizar nombre del módulo
            $this->assignment->didacticUnit->module->update([
                'name' => $this->module_name,
            ]);

            // 4. Actualizar nombre del plan de estudios
            $this->assignment->didacticUnit->module->studyPlan->update([
                'name' => $this->study_plan,
            ]);

            // 5. Actualizar nombre de la carrera
            $this->assignment->didacticUnit->module->studyPlan->career->update([
                'name' => $this->study_program,
            ]);

            // 6. Actualizar turno
            $this->assignment->shift->update([
                'name' => $this->shift_name,
            ]);

            // 7. Recargar datos generales para reflejar cambios
            $this->assignment->refresh()->load([
                'didacticUnit.module.studyPlan.career',
                'academicPeriod',
                'teacher.user',
                'shift'
            ]);
            $this->loadGeneralData();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Datos generales actualizados correctamente.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * ✅ Guardar Sumilla
     */
    public function saveSumilla()
    {
        try {
            $this->syllabus->update(['sumilla' => $this->sumilla]);
            // $this->syllabus->refresh();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Sumilla actualizada correctamente.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * ✅ Guardar Competencias
     */
    public function saveCompetencies()
    {
        try {
            $this->syllabus->update(['unit_competence' => $this->unit_competence]);
            // $this->syllabus->refresh();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Competencias actualizadas.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    // TAB IV
    public function addIndicator()
    {
        $newIndicator = $this->syllabus->indicators()->create([
            'description' => 'Nuevo indicador',
            'sort_order' => $this->indicators->count() + 1
        ]);

        $this->loadIndicatorsWithUnits();
    }

    // DESPUÉS
    public function removeIndicator($id)
    {
        // Verificar que el indicador pertenece al sílabo del docente autenticado
        $indicator = SyllabusIndicator::where('syllabus_id', $this->syllabus->id)
            ->find($id);

        if (!$indicator) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Acceso denegado',
                'text' => 'No tienes permiso para eliminar este indicador.'
            ]);
            return;
        }

        $indicator->delete();
        $this->loadIndicatorsWithUnits();
    }

    /**
     * ✅ Guardar Capacidad e Indicadores
     */
    public function saveCapacityAndIndicators()
    {
        try {
            // 1. Guardar la capacidad del curso
            $this->syllabus->update(['course_capacity' => $this->course_capacity]);

            // 2. Guardar cada indicador (trabajando con Collection)
            foreach ($this->indicators as $indicator) {
                if ($indicator->id && isset($indicator->description)) {
                    SyllabusIndicator::where('id', $indicator->id)->update([
                        'description' => $indicator->description
                    ]);
                }
            }

            $this->syllabus->refresh();
            $this->loadIndicatorsWithUnits();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Capacidad e indicadores guardados correctamente.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    public function saveEmployability()
    {
        try {
            $this->syllabus->update([
                'employability_competencies' => [$this->employability_content]
            ]);
            // $this->syllabus->refresh();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Empleabilidad actualizada.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * ✅ Guardar Programación (trabajando con Collection)
     */
    // DESPUÉS
    public function saveProgramming()
    {
        try {
            DB::transaction(function () {
                foreach ($this->indicators as $indicator) {
                    if (!$indicator->units || $indicator->units->isEmpty()) continue;

                    foreach ($indicator->units as $unit) {
                        if (!$unit->id) continue;

                        // Verificar pertenencia antes de actualizar
                        $exists = SyllabusUnit::whereHas('indicator', function ($q) {
                            $q->where('syllabus_id', $this->syllabus->id);
                        })->where('id', $unit->id)->exists();

                        if (!$exists) continue;

                        SyllabusUnit::where('id', $unit->id)->update([
                            'name'                 => $unit->name ?? '',
                            'content'              => $unit->content ?? '',
                            'learning_outcome'     => $unit->learning_outcome ?? '',
                            'evaluation_instrument' => $unit->evaluation_instrument ?? '',
                        ]);
                    }
                }
            });

            // Recargar solo después de que toda la transacción fue exitosa
            $this->loadIndicatorsWithUnits();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Programación Guardada',
                'text' => 'Todas las sesiones han sido registradas correctamente.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * ✅ NUEVO MÉTODO: Actualizar campos individuales de las sesiones en tiempo real
     */
    // DESPUÉS
    public function updateUnitField($unitId, $fieldName, $value)
    {
        try {
            // 1. Validar campo permitido
            $allowedFields = ['name', 'content', 'learning_outcome', 'evaluation_instrument'];

            if (!in_array($fieldName, $allowedFields)) {
                throw new \Exception('Campo no permitido');
            }

            // 2. Verificar pertenencia al sílabo del docente autenticado
            $unit = SyllabusUnit::whereHas('indicator', function ($q) {
                $q->where('syllabus_id', $this->syllabus->id);
            })->find($unitId);

            if (!$unit) {
                throw new \Exception('Acceso denegado');
            }

            // 3. Actualizar solo el campo específico
            $unit->update([$fieldName => $value]);

            // 4. Solo recalcular semanas, NO recargar toda la colección
            $this->calculateTotalWeeks();
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * ✅ NUEVO: Actualizar descripción de indicador individual
     */
    // DESPUÉS
    public function updateIndicatorDescription($indicatorId, $description)
    {
        try {
            // Verificar que el indicador pertenece al sílabo del docente autenticado
            $indicator = SyllabusIndicator::where('syllabus_id', $this->syllabus->id)
                ->find($indicatorId);

            if (!$indicator) {
                throw new \Exception('Acceso denegado');
            }

            $indicator->update(['description' => $description]);

            // Solo recalcular, NO recargar toda la colección
            $this->calculateTotalWeeks();
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar: ' . $e->getMessage()
            ]);
        }
    }

    public function saveMethodology()
    {
        try {
            $this->syllabus->update(['methodology' => $this->methodology]);
            // $this->syllabus->refresh();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Metodología actualizada.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    public function saveResources()
    {
        try {
            $this->syllabus->update([
                'environments' => $this->environments,
                'resources' => $this->resources
            ]);
            // $this->syllabus->refresh();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Recursos actualizados.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    public function saveEvaluation()
    {
        try {
            $this->syllabus->update(['evaluation_system' => $this->evaluation_system]);
            // $this->syllabus->refresh();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Evaluación actualizada.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    public function saveSources()
    {
        try {
            $this->syllabus->update([
                'bibliography' => $this->bibliography,
                'web_sources' => $this->web_sources
            ]);
            // $this->syllabus->refresh();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Guardado',
                'text' => 'Fuentes actualizadas.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar: ' . $e->getMessage()
            ]);
        }
    }

    // DESPUÉS
    public function confirmSubmit()
    {
        // Guarda de seguridad: verificar estado actual en BD (no solo en memoria)
        $currentStatus = Syllabus::find($this->syllabus->id)?->status;

        if (!in_array($currentStatus, ['draft', 'observed'])) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Acción no permitida',
                'text' => 'Este sílabo no puede ser enviado en su estado actual.'
            ]);
            return;
        }

        if (
            empty(trim(strip_tags($this->sumilla))) ||
            empty(trim(strip_tags($this->methodology))) ||
            empty(trim(strip_tags($this->evaluation_system))) ||
            $this->total_weeks_programmed == 0
        ) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Sílabo Incompleto',
                'text' => 'Debe completar la Sumilla, Metodología, Evaluación y programar al menos una sesión antes de enviar a aprobación.'
            ]);
            return;
        }

        $this->confirmingSubmission = true;
    }

    public function submitSyllabus()
    {
        $this->syllabus->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        $this->confirmingSubmission = false;
        session()->flash('flash.banner', '¡Sílabo enviado correctamente!');

        return redirect()->route('teacher.my-syllabi');
    }

    public function render()
    {
        return view('livewire.pages.teacher.syllabus-editor');
    }
}

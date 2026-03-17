<?php

namespace App\Livewire\Pages\Teacher;

use App\Models\LearningSession;
use App\Models\Syllabus;
use App\Models\SyllabusUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LearningSessionEditor extends Component
{
    public Syllabus        $syllabus;
    public SyllabusUnit    $unit;
    public LearningSession $session;

    // --- Campos del formulario ---
    public string $transversal_competence  = '';
    public string $activity_type           = 'teorico-practico';
    public string $evaluation_criteria     = '';
    public string $evaluation_technique    = '';
    public string $evaluation_instrument   = '';
    public string $evaluation_moment       = '';
    public string $bibliography            = '';
    public string $status                  = 'pending';
    public string $week_range = '';

    // Momentos de la secuencia didáctica
    public array $sequence_activities = [];

    public function mount(Syllabus $syllabus, SyllabusUnit $unit)
    {
        // Seguridad: verificar que el sílabo pertenece al docente autenticado
        $teacher = Auth::user()->teacher;

        if (!$teacher || $syllabus->teacherAssignment->teacher_id !== $teacher->id) {
            abort(403, 'No tienes permiso para editar esta sesión.');
        }

        // Cargar todas las relaciones necesarias para la vista
        $syllabus->load([
            'teacherAssignment.didacticUnit.module.studyPlan.career',
            'teacherAssignment.academicPeriod',
            'teacherAssignment.teacher.user',
            'teacherAssignment.shift',
        ]);

        $unit->load(['indicator', 'learningSession']);

        $this->syllabus = $syllabus;
        $this->unit     = $unit;
        $this->session  = $unit->learningSession;

        // Hidratar campos del formulario desde la sesión existente
        $this->transversal_competence = $this->session->transversal_competence ?? '';
        $this->activity_type          = $this->session->activity_type          ?? 'teorico-practico';
        $this->evaluation_criteria    = $this->session->evaluation_criteria    ?? '';
        $this->evaluation_technique   = $this->session->evaluation_technique   ?? '';
        $this->evaluation_instrument  = $this->session->evaluation_instrument  ?? $unit->evaluation_instrument ?? '';
        $this->evaluation_moment      = $this->session->evaluation_moment      ?? '';
        $this->bibliography           = $this->session->bibliography           ?? '';
        $this->status                 = $this->session->status                 ?? 'pending';
        $this->week_range = $this->unit->week_range ?? '';

        // Hidratar secuencia didáctica
        $this->sequence_activities = $this->session->sequence_activities ?? [
            [
                'moment'    => 'inicio',
                'label'     => 'Inicio',
                'hint'      => 'Motivación, Recojo de saberes previos, Conflicto cognitivo',
                'activity'  => '',
                'resources' => '',
                'time'      => 20,
            ],
            [
                'moment'    => 'desarrollo',
                'label'     => 'Desarrollo',
                'hint'      => 'Construcción del aprendizaje, Aplicación',
                'activity'  => '',
                'resources' => '',
                'time'      => 60,
            ],
            [
                'moment'    => 'cierre',
                'label'     => 'Cierre',
                'hint'      => 'Metacognición y evaluación',
                'activity'  => '',
                'resources' => '',
                'time'      => 10,
            ],
        ];
    }

    // ============================================
    // GUARDAR
    // ============================================

    public function save(string $newStatus = null)
    {
        $this->validate([
            'activity_type'                   => 'required|in:teorico,practico,teorico-practico',
            'transversal_competence'          => 'nullable|string|max:255',
            'evaluation_criteria'             => 'nullable|string',
            'evaluation_technique'            => 'nullable|string|max:255',
            'evaluation_instrument'           => 'nullable|string|max:255',
            'evaluation_moment'               => 'nullable|string|max:100',
            'bibliography'                    => 'nullable|string',
            'sequence_activities'             => 'required|array|size:3',
            'sequence_activities.*.activity'  => 'nullable|string',
            'sequence_activities.*.resources' => 'nullable|string|max:255',
            'sequence_activities.*.time'      => 'nullable|integer|min:1|max:300',
            'week_range' => 'nullable|string|max:100',
        ]);

        $saveStatus = $newStatus ?? $this->status;

        $this->session->update([
            'transversal_competence' => $this->transversal_competence,
            'activity_type'          => $this->activity_type,
            'sequence_activities'    => $this->sequence_activities,
            'evaluation_criteria'    => $this->evaluation_criteria,
            'evaluation_technique'   => $this->evaluation_technique,
            'evaluation_instrument'  => $this->evaluation_instrument,
            'evaluation_moment'      => $this->evaluation_moment,
            'bibliography'           => $this->bibliography,
            'status'                 => $saveStatus,
            'completed_at'           => in_array($saveStatus, ['completed', 'executed'])
                ? now()
                : null,
        ]);

        // AÑADIR después de $this->session->update([...])
        $this->unit->update([
            'week_range' => $this->week_range,
        ]);

        $this->status = $saveStatus;

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => '¡Guardado!',
            'text'  => 'Sesión de aprendizaje guardada correctamente.',
        ]);
    }

    public function saveAndComplete()
    {
        $this->save('completed');
    }

    public function render()
    {
        return view('livewire.pages.teacher.learning-session-editor');
    }
}

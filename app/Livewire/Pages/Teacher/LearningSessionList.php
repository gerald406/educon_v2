<?php

namespace App\Livewire\Pages\Teacher;

use App\Models\LearningSession;
use App\Models\Syllabus;
use App\Models\SyllabusUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LearningSessionList extends Component
{
    public Syllabus $syllabus;

    public function mount(Syllabus $syllabus)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher || $syllabus->teacherAssignment->teacher_id !== $teacher->id) {
            abort(403, 'No tienes permiso para acceder a estas sesiones.');
        }

        if ($syllabus->status !== 'approved') {
            abort(403, 'Solo puedes gestionar sesiones de sílabos aprobados.');
        }

        $this->syllabus = $syllabus;
        $this->generateMissingSessions();
    }

    /**
     * Crea automáticamente los registros en learning_sessions
     * para cada syllabus_unit que aún no tenga uno.
     */
    protected function generateMissingSessions(): void
    {
        $units = SyllabusUnit::whereHas('indicator', function ($q) {
            $q->where('syllabus_id', $this->syllabus->id);
        })->get();

        foreach ($units as $unit) {
            LearningSession::firstOrCreate(
                ['syllabus_unit_id' => $unit->id],
                [
                    'status'                 => 'pending',
                    'activity_type'          => 'teorico-practico',
                    'transversal_competence' => null,
                    'sequence_activities'    => [
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
                    ],
                ]
            );
        }
    }

    public function render()
    {
        $this->syllabus->load([
            'teacherAssignment.didacticUnit.module.studyPlan.career',
            'teacherAssignment.academicPeriod',
            'teacherAssignment.teacher.user',
            'indicators.units.learningSession',
        ]);

        // Calcular progreso
        $allUnits       = $this->syllabus->indicators->flatMap->units;
        $totalUnits     = $allUnits->count();
        $completedUnits = $allUnits->filter(
            fn($u) => in_array($u->learningSession?->status, ['completed', 'executed'])
        )->count();
        $percent = $totalUnits > 0 ? round(($completedUnits / $totalUnits) * 100) : 0;

        return view('livewire.pages.teacher.learning-session-list', [
            'syllabus'       => $this->syllabus,
            'totalUnits'     => $totalUnits,
            'completedUnits' => $completedUnits,
            'percent'        => $percent,
        ]);
    }
}

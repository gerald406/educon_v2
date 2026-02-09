<?php

namespace App\Exports;

use App\Models\Schedule;
use App\Models\TeacherAssignment;
use App\Models\Teacher;
use App\Models\ClassroomResource;
use App\Models\Career;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $type;
    protected $id;
    protected $periodId;
    protected $shiftId;

    public function __construct($type, $id, $periodId, $shiftId = null)
    {
        $this->type = $type;
        $this->id = $id;
        $this->periodId = $periodId;
        $this->shiftId = $shiftId;
    }

    public function collection()
    {
        $query = Schedule::with([
            'teacherAssignment.didacticUnit',
            'teacherAssignment.teacher.user',
            'teacherAssignment.shift',
            'classroomResource'
        ])->whereHas('teacherAssignment', function ($q) {
            $q->where('academic_period_id', $this->periodId);

            if ($this->shiftId) {
                $q->where('shift_id', $this->shiftId);
            }
        });

        // Aplicar filtros según el tipo
        match ($this->type) {
            'section' => $query->where('teacher_assignment_id', $this->id),
            'teacher' => $query->whereHas('teacherAssignment', fn($q) => $q->where('teacher_id', $this->id)),
            'classroom' => $query->where('classroom_resource_id', $this->id),
            'career' => $query->whereHas(
                'teacherAssignment.didacticUnit.module.studyPlan',
                fn($q) => $q->where('career_id', $this->id)
            ),
            default => null
        };

        return $query->get()->map(function ($schedule) {
            $days = [
                'monday' => 'Lunes',
                'tuesday' => 'Martes',
                'wednesday' => 'Miércoles',
                'thursday' => 'Jueves',
                'friday' => 'Viernes',
                'saturday' => 'Sábado'
            ];

            return [
                'dia' => $days[$schedule->day_of_week] ?? '',
                'hora_inicio' => $schedule->start_time->format('H:i'),
                'hora_fin' => $schedule->end_time->format('H:i'),
                'curso' => $schedule->teacherAssignment->didacticUnit->name,
                'seccion' => $schedule->teacherAssignment->section,
                'docente' => $schedule->teacherAssignment->teacher->user->name . ' ' .
                    $schedule->teacherAssignment->teacher->user->lastname,
                'aula' => $schedule->classroomResource->name ?? 'Sin asignar',
                'turno' => $schedule->teacherAssignment->shift->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Día',
            'Hora Inicio',
            'Hora Fin',
            'Curso',
            'Sección',
            'Docente',
            'Aula',
            'Turno'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Horarios';
    }
}

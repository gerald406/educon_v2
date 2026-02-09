<?php

namespace App\Exports;

use App\Models\Schedule;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeacherScheduleExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $teacherId;
    protected $periodId;
    protected $teacher;

    public function __construct($teacherId, $periodId)
    {
        $this->teacherId = $teacherId;
        $this->periodId = $periodId;
        $this->teacher = Teacher::with('user')->findOrFail($teacherId);
    }

    /**
     * Retornar colección de datos
     */
    public function collection()
    {
        return Schedule::with([
            'teacherAssignment.didacticUnit.module.studyPlan.career',
            'teacherAssignment.shift',
            'classroomResource'
        ])
            ->whereHas(
                'teacherAssignment',
                fn($q) =>
                $q->where('teacher_id', $this->teacherId)
                    ->where('academic_period_id', $this->periodId)
                    ->where('status', 'active')
            )
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Cabeceras
     */
    public function headings(): array
    {
        return [
            ['HORARIO DOCENTE: ' . $this->teacher->user->name . ' ' . $this->teacher->user->lastname],
            ['DNI: ' . $this->teacher->user->document_number],
            [],
            [
                'Día',
                'Hora Inicio',
                'Hora Fin',
                'Curso',
                'Sección',
                'Programa',
                'Semestre',
                'Turno',
                'Aula',
            ]
        ];
    }

    /**
     * Mapear cada fila
     */
    public function map($schedule): array
    {
        $days = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado'
        ];

        return [
            $days[$schedule->day_of_week] ?? $schedule->day_of_week,
            $schedule->start_time->format('H:i'),
            $schedule->end_time->format('H:i'),
            $schedule->teacherAssignment->didacticUnit->name,
            $schedule->teacherAssignment->section,
            $schedule->teacherAssignment->didacticUnit->module->studyPlan->career->name ?? 'N/A',
            $schedule->teacherAssignment->didacticUnit->semester,
            $schedule->teacherAssignment->shift->name ?? 'N/A',
            $schedule->classroomResource->name ?? 'Sin asignar',
        ];
    }

    /**
     * Estilos
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D3D3D3']]],
        ];
    }

    /**
     * Título de la hoja
     */
    public function title(): string
    {
        return 'Horario Docente';
    }
}

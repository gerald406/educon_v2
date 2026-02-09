<?php

namespace App\Exports;

use App\Models\Career;
use App\Models\Schedule;
use App\Models\Shift;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CareerScheduleExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $careerId;
    protected $periodId;
    protected $semester;
    protected $shiftId;
    protected $career;
    protected $shift;

    public function __construct($careerId, $periodId, $semester, $shiftId)
    {
        $this->careerId = $careerId;
        $this->periodId = $periodId;
        $this->semester = $semester;
        $this->shiftId = $shiftId;
        $this->career = Career::findOrFail($careerId);
        $this->shift = Shift::findOrFail($shiftId);
    }

    /**
     * Retornar colección de datos
     */
    public function collection()
    {
        return Schedule::with([
            'teacherAssignment.didacticUnit',
            'teacherAssignment.teacher.user',
            'teacherAssignment.shift',
            'classroomResource'
        ])
            ->whereHas('teacherAssignment', function ($q) {
                $q->where('academic_period_id', $this->periodId)
                    ->where('status', 'active')
                    ->where('shift_id', $this->shiftId)
                    ->whereHas(
                        'didacticUnit',
                        fn($sq) =>
                        $sq->where('semester', $this->semester)
                    )
                    ->whereHas(
                        'didacticUnit.module.studyPlan',
                        fn($sq) =>
                        $sq->where('career_id', $this->careerId)
                    );
            })
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
            ['HORARIO CONSOLIDADO'],
            ['Programa: ' . $this->career->name],
            ['Semestre: ' . $this->semester . ' | Turno: ' . $this->shift->name],
            [],
            [
                'Día',
                'Hora Inicio',
                'Hora Fin',
                'Curso',
                'Sección',
                'Docente',
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
            $schedule->teacherAssignment->teacher->user->name . ' ' . $schedule->teacherAssignment->teacher->user->lastname,
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
            3 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D3D3D3']]],
        ];
    }

    /**
     * Título de la hoja
     */
    public function title(): string
    {
        return 'Horario Consolidado';
    }
}

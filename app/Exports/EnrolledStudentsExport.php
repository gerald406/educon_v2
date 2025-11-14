<?php

namespace App\Exports;

use App\Models\TeacherAssignment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle; // Para el nombre de la pestaña
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EnrolledStudentsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $assignment;

    /**
     * Pasamos la asignación (sección) completa.
     */
    public function __construct(TeacherAssignment $assignment)
    {
        // $this->assignment = $assignment;
        $this->assignment = $assignment->load('didacticUnit');
    }

    /**
     * Devuelve la colección de datos (la lista de estudiantes).
     */
    public function collection()
    {
        // Verificar si hay registros antes de procesar
        if (!$this->assignment->registrations()->exists()) {
            return collect([
                [
                    'N°' => '',
                    'Código Estudiante' => 'No hay estudiantes matriculados',
                    'Nombre Completo' => '',
                    'Email' => '',
                    'Estado Matrícula' => '',
                ]
            ]);
        }

        // Cargar las matrículas con las relaciones necesarias
        $registrations = $this->assignment->registrations()
            // [CORRECCIÓN] Esta es la ruta de relación correcta
            ->with(['enrollment.student.user']) 
            ->where('status', 'enrolled')
            ->get();

        // Ordenar por nombre del estudiante
        $sortedRegistrations = $registrations->sortBy(function ($registration) {
            // [CORRECCIÓN] Usar la ruta completa
            return $registration->enrollment?->student?->user?->name ?? '';
        });

        // Mapear los datos para el Excel
        return $sortedRegistrations->values()->map(function ($registration, $index) {
            
            // [CORRECCIÓN] Obtener los modelos anidados de forma segura
            $student = $registration->enrollment?->student;
            $user = $student?->user;
            
            return [
                'N°' => $index + 1,
                'Código Estudiante' => $student?->code ?? 'N/A',
                'Nombre Completo' => $user?->name ?? 'N/A',
                'Email' => $user?->email ?? 'N/A',
                'Estado Matrícula' => $this->getStatusLabel($registration->status),
            ];
        });
    }

    /**
     * Convierte el estado a un label legible
     */
    private function getStatusLabel($status): string
    {
        $labels = [
            'enrolled' => 'Matriculado',
            'withdrawn' => 'Retirado',
            'transferred' => 'Trasladado',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Define los encabezados de las columnas.
     */
    public function headings(): array
    {
        return [
            'N°',
            'Código Estudiante',
            'Nombre Completo',
            'Email',
            'Estado Matrícula',
        ];
    }

    /**
     * Aplica estilos a la hoja de cálculo.
     */
    public function styles(Worksheet $sheet)
    {
        // Estilos (tu código de estilos estaba bien)
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']] // Azul/Indigo
        ]);
        
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 1) {
                $sheet->getStyle("A1:E{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]);
        }
    }

    /**
     * Define el nombre de la pestaña en el Excel.
     */
    public function title(): string
    {
        $code = $this->assignment->didacticUnit->code ?? 'UD';
        $section = $this->assignment->section ?? 'X';
        return "{$code} Sec {$section}";
    }
}
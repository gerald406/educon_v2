<?php

namespace App\Exports;

use App\Models\Applicant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ApplicantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    /**
     * Recibe los filtros desde el componente Livewire.
     * $filters = ['career_id' => ..., 'modality_id' => ..., 'shift_id' => ...]
     */
    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Applicant::query()
            ->with([
                'user',
                'admissionOffering.career',
                'admissionOffering.shift',
                'admissionModality',
                'financialEntity'
            ])
            ->where('application_status', '!=', 'cancelled'); // Excluir cancelados si aplica

        // --- Filtros Dinámicos ---

        // Filtro por Programa (Reporte A y B)
        if (!empty($this->filters['career_id'])) {
            $query->whereHas('admissionOffering', function ($q) {
                $q->where('career_id', $this->filters['career_id']);
            });
        }

        // Filtro por Modalidad (Reporte B)
        if (!empty($this->filters['modality_id'])) {
            $query->where('admission_modality_id', $this->filters['modality_id']);
        }

        // Filtro por Turno (Reporte B)
        if (!empty($this->filters['shift_id'])) {
            $query->whereHas('admissionOffering', function ($q) {
                $q->where('shift_id', $this->filters['shift_id']);
            });
        }

        return $query->get();
    }

    public function map($applicant): array
    {
        return [
            $applicant->user->document_number,
            $applicant->user->lastname . ' ' . $applicant->user->name,
            $applicant->admissionOffering->career->name ?? 'Sin Asignar',
            $applicant->admissionModality->name ?? 'Sin Asignar',
            $applicant->admissionOffering->shift->name ?? 'Sin Asignar',
            $applicant->financialEntity->name ?? 'Sin Asignar',
            $applicant->payment_operation_code,
            $applicant->created_at->format('d/m/Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'DNI',
            'Apellidos y Nombres',
            'Programa de Estudio',
            'Modalidad',
            'Turno',
            'Entidad Financiera',
            'Código Operación',
            'Fecha Registro',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}

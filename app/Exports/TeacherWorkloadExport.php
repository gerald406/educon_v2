<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeacherWorkloadExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;

    /**
     * Pasamos los datos (la colección) desde nuestro componente.
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * Devuelve la colección de datos.
     */
    public function collection()
    {
        // Mapeamos los datos para que solo incluyan lo que queremos en el Excel
        return $this->data->map(function ($item) {
            return [
                'name' => $item['name'],
                'code' => $item['code'],
                'hours' => $item['hours'],
                'status_text' => $item['status_text'],
            ];
        });
    }

    /**
     * Define los encabezados de las columnas.
     */
    public function headings(): array
    {
        return [
            'Docente',
            'Código',
            'Horas Asignadas',
            'Estado',
        ];
    }

    /**
     * Aplica estilos a la hoja de cálculo (ej. negrilla al encabezado).
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la primera fila (encabezados)
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
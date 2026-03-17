<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Syllabus;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SyllabusPdfController extends Controller
{
    public function download(Syllabus $syllabus)
    {
        // 1. Seguridad: Verificar que el sílabo pertenezca al docente logueado
        // Accedemos al teacher_id a través de la relación teacherAssignment
        if ($syllabus->teacherAssignment->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'No tienes permiso para ver este sílabo.');
        }

        // 2. Cargar todas las relaciones necesarias para que el PDF no falle
        $syllabus->load([
            'teacherAssignment.didacticUnit.module.studyPlan.career',
            'teacherAssignment.academicPeriod',
            'teacherAssignment.teacher.user',
            'teacherAssignment.shift',
            'indicators.units' // Cargar indicadores y sus sesiones
        ]);

        // 3. Generar el PDF usando la vista
        // Asegúrate de tener instalado dompdf: composer require barryvdh/laravel-dompdf
        $pdf = Pdf::loadView('reports.syllabus-pdf', compact('syllabus'));

        // Configuración opcional del papel
        $pdf->setPaper('A4', 'portrait');

        // 4. Mostrar en el navegador (stream)
        return $pdf->stream('Silabo-' . $syllabus->teacherAssignment->didacticUnit->name . '.pdf');
    }
}

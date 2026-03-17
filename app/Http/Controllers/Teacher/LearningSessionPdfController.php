<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Syllabus;
use App\Models\SyllabusUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LearningSessionPdfController extends Controller
{
    public function download(Syllabus $syllabus, SyllabusUnit $unit)
    {
        // Seguridad: verificar que pertenece al docente autenticado
        $teacher = Auth::user()->teacher;

        if (!$teacher || $syllabus->teacherAssignment->teacher_id !== $teacher->id) {
            abort(403, 'No tienes permiso para ver esta sesión.');
        }

        // Cargar todas las relaciones necesarias para el PDF
        $syllabus->load([
            'teacherAssignment.didacticUnit.module.studyPlan.career',
            'teacherAssignment.academicPeriod',
            'teacherAssignment.teacher.user',
            'teacherAssignment.shift',
        ]);

        $unit->load(['indicator', 'learningSession']);

        $session = $unit->learningSession;

        if (!$session) {
            abort(404, 'Esta sesión aún no ha sido generada.');
        }

        $pdf = Pdf::loadView('reports.learning-session-pdf', compact(
            'syllabus',
            'unit',
            'session'
        ));

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Sesion-' . $unit->session_number . '-' .
            Str::slug($syllabus->teacherAssignment->didacticUnit->name) . '.pdf';

        return $pdf->stream($filename);
    }
}

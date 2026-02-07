<?php

namespace App\Http\Controllers;

use App\Models\ExamClassroom;
use App\Models\ExamClassroomAssignment;
use App\Models\Institution; // [IMPORTANTE] Importar modelo
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage; // [IMPORTANTE] Para manejar archivos

class ExamReportController extends Controller
{
    public function doorList(ExamClassroom $classroom)
    {
        // 1. Obtener Datos de la Institución y Logo
        $institution = Institution::first();
        $logoData = null;

        if ($institution && $institution->logo_url && Storage::disk('public')->exists($institution->logo_url)) {
            $path = Storage::disk('public')->path($institution->logo_url);
            $mime = mime_content_type($path) ?: 'image/png';
            $logoData = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        // 2. Obtener Asignaciones
        $assignments = ExamClassroomAssignment::with(['applicant.user', 'applicant.admissionOffering.career'])
            ->where('exam_classroom_id', $classroom->id)
            ->join('applicants', 'exam_classroom_assignments.applicant_id', '=', 'applicants.id')
            ->join('users', 'applicants.user_id', '=', 'users.id')
            ->orderBy('users.lastname')
            ->orderBy('users.name')
            ->select('exam_classroom_assignments.*')
            ->get();

        // 3. Generar PDF
        $pdf = Pdf::loadView('reports.exam.door-list', [
            'classroom' => $classroom,
            'assignments' => $assignments,
            'institution' => $institution,
            'logoData' => $logoData
        ]);

        return $pdf->stream('Lista_Aula_' . $classroom->room_number . '.pdf');
    }
}

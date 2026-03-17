<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Institution;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class StudentReportController extends Controller
{
    public function downloadEnrollmentForm(Student $student)
    {
        // 1. Obtener el Periodo Académico Activo
        $activePeriod = AcademicPeriod::where('status', 'active')->first();

        if (!$activePeriod) {
            return response()->json(['error' => 'No hay un periodo académico activo.'], 404);
        }

        // 2. Buscar la matrícula del estudiante en este periodo
        // Usamos 'latest' para asegurarnos de traer la más reciente si hubiera duplicados (que no debería)
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('academic_period_id', $activePeriod->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'El estudiante no tiene matrícula activa en el periodo actual.'], 404);
        }

        // 3. Obtener los Cursos Inscritos (Registrations)
        $courses = $enrollment->registrations()
            ->where('status', 'enrolled')
            ->with([
                'teacherAssignment.didacticUnit',
                'teacherAssignment.teacher.user',
                'teacherAssignment.shift'
            ])
            ->get()
            ->pluck('teacherAssignment');

        // 4. Datos de la Institución (Logo y Nombre)
        $institution = Institution::first();
        $logoData = null;

        // Convertir logo a Base64 para que DOMPDF no falle
        if ($institution?->logo_url && Storage::disk('public')->exists($institution->logo_url)) {
            $path = Storage::disk('public')->path($institution->logo_url);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // 5. Generar PDF
        $pdf = Pdf::loadView('reports.enrollment-form-pdf', [
            'institution' => $institution,
            'logoData' => $logoData,
            'activePeriod' => $activePeriod,
            'student' => $student,
            'enrollment' => $enrollment,
            'courses' => $courses,
        ]);

        return $pdf->stream('Ficha_Matricula_' . $student->code . '.pdf');
    }
}

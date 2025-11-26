<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Institution;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class StudentReportController extends Controller
{
    /**
     * Genera la Ficha de Matrícula del periodo activo para un estudiante dado.
     */
    public function downloadEnrollmentForm(Student $student)
    {
        // 1. Obtener Periodo Activo
        $activePeriod = AcademicPeriod::where('status', 'active')->first();

        if (!$activePeriod) {
            return back()->with('error', 'No hay un periodo académico activo.');
        }

        // 2. Buscar la Matrícula del Estudiante
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('academic_period_id', $activePeriod->id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'El estudiante no tiene matrícula activa en este periodo.');
        }

        // 3. Obtener los Cursos (TeacherAssignments)
        // Reutilizamos la misma lógica que usamos en el portal del estudiante
        $courses = $enrollment->registrations()
            ->where('status', 'enrolled')
            ->with([
                'teacherAssignment.didacticUnit',
                'teacherAssignment.teacher.user',
                'teacherAssignment.shift'
            ])
            ->get()
            ->pluck('teacherAssignment');

        // 4. Datos Institucionales
        $institution = Institution::first();

        // Lógica del Logo Base64
        $logoData = null;
        if ($institution?->logo_url && Storage::disk('public')->exists($institution->logo_url)) {
            $path = Storage::disk('public')->path($institution->logo_url);
            $mime = mime_content_type($path);
            $logoData = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        // 5. Generar PDF (Reutilizando la vista existente)
        $data = [
            'institution' => $institution,
            'logoData' => $logoData,
            'activePeriod' => $activePeriod,
            'student' => $student,
            'enrollment' => $enrollment,
            'courses' => $courses,
        ];

        $pdf = Pdf::loadView('reports.enrollment-form-pdf', $data);

        return $pdf->stream('ficha-matricula-' . $student->code . '.pdf');
    }
}

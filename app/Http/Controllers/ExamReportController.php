<?php

namespace App\Http\Controllers;

use App\Models\ExamClassroom;
use App\Models\ExamClassroomAssignment;
use App\Models\Institution;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class ExamReportController extends Controller
{
    /**
     * Lista de Puerta — método existente sin cambios
     */
    public function doorList(ExamClassroom $classroom)
    {
        $institution = Institution::first();
        $logoData    = null;

        if (
            $institution && $institution->logo_url &&
            Storage::disk('public')->exists($institution->logo_url)
        ) {
            $path     = Storage::disk('public')->path($institution->logo_url);
            $mime     = mime_content_type($path) ?: 'image/png';
            $logoData = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        $assignments = ExamClassroomAssignment::with([
            'applicant.user',
            'applicant.admissionOffering.career'
        ])
            ->where('exam_classroom_id', $classroom->id)
            ->join('applicants', 'exam_classroom_assignments.applicant_id', '=', 'applicants.id')
            ->join('users', 'applicants.user_id', '=', 'users.id')
            ->orderBy('users.lastname')
            ->orderBy('users.name')
            ->select('exam_classroom_assignments.*')
            ->get();

        $pdf = Pdf::loadView('reports.exam.door-list', [
            'classroom'   => $classroom,
            'assignments' => $assignments,
            'institution' => $institution,
            'logoData'    => $logoData,
        ]);

        return $pdf->stream('Lista_Aula_' . $classroom->room_number . '.pdf');
    }

    /**
     * Hojas de Respuesta — UNA página por postulante
     */
    public function answerSheets(ExamClassroom $classroom)
    {
        $institution = Institution::first();

        $assignments = ExamClassroomAssignment::with([
            'applicant.user',
            'applicant.admissionOffering.career',
            'applicant.admissionOffering.shift',
        ])
            ->where('exam_classroom_id', $classroom->id)
            ->join('applicants', 'exam_classroom_assignments.applicant_id', '=', 'applicants.id')
            ->join('users', 'applicants.user_id', '=', 'users.id')
            ->orderBy('users.lastname')
            ->orderBy('users.name')
            ->select('exam_classroom_assignments.*')
            ->get();

        if ($assignments->isEmpty()) {
            abort(404, 'No hay postulantes asignados a esta aula.');
        }

        // Encabezado institucional (mismo mecanismo que syllabus-pdf)
        $headerImagePath = public_path('images/encabezado-institucional.jpg');
        $headerImageData = null;
        if (file_exists($headerImagePath)) {
            $mime            = mime_content_type($headerImagePath) ?: 'image/jpeg';
            $headerImageData = 'data:' . $mime . ';base64,' .
                base64_encode(file_get_contents($headerImagePath));
        }

        // QR por postulante se genera en la vista con BaconQrCode
        // Prerenderizar QR de cada postulante para pasar como base64
        $qrCodes = [];
        foreach ($assignments as $assignment) {
            $dni      = $assignment->applicant->user->document_number ?? '00000000';
            $name     = trim(
                ($assignment->applicant->user->lastname ?? '') . ' ' .
                    ($assignment->applicant->user->name ?? '')
            );
            $code     = $assignment->applicant->code ?? $dni;
            $content  = "{$dni}";

            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(120, 1),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer   = new \BaconQrCode\Writer($renderer);
            $svg      = $writer->writeString($content);
            $qrCodes[$assignment->applicant_id] = 'data:image/svg+xml;base64,' . base64_encode($svg);
        }

        $pdf = Pdf::loadView('reports.exam.answer-sheets', [
            'classroom'       => $classroom,
            'assignments'     => $assignments,
            'institution'     => $institution,
            'headerImageData' => $headerImageData,
            'qrCodes'         => $qrCodes,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Hojas_Respuesta_Aula_' . $classroom->room_number . '.pdf');
    }
}

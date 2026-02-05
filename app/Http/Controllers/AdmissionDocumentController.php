<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Institution;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class AdmissionDocumentController extends Controller
{
    public function constancia(Applicant $applicant)
    {
        return $this->generatePdf('reports.admission.constancia', $applicant, 'Constancia');
    }

    public function ficha(Applicant $applicant)
    {
        return $this->generatePdf('reports.admission.ficha', $applicant, 'Ficha');
    }

    private function generatePdf($view, $applicant, $filenamePrefix)
    {
        $institution = Institution::first();

        // 1. Logo (Base64)
        $logoData = null;
        if ($institution->logo_url && Storage::disk('public')->exists($institution->logo_url)) {
            $path = Storage::disk('public')->path($institution->logo_url);
            $mime = mime_content_type($path) ?: 'image/png';
            $logoData = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        // 2. Foto Postulante (Base64)
        $applicantPhoto = null;
        if ($applicant->photo_url && Storage::disk('public')->exists($applicant->photo_url)) {
            $photoPath = Storage::disk('public')->path($applicant->photo_url);
            $photoMime = mime_content_type($photoPath) ?: 'image/jpeg';
            $applicantPhoto = 'data:' . $photoMime . ';base64,' . base64_encode(file_get_contents($photoPath));
        }

        // 3. Generar QR (SVG Base64)
        // Contenido: DNI | Nombre Completo | Código Postulante
        $qrContent = "{$applicant->user->document_number}|{$applicant->user->lastname} {$applicant->user->name}|{$applicant->code}";

        $renderer = new ImageRenderer(
            new RendererStyle(200, 1),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvgString = $writer->writeString($qrContent);
        $qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrSvgString);

        // 4. Datos de Auditoría (Usuario e IP)
        $user = auth()->user();
        $printedBy = $user ? ($user->lastname . ' ' . $user->name) : 'SISTEMA WEB';
        $ipAddress = request()->ip();

        // 5. Renderizar PDF
        $pdf = Pdf::loadView($view, [
            'applicant' => $applicant,
            'institution' => $institution,
            'logoData' => $logoData,
            'applicantPhoto' => $applicantPhoto,
            'qrCode' => $qrCodeDataUri,
            'printedBy' => strtoupper($printedBy), // Nombre en mayúsculas
            'ipAddress' => $ipAddress
        ]);

        return $pdf->stream("{$filenamePrefix}-{$applicant->code}.pdf");
    }
}

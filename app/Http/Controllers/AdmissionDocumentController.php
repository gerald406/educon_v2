<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Institution;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

// Importaciones para generar el QR nativamente
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class AdmissionDocumentController extends Controller
{
    public function constancia(Applicant $applicant)
    {
        return $this->generatePdf('reports.admission.constancia', $applicant, 'constancia');
    }

    public function ficha(Applicant $applicant)
    {
        return $this->generatePdf('reports.admission.ficha', $applicant, 'ficha');
    }

    private function generatePdf($view, $applicant, $filenamePrefix)
    {
        $institution = Institution::first();

        // 1. Procesar Logo de la Institución (Base64)
        $logoData = null;
        if ($institution->logo_url && Storage::disk('public')->exists($institution->logo_url)) {
            $path = Storage::disk('public')->path($institution->logo_url);
            // Obtenemos el mime type real o fallamos a png
            $mime = mime_content_type($path) ?: 'image/png';
            $logoData = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        // 2. Procesar Foto del Postulante (Base64)
        $applicantPhoto = null;
        if ($applicant->photo_url && Storage::disk('public')->exists($applicant->photo_url)) {
            $photoPath = Storage::disk('public')->path($applicant->photo_url);
            $photoMime = mime_content_type($photoPath) ?: 'image/jpeg';
            $applicantPhoto = 'data:' . $photoMime . ';base64,' . base64_encode(file_get_contents($photoPath));
        }

        // 3. Generar QR (Nativo con BaconQrCode v3)
        // Contenido: DNI | Nombre | Código
        $qrContent = "{$applicant->user->document_number} | {$applicant->user->name} | {$applicant->code}";

        // Configurar el renderizador para crear un SVG (vectorial, mejor calidad en PDF)
        $renderer = new ImageRenderer(
            new RendererStyle(200, 1), // Tamaño 200px, Margen 1
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrSvgString = $writer->writeString($qrContent);

        // Convertir el string SVG a Base64 para incrustarlo
        $qrCode = base64_encode($qrSvgString);

        // Nota: Para SVG, el prefijo data URI es diferente
        $qrCodeDataUri = 'data:image/svg+xml;base64,' . $qrCode;

        // 4. Renderizar PDF
        $pdf = Pdf::loadView($view, [
            'applicant' => $applicant,
            'institution' => $institution,
            'logoData' => $logoData,
            'applicantPhoto' => $applicantPhoto,
            'qrCode' => $qrCodeDataUri // Pasamos la URI completa
        ]);

        return $pdf->stream("{$filenamePrefix}-{$applicant->code}.pdf");
    }
}

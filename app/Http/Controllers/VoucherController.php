<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function download(Voucher $voucher)
    {
        // Cargar relaciones necesarias
        $voucher->load(['items', 'client', 'issuer', 'cashSession']);

        $institution = Institution::first();

        // Generar PDF
        $pdf = Pdf::loadView('treasury.voucher-pdf', [
            'voucher' => $voucher,
            'institution' => $institution,
        ])->setPaper('a5', 'landscape'); // Tamaño A5 Horizontal

        // Mostrar en el navegador
        return $pdf->stream('voucher-' . $voucher->series . '-' . $voucher->number . '.pdf');
    }
}

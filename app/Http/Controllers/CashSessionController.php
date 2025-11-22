<?php

namespace App\Http\Controllers;

use App\Models\CashSession;
use App\Models\Institution;
use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashSessionController extends Controller
{
    /**
     * Genera el Reporte X (Parcial) o Z (Final)
     */
    public function download(CashSession $session, $type = 'x')
    {
        $institution = Institution::first();

        // 1. Obtener resumen por método de pago
        $summary = Voucher::where('cash_session_id', $session->id)
            ->where('status', 'issued')
            ->select('payment_method', DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        // 2. Calcular totales globales
        $totalIncome = $summary->sum('total');
        $totalVouchers = $summary->sum('count');

        // 3. Si es Reporte Z y la caja sigue abierta, calculamos al vuelo
        if ($type == 'z' && $session->status == 'open') {
            // (Esto no debería pasar por la UI, pero por seguridad)
            $session->calculated_cash = $session->opening_balance +
                $summary->where('payment_method', 'Efectivo')->sum('total');
        }

        $data = [
            'session' => $session,
            'institution' => $institution,
            'summary' => $summary,
            'totalIncome' => $totalIncome,
            'totalVouchers' => $totalVouchers,
            'reportType' => strtoupper($type), // 'X' o 'Z'
            'date' => now(),
        ];

        // Usamos formato ticket (80mm) o A4. Usaremos A4 por simplicidad y detalle.
        $pdf = Pdf::loadView('treasury.cash-report-pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('reporte-' . $type . '-' . $session->id . '.pdf');
    }
}

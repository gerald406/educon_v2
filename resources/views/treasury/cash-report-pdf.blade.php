<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Caja {{ $reportType }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; }
        .info-box { margin-bottom: 15px; }
        .info-box p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px solid #ccc; padding: 5px; text-align: left; }
        th { background-color: #f5f5f5; }
        .totals { margin-top: 15px; text-align: right; font-size: 14px; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
        .closing-info { border: 1px solid #000; padding: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $institution->name }}</h1>
        <h2>REPORTE DE CAJA "{{ $reportType }}"</h2>
        <p>Sesión #{{ $session->id }}</p>
    </div>

    <div class="info-box">
        <p><strong>Cajero:</strong> {{ $session->user->name }}</p>
        <p><strong>Apertura:</strong> {{ $session->opening_time->format('d/m/Y h:i A') }}</p>
        <p><strong>Fecha Reporte:</strong> {{ $date->format('d/m/Y h:i A') }}</p>
        @if($reportType == 'Z' && $session->closing_time)
            <p><strong>Cierre:</strong> {{ $session->closing_time->format('d/m/Y h:i A') }}</p>
        @endif
    </div>

    <h3>Resumen de Ingresos</h3>
    <table>
        <thead>
            <tr>
                <th>Método de Pago</th>
                <th style="text-align: center;">Transacciones</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $row)
                <tr>
                    <td>{{ $row->payment_method }}</td>
                    <td style="text-align: center;">{{ $row->count }}</td>
                    <td style="text-align: right;">S/ {{ number_format($row->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>TOTAL INGRESOS: S/ {{ number_format($totalIncome, 2) }}</p>
    </div>

    <div class="closing-info">
        <h3>Balance de Caja</h3>
        <p>Monto Inicial (Fondo): S/ {{ number_format($session->opening_balance, 2) }}</p>
        <p>Total Ventas (Efectivo): S/ {{ number_format($summary->where('payment_method', 'Efectivo')->sum('total'), 2) }}</p>
        <p><strong>Saldo Teórico (Efectivo): S/ {{ number_format($session->opening_balance + $summary->where('payment_method', 'Efectivo')->sum('total'), 2) }}</strong></p>
        
        @if($reportType == 'Z' && $session->status == 'closed')
            <hr>
            <p>Monto Real (Contado): S/ {{ number_format($session->closing_balance_cash, 2) }}</p>
            <p style="color: {{ $session->difference < 0 ? 'red' : 'green' }}">
                <strong>Diferencia: S/ {{ number_format($session->difference, 2) }}</strong>
                @if($session->difference < 0) (FALTANTE) @elseif($session->difference > 0) (SOBRANTE) @else (OK) @endif
            </p>
        @endif
    </div>

    <div class="footer">
        <p>Generado por el Sistema de Gestión Académica EDUCON</p>
    </div>
</body>
</html>
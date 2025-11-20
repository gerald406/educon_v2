<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        @page { margin: 1cm; size: A5 landscape; } /* Tamaño A5 horizontal */
        body { font-family: 'Courier', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; }
        .details { margin-bottom: 15px; }
        .details p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { text-align: left; padding: 5px; border-bottom: 1px solid #ccc; }
        .totals { text-align: right; margin-top: 10px; }
        .footer { text-align: center; font-size: 10px; margin-top: 30px; border-top: 1px dashed #000; padding-top: 10px; }
        .watermark {
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(255, 0, 0, 0.3);
            font-weight: bold;
            border: 5px solid rgba(255, 0, 0, 0.3);
            padding: 10px 20px;
            z-index: -1000;
            pointer-events: none;
        }
    </style>
</head>
<body>
    @if($voucher->status == 'annulled')
        <div class="watermark">ANULADO</div>
    @endif
    <div class="header">
        <h1>{{ $institution->name }}</h1>
        <p>RUC: {{ $institution->tax_id }}</p>
        <p>{{ $institution->address }}</p>
        <br>
        <h2>{{ strtoupper($voucher->voucher_type) }} DE VENTA ELECTRÓNICA</h2>
        <p>Nro: {{ $voucher->series }}-{{ str_pad($voucher->number, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="details">
        <p><strong>Fecha de Emisión:</strong> {{ $voucher->issued_at->format('d/m/Y h:i A') }}</p>
        <p><strong>Cliente:</strong> {{ $voucher->client->name }}</p>
        <p><strong>Documento:</strong> {{ $voucher->client->document_type }} - {{ $voucher->client->document_number }}</p>
        <p><strong>Cajero:</strong> {{ $voucher->issuer->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cant.</th>
                <th>Descripción</th>
                <th>P. Unit.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($voucher->items as $item)
                <tr>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>TOTAL A PAGAR: S/ {{ number_format($voucher->total_amount, 2) }}</strong></p>
        <p>Método de Pago: {{ $voucher->payment_method }}</p>
    </div>

    <div class="footer">
        <p>Gracias por su pago.</p>
        <p>Conserve este documento para cualquier reclamo.</p>
    </div>
</body>
</html>
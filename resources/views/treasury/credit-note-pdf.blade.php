<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nota de Crédito</title>
    <style>
        @page { margin: 1cm; size: A5 landscape; }
        body { font-family: 'Courier', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .details { margin-bottom: 15px; }
        .title { font-size: 16px; font-weight: bold; text-align: center; margin: 10px 0; }
        .box { border: 1px solid #000; padding: 10px; margin-top: 10px; }
        .totals { text-align: right; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $institution->name }}</h1>
        <p>RUC: {{ $institution->tax_id }}</p>
        <p>{{ $institution->address }}</p>
    </div>

    <div class="title">NOTA DE CRÉDITO ELECTRÓNICA</div>
    <p style="text-align: center;">Ref: {{ strtoupper($voucher->voucher_type) }} {{ $voucher->series }}-{{ str_pad($voucher->number, 6, '0', STR_PAD_LEFT) }}</p>

    <div class="details">
        <p><strong>Fecha de Emisión:</strong> {{ $creditNote->created_at->format('d/m/Y h:i A') }}</p>
        <p><strong>Cliente:</strong> {{ $voucher->client->name }}</p>
        <p><strong>Documento:</strong> {{ $voucher->client->document_type }} - {{ $voucher->client->document_number }}</p>
        <p><strong>Emitido por:</strong> {{ $creditNote->user->name }}</p>
    </div>

    <div class="box">
        <p><strong>Motivo de la Anulación:</strong></p>
        <p>{{ $creditNote->reason }}</p>
    </div>

    <div class="totals">
        <p><strong>MONTO ANULADO: S/ {{ number_format($creditNote->amount, 2) }}</strong></p>
    </div>
    
    <div style="margin-top: 30px; text-align: center; font-size: 10px;">
        <p>Este documento certifica la anulación del comprobante de referencia.</p>
    </div>
</body>
</html>
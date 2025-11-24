<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Constancia de Inscripción</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        
        .header { text-align: center; margin-bottom: 20px; height: 80px; }
        .header h1 { font-size: 14px; font-weight: bold; margin: 0; }
        .header h2 { font-size: 12px; margin: 2px 0; font-weight: normal; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin: 15px 0; text-decoration: underline; }
        
        /* Imágenes Absolutas */
        .logo { position: absolute; top: 10px; left: 20px; width: 60px; }
        .qr-code { position: absolute; top: 10px; right: 20px; width: 70px; height: 70px; }
        
        /* Foto Pasaporte */
        .photo-passport { 
            position: absolute; 
            top: 90px; 
            right: 20px; 
            width: 2.5cm; 
            height: 3.2cm; 
            object-fit: cover; 
            border: 1px solid #000;
        }

        .section-title { font-weight: bold; background-color: #f0f0f0; padding: 5px; margin-top: 10px; border: 1px solid #ccc; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        td { padding: 4px; vertical-align: top; }
        .label { font-weight: bold; width: 180px; }
        
        .footer { margin-top: 30px; text-align: center; font-size: 10px; }
        .signatures { width: 100%; margin-top: 60px; }
        .signature-box { width: 45%; float: left; text-align: center; }
        .line { border-top: 1px solid #000; width: 80%; margin: 0 auto; }
    </style>
</head>
<body>
    @if($logoData) <img src="{{ $logoData }}" class="logo"> @endif
    {{-- @if($qrCode) <img src="data:image/png;base64,{{ $qrCode }}" class="qr-code"> @endif --}}
    @if(isset($qrCode)) <img src="{{ $qrCode }}" class="qr-code"> @endif
    @if($applicantPhoto) <img src="{{ $applicantPhoto }}" class="photo-passport"> @endif

    <div class="header">
        <br>
        <h1>{{ $institution->name }}</h1>
        <h2>Instituto de Excelencia</h2>
    </div>

    <div class="title">CONSTANCIA DE INSCRIPCIÓN</div>

    <div style="width: 75%;">
        <div class="section-title">DATOS PERSONALES</div>
        <table>
            <tr><td class="label">APELLIDOS:</td><td>{{ $applicant->user->lastname }}</td></tr>
            <tr><td class="label">NOMBRES:</td><td>{{ $applicant->user->name }}</td></tr>
            <tr><td class="label">DNI:</td><td>{{ $applicant->user->document_number }}</td></tr>
        </table>

        <div class="section-title">DATOS ACADÉMICOS</div>
        <table>
            <tr><td class="label">COLEGIO PROCEDENCIA:</td><td>{{ $applicant->originSchool->name ?? '-' }}</td></tr>
            <tr><td class="label">AÑO DE EGRESO:</td><td>{{ $applicant->school_graduation_year }}</td></tr>
        </table>

        <div class="section-title">DATOS DEL POSTULANTE</div>
        <table>
            <tr><td class="label">PROGRAMA:</td><td>{{ $applicant->admissionOffering->career->name ?? '-' }}</td></tr>
            <tr><td class="label">MODALIDAD:</td><td>{{ $applicant->admissionModality->name ?? '-' }}</td></tr>
            <tr><td class="label">TURNO:</td><td>{{ $applicant->admissionOffering->shift->name ?? '-' }}</td></tr>
            <tr><td class="label">Entidad:</td><td>{{ $applicant->financialEntity->name ?? '-' }}</td></tr>
            <tr><td class="label">CÓDIGO OP.:</td><td>{{ $applicant->payment_operation_code }}</td></tr>
        </table>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <div class="line"></div>
            <p>Firma del Postulante</p>
        </div>
        <div class="signature-box" style="float: right;">
            <div class="line"></div>
            <p>Comisión de Admisión</p>
        </div>
    </div>
    
    <div style="clear:both;"></div>

    <div class="footer">
        <p>Fecha: {{ now()->format('d/m/Y H:i:s') }} | Código: {{ $applicant->code }}</p>
    </div>
</body>
</html>
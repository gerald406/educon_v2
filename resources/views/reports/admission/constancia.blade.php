<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Constancia de Inscripción</title>
    <style>
        @page { margin: 1cm 1.5cm; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        
        /* ESTILO DE MARCA DE AGUA */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            width: 100%;
            text-align: center;
            font-size: 80px;
            font-weight: bold;
            color: #e0e0e0; /* Gris muy claro */
            opacity: 0.4;   /* Transparencia adicional */
            z-index: -1000; /* Detrás de todo */
            pointer-events: none;
            white-space: nowrap;
        }

        /* Encabezado */
        .header { text-align: center; margin-bottom: 20px; height: 80px; position: relative; }
        .header h1 { font-size: 16px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .header h2 { font-size: 12px; margin: 2px 0; font-weight: normal; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin: 20px 0; text-decoration: underline; text-transform: uppercase; }
        
        /* Posicionamiento Absoluto de Imágenes */
        .logo { position: absolute; top: 0; left: 0; width: 60px; height: auto; }
        .qr-code { position: absolute; top: 0; right: 0; width: 75px; height: 75px; }
        
        /* Foto Pasaporte */
        .photo-passport { 
            position: absolute; 
            top: 90px; 
            right: 0px; 
            width: 3cm; 
            height: 4cm; 
            object-fit: cover; 
            border: 1px solid #999;
            padding: 2px;
        }

        /* Secciones */
        .section-title { 
            font-weight: bold; 
            background-color: #e9e9e9; 
            padding: 6px; 
            margin-top: 15px; 
            border: 1px solid #ccc; 
            font-size: 12px;
        }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 160px; color: #444; }
        
        /* Firmas */
        .signatures { width: 100%; margin-top: 80px; }
        .signature-box { width: 40%; float: left; text-align: center; margin: 0 5%; }
        .line { border-top: 1px solid #000; margin-bottom: 5px; }

        /* Pie de Página Fijo */
        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            text-align: center; 
            font-size: 9px; 
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            background-color: #fff; /* Fondo blanco para tapar la marca de agua en el pie */
        }
        .footer strong { color: #000; font-size: 10px; display: block; margin-bottom: 2px; }
    </style>
</head>
<body>
    <div class="watermark">ADMISIÓN JAE 2026</div>

    @if($logoData) <img src="{{ $logoData }}" class="logo"> @endif
    @if(isset($qrCode)) <img src="{{ $qrCode }}" class="qr-code"> @endif
    
    <div class="header">
        <br>
        <h1>{{ $institution->name }}</h1>
        <h2>Instituto de Educación Superior Tecnológico Público</h2>
    </div>

    <div class="title">CONSTANCIA DE INSCRIPCIÓN</div>

    <div style="width: 75%; margin-right: 25%;">
        
        <div class="section-title">I. DATOS PERSONALES</div>
        <table>
            <tr><td class="label">APELLIDOS:</td><td>{{ $applicant->user->lastname }}</td></tr>
            <tr><td class="label">NOMBRES:</td><td>{{ $applicant->user->name }}</td></tr>
            <tr><td class="label">DNI:</td><td>{{ $applicant->user->document_number }}</td></tr>
        </table>

        <div class="section-title">II. DATOS ACADÉMICOS</div>
        <table>
            <tr><td class="label">COLEGIO PROCEDENCIA:</td><td>{{ $applicant->originSchool->name ?? '-' }}</td></tr>
            <tr><td class="label">AÑO DE EGRESO:</td><td>{{ $applicant->school_graduation_year }}</td></tr>
        </table>

        <div class="section-title">III. DATOS DE POSTULACIÓN</div>
        <table>
            <tr><td class="label">PROGRAMA ESTUDIOS:</td><td>{{ $applicant->admissionOffering->career->name ?? '-' }}</td></tr>
            <tr><td class="label">MODALIDAD:</td><td>{{ $applicant->admissionModality->name ?? '-' }}</td></tr>
            <tr><td class="label">TURNO:</td><td>{{ $applicant->admissionOffering->shift->name ?? '-' }}</td></tr>
            <tr><td class="label">ENTIDAD PAGO:</td><td>{{ $applicant->financialEntity->name ?? '-' }}</td></tr>
            <tr><td class="label">CÓDIGO OPERACIÓN:</td><td>{{ $applicant->payment_operation_code }}</td></tr>
        </table>
    </div>

    @if($applicantPhoto) <img src="{{ $applicantPhoto }}" class="photo-passport"> @endif

    <div class="signatures">
        <div class="signature-box">
            <div class="line"></div>
            <p>Firma del Postulante</p>
            <p style="font-size: 9px;">DNI: {{ $applicant->user->document_number }}</p>
        </div>
        <div class="signature-box">
            <div class="line"></div>
            <p>Comisión de Admisión</p>
            <p style="font-size: 9px;">{{ date('Y') }}</p>
        </div>
    </div>
    
    <div class="footer">
        <strong>COMISIÓN INSTITUCIONAL DE ADMISIÓN</strong>
        <p>
            Impreso por: {{ $printedBy }} &nbsp;|&nbsp; IP: {{ $ipAddress }} <br>
            Fecha de Impresión: {{ now()->format('d/m/Y H:i:s') }} &nbsp;|&nbsp; Código Interno: {{ $applicant->code }}
        </p>
    </div>
</body>
</html>
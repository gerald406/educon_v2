<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ficha de Inscripción</title>
    <style>
        @page { margin: 1cm 1.5cm; }
        body { font-family: Arial, sans-serif; font-size: 11px; }
        
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
            color: #e0e0e0;
            opacity: 0.4;
            z-index: -1000;
            pointer-events: none;
            white-space: nowrap;
        }

        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; height: 80px; position: relative; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h3 { margin: 5px 0; font-size: 13px; font-weight: normal; }
        
        .logo { position: absolute; top: 0; left: 0; width: 60px; }
        
        /* FOTO: Alineada arriba a la derecha del contenido */
        .photo-passport { 
            position: absolute; 
            top: 110px; 
            right: 0px; 
            width: 3.5cm; 
            height: 4.5cm; 
            object-fit: cover; 
            border: 1px solid #000; 
            padding: 2px;
            background: #fff;
        }

        /* QR: Debajo de la foto */
        .qr-code-ficha { 
            position: absolute; 
            top: 290px; 
            right: 15px; 
            width: 2.5cm; 
            height: 2.5cm; 
        }

        table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        td { padding: 6px; border: 1px solid #ccc; }
        .label { background-color: #f5f5f5; font-weight: bold; width: 25%; }
        .section-header { background: #ddd; font-weight:bold; text-align: center; padding: 5px; border: 1px solid #999; }

        .declaration { margin-top: 30px; text-align: justify; font-size: 10px; line-height: 1.4; border: 1px dashed #999; padding: 10px; background-color: rgba(255,255,255,0.8); }
        
        .signatures { margin-top: 80px; width: 100%; }
        .sig-box { width: 40%; float: left; text-align: center; margin: 0 5%; }
        .line { border-top: 1px solid #000; margin-bottom: 5px; }

        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            text-align: center; 
            font-size: 9px; 
            border-top: 1px solid #ccc; 
            padding-top: 5px;
            background-color: #fff;
        }
        .footer strong { display: block; font-size: 10px; margin-bottom: 2px; color: #000; }
    </style>
</head>
<body>
    <div class="watermark">ADMISIÓN JAE 2026</div>

    @if($logoData) <img src="{{ $logoData }}" class="logo"> @endif

    <div class="header">
        <br> <h2>{{ $institution->name }}</h2>
        <h3>FICHA DE INSCRIPCIÓN - PROCESO DE ADMISIÓN {{ date('Y') }}</h3>
    </div>

    <div style="width: 72%; margin-right: 28%;">
        <table> 
            <tr><td colspan="2" class="section-header">I. DATOS PERSONALES</td></tr>
            <tr><td class="label">DNI:</td><td>{{ $applicant->user->document_number }}</td></tr>
            <tr><td class="label">Apellidos y Nombres:</td><td>{{ $applicant->user->lastname }} {{ $applicant->user->name }}</td></tr>
            <tr><td class="label">Fecha Nacimiento:</td><td>{{ $applicant->birthday ? $applicant->birthday->format('d/m/Y') : '-' }}</td></tr>
            <tr><td class="label">Lugar Nacimiento:</td><td>{{ $applicant->birthLocation->full_name ?? '-' }}</td></tr>
            <tr><td class="label">Dirección:</td><td>{{ $applicant->address }}</td></tr>
            <tr><td class="label">Celular / Email:</td><td>{{ $applicant->phone }} / {{ $applicant->user->email }}</td></tr>
        </table>

        <table>
            <tr><td colspan="2" class="section-header">II. COELGIO DE PROCEDENCIA</td></tr>
            <tr><td class="label">Colegio Procedencia:</td><td>{{ $applicant->originSchool->name ?? '-' }}</td></tr>
            <tr><td class="label">Año Egreso:</td><td>{{ $applicant->school_graduation_year }}</td></tr>
        </table>
    </div>

    @if($applicantPhoto) 
        <img src="{{ $applicantPhoto }}" class="photo-passport"> 
    @else
        <div class="photo-passport" style="text-align: center; line-height: 4.5cm; color: #ccc;">FOTO</div>
    @endif
    
    @if(isset($qrCode)) 
        <img src="{{ $qrCode }}" class="qr-code-ficha"> 
    @endif
    
    <div style="clear:both;"></div>
    <table>
        <tr><td colspan="2" class="section-header">III. DATOS DE POSTULACIÓN</td></tr>
        <tr><td class="label">Programa Estudios:</td><td>{{ $applicant->admissionOffering->career->name ?? '-' }}</td></tr>
        <tr><td class="label">Turno:</td><td>{{ $applicant->admissionOffering->shift->name ?? '-' }}</td></tr>
        <tr><td class="label">Modalidad:</td><td>{{ $applicant->admissionModality->name ?? '-' }}</td></tr>
    </table>
    <table>
         <tr><td colspan="2" class="section-header">IV. DATOS DE PAGO</td></tr>
         <tr><td class="label">Entidad Financiera:</td><td>{{ $applicant->financialEntity->name ?? '-' }}</td></tr>
         <tr><td class="label">Código Operación:</td><td>{{ $applicant->payment_operation_code }}</td></tr>
    </table>

    <div class="declaration">
        <strong>DECLARACIÓN JURADA</strong><br>
        Yo, <strong>{{ $applicant->user->lastname }} {{ $applicant->user->name }}</strong>, identificado con DNI N° <strong>{{ $applicant->user->document_number }}</strong>, declaro bajo juramento que los datos consignados en la presente ficha son verdaderos y que conozco el Reglamento de Admisión vigente.
        Asimismo, me comprometo a regularizar mi expediente con la documentación física correspondiente en caso de alcanzar una vacante.
    </div>

    <div class="signatures">
        <div class="sig-box">
            <div class="line"></div>
            <p>Firma del Postulante</p>
            <p style="font-size: 9px;">DNI: {{ $applicant->user->document_number }}</p>
        </div>
        <div class="sig-box">
            <div class="line"></div>
            <p>Responsable de Admisión</p>
            <p style="font-size: 9px;">Sello y Firma</p>
        </div>
    </div>

    <div class="footer">
        <strong>COMISIÓN INSTITUCIONAL DE ADMISIÓN</strong>
        <p>
            Impreso por: {{ $printedBy }} &nbsp;|&nbsp; IP: {{ $ipAddress }} <br>
            Fecha: {{ now()->format('d/m/Y H:i:s') }} &nbsp;|&nbsp; ID Sistema: {{ $applicant->code }}
        </p>
    </div>
</body>
</html>
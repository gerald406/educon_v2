<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ficha de Inscripción</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        
        /* Encabezado */
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; min-height: 80px; }
        .header h2 { margin: 0; font-size: 16px; }
        .header h3 { margin: 5px 0; font-size: 14px; font-weight: normal; }
        
        /* Posicionamiento de Imágenes Absoluto */
        /* .logo { position: absolute; top: 0px; left: 0px; width: 60px; height: auto; } */
        /* Imágenes Absolutas */
        .logo { position: absolute; top: 10px; left: 20px; width: 60px; }
        .qr-code { position: absolute; top: 10px; right: 20px; width: 70px; height: 70px; }
        
        /* [NUEVO] Estilo Foto Tamaño Carnet/Pasaporte */
        .photo-passport { 
            position: absolute; 
            top: 0px; 
            right: 0px; 
            width: 3.5cm;  /* Ancho estándar */
            height: 4.5cm; /* Alto estándar */
            object-fit: cover; 
            border: 1px solid #000; 
            padding: 2px;
            background: #fff;
        }

        /* Tablas */
        table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        td { padding: 5px; border: 1px solid #ccc; }
        .label { background-color: #eee; font-weight: bold; width: 25%; }
        .section-header { background: #ccc; font-weight:bold; text-align: center; }

        /* Firmas */
        .footer { margin-top: 30px; text-align: center; font-size: 10px; }
        .declaration { margin-top: 20px; text-align: justify; font-size: 10px; }
        .signatures { margin-top: 80px; width: 100%; }
        .sig-box { width: 40%; float: left; text-align: center; margin: 0 5%; }
        .line { border-top: 1px solid #000; margin-bottom: 5px; }
    </style>
</head>
<body>
    @if($logoData) <img src="{{ $logoData }}" class="logo"> @endif
    @if(isset($qrCode)) <img src="{{ $qrCode }}" class="qr-code"> @endif
    @if($applicantPhoto) 
        <img src="{{ $applicantPhoto }}" class="photo-passport"> 
    @else
        <div class="photo-passport" style="text-align: center; line-height: 4.5cm; color: #ccc;">FOTO</div>
    @endif

    <div class="header">
        <br> <h2>{{ $institution->name }}</h2>
        <h3>FICHA DE INSCRIPCIÓN - PROCESO DE ADMISIÓN {{ date('Y') }}</h3>
    </div>

    <table style="margin-top: 20px;"> 
        <tr><td colspan="2" class="section-header">I. DATOS PERSONALES</td></tr>
        <tr><td class="label">DNI:</td><td>{{ $applicant->user->document_number }}</td></tr>
        <tr><td class="label">Apellidos y Nombres:</td><td>{{ $applicant->user->lastname }} {{ $applicant->user->name }}</td></tr>
        <tr><td class="label">Fecha Nacimiento:</td><td>{{ $applicant->birthday ? $applicant->birthday->format('d/m/Y') : '-' }}</td></tr>
        <tr><td class="label">Lugar Nacimiento:</td><td>{{ $applicant->birthLocation->full_name ?? '-' }}</td></tr>
        <tr><td class="label">Dirección:</td><td>{{ $applicant->address }}</td></tr>
        <tr><td class="label">Celular / Email:</td><td>{{ $applicant->phone }} / {{ $applicant->user->email }}</td></tr>
    </table>

    <table>
        <tr><td colspan="2" class="section-header">II. DATOS ACADÉMICOS</td></tr>
        <tr><td class="label">Colegio Procedencia:</td><td>{{ $applicant->originSchool->name ?? '-' }}</td></tr>
        <tr><td class="label">Año Egreso:</td><td>{{ $applicant->school_graduation_year }}</td></tr>
        <tr><td class="label">Programa Estudios:</td><td>{{ $applicant->admissionOffering->career->name ?? '-' }}</td></tr>
        <tr><td class="label">Turno:</td><td>{{ $applicant->admissionOffering->shift->name ?? '-' }}</td></tr>
        <tr><td class="label">Modalidad:</td><td>{{ $applicant->admissionModality->name ?? '-' }}</td></tr>
    </table>
    
    <table>
         <tr><td colspan="2" class="section-header">III. DATOS DE PAGO</td></tr>
         <tr><td class="label">Entidad:</td><td>{{ $applicant->financialEntity->name ?? '-' }}</td></tr>
         <tr><td class="label">Código Operación:</td><td>{{ $applicant->payment_operation_code }}</td></tr>
    </table>

    <div class="declaration">
        <strong>DECLARACIÓN JURADA</strong><br>
        Yo, <strong>{{ $applicant->user->lastname }} {{ $applicant->user->name }}</strong>, identificado con DNI N° <strong>{{ $applicant->user->document_number }}</strong>, declaro bajo juramento que los datos consignados en la presente ficha son verdaderos y que conozco el Reglamento de Admisión vigente.
        Asimismo, me comprometo a regularizar mi expediente en caso de alcanzar una vacante.
    </div>

    <div class="signatures">
        <div class="sig-box">
            <div class="line"></div>
            Postulante
        </div>
        <div class="sig-box">
            <div class="line"></div>
            Responsable de Admisión
        </div>
    </div>
    <div style="clear:both;"></div>

    <div class="footer">
        <p>Fecha: {{ now()->format('d/m/Y H:i:s') }} | Código: {{ $applicant->code }}</p>
    </div>
</body>
</html>
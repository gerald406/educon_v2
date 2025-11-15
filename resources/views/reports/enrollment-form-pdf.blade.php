<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Matrícula</title>
    <style>
        /* Estilos de Encabezado y Pie de Página (igual que Fases anteriores) */
        @page { margin: 3.5cm 1.5cm 3cm 1.5cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; line-height: 1.3; }
        header { position: fixed; top: -2.8cm; left: 0cm; right: 0cm; height: 2.5cm; width: 100%; text-align: center; border-bottom: 1px solid #ccc; }
        footer { position: fixed; bottom: -2.5cm; left: 1.5cm; right: 1.5cm; height: 2cm; border-top: 1px solid #ccc; text-align: center; }
        .logo { max-width: 70px; max-height: 70px; margin-bottom: 5px; }
        .header-info h1 { margin: 0; font-size: 16px; font-weight: bold; }
        .header-info h2 { margin: 5px 0 0 0; font-size: 14px; font-weight: normal; }
        .page-number:after { content: "Página " counter(page); }
        main { width: 100%; }
        
        /* Estilos de la tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        th { background-color: #f0f0f0; font-size: 11px; text-align: center; }
        
        /* Estilos específicos */
        .details-box {
            width: 100%;
            border: 1px solid #999;
            padding: 10px;
            margin-bottom: 15px;
        }
        .details-box h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .details-box p { margin: 3px 0; font-size: 11px; }
        .details-box strong {
            display: inline-block;
            width: 120px; /* Ancho fijo para etiquetas */
        }
        
        .signatures {
            margin-top: 60px;
            width: 100%;
        }
        .signature-box {
            text-align: center;
            width: 45%;
            padding-top: 5px;
            font-size: 12px;
        }
        .student-signature {
            float: left;
            margin-left: 5%;
            border-top: 1px solid #000;
        }
        .secretary-signature {
            float: right;
            margin-right: 5%;
            border-top: 1px solid #000;
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    <header>
        @if($logoData)
            <img src="{{ $logoData }}" alt="Logo" class="logo">
        @endif
        <div class="header-info">
            <h1>{{ $institution?->name ?? 'Reporte del Sistema' }}</h1>
            <h2>Ficha de Matrícula</h2>
            <h3>Periodo Académico: {{ $activePeriod?->name }}</h3>
        </div>
    </header>
    <footer>
        <p>Ficha generada el {{ now()->format('d/m/Y h:i A') }} | <span class="page-number"></span></p>
    </footer>

    <main>
        <div class="details-box">
            <h3>Datos de Matrícula</h3>
            <p><strong>N° de Matrícula:</strong> {{ $enrollment->id }}</p>
            <p><strong>Fecha de Matrícula:</strong> {{ $enrollment->enrollment_date->format('d/m/Y h:i A') }}</p>
            <p><strong>Programa de Estudio:</strong> {{ $student->career->name }}</p>
            <p><strong>Plan de Estudios:</strong> {{ $student->studyPlan->name }}</p>
            <p><strong>Semestre Matriculado:</strong> {{ $enrollment->semester_enrolled }}</p>
        </div>
        
        <div class="details-box">
            <h3>Datos del Estudiante</h3>
            <p><strong>Estudiante:</strong> {{ $student->user->name }}</p>
            <p><strong>Código:</strong> {{ $student->code }}</p>
            <p><strong>Email:</strong> {{ $student->user->email }}</p>
            <p><strong>Fecha de Admisión:</strong> {{ $student->admission_date->format('d/m/Y') }}</p>
        </div>
        
        <h3>Unidades Didácticas Matriculadas</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">N°</th>
                    <th style="width: 15%;">Código</th>
                    <th>Unidad Didáctica (Curso)</th>
                    <th style="width: 10%;">Créd.</th>
                    <th style="width: 10%;">Horas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $index => $course)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $course->didacticUnit->code }}</td>
                        <td>{{ $course->didacticUnit->name }}</td>
                        <td style="text-align: center;">{{ $course->didacticUnit->credits }}</td>
                        <td style="text-align: center;">{{ $course->didacticUnit->weekly_hours }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No se inscribió en ninguna unidad didáctica.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signatures">
            <div class="signature-box student-signature">
                <p>{{ $student->user->name }}</p>
                <p>Firma del Estudiante</p>
            </div>
            <div class="signature-box secretary-signature">
                <p>_________________________</p>
                <p>Secretaría Académica</p>
            </div>
            <div class="clear"></div>
        </div>
    </main>
</body>
</html>
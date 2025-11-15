<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Notas Finales</title>
    <style>
        /* Estilos de Encabezado y Pie de Página (igual que Fase 71) */
        @page { margin: 3.5cm 1.5cm 3cm 1.5cm; } /* Aumentado margen inferior para firma */
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; line-height: 1.3; }
        header { position: fixed; top: -2.8cm; left: 0cm; right: 0cm; height: 2.5cm; width: 100%; text-align: center; border-bottom: 1px solid #ccc; }
        footer { position: fixed; bottom: -2.5cm; left: 1.5cm; right: 1.5cm; height: 2cm; border-top: 1px solid #ccc; text-align: center; }
        .logo { max-width: 70px; max-height: 70px; margin-bottom: 5px; }
        .header-info h1 { margin: 0; font-size: 16px; font-weight: bold; }
        .header-info h2 { margin: 5px 0 0 0; font-size: 14px; font-weight: normal; }
        .header-info h3 { margin: 5px 0 0 0; font-size: 12px; font-weight: normal; }
        .page-number:after { content: "Página " counter(page); }
        main { width: 100%; }
        
        /* Estilos de la tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        th { background-color: #f0f0f0; font-size: 11px; text-align: center; }
        
        /* Estilos específicos del Acta */
        .details { margin-bottom: 15px; }
        .details p { margin: 3px 0; }
        .status-approved { color: green; font-weight: bold; }
        .status-failed { color: red; font-weight: bold; }
        
        .signature-box {
            text-align: center;
            width: 250px;
            margin: 60px auto 0 auto;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <header>
        @if($logoData)
            <img src="{{ $logoData }}" alt="Logo" class="logo">
        @endif
        <div class="header-info">
            <h1>{{ $institution?->name ?? 'Reporte del Sistema' }}</h1>
            <h2>Acta de Evaluación Final</h2>
            <h3>Periodo Académico: {{ $activePeriod?->name }}</h3>
        </div>
    </header>
    <footer>
        <p>Reporte generado el {{ now()->format('d/m/Y h:i A') }} | <span class="page-number"></span></p>
    </footer>

    <main>
        <div class="details">
            <p><strong>Programa de Estudio:</strong> {{ $assignment->didacticUnit->module->studyPlan->career->name }}</p>
            <p><strong>Unidad Didáctica:</strong> {{ $assignment->didacticUnit->name }} (Cód: {{ $assignment->didacticUnit->code }})</p>
            <p><strong>Docente:</strong> {{ $teacher->user->name }}</p>
            <p><strong>Semestre:</strong> {{ $assignment->didacticUnit->semester }} | 
               <strong>Sección:</strong> {{ $assignment->section }} | 
               <strong>Turno:</strong> {{ $assignment->shift->name }}
            </p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">N°</th>
                    <th style="width: 15%;">Código Est.</th>
                    <th>Nombre Completo del Estudiante</th>
                    <th style="width: 10%;">Nota Final</th>
                    <th style="width: 15%;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($finalRecords as $index => $record)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $record->student->code }}</td>
                        <td>{{ $record->student->user->name }}</td>
                        <td style="text-align: center; font-weight: bold; font-size: 13px;">
                            {{ number_format($record->final_grade, 0) }}
                        </td>
                        <td @class([
                            'status-approved' => $record->course_status == 'approved',
                            'status-failed' => $record->course_status == 'failed',
                            'text-center'
                        ])>
                            {{ $record->course_status == 'approved' ? 'APROBADO' : 'DESAPROBADO' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No hay notas finales consolidadas para esta sección.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signature-box">
            <p>_________________________</p>
            <p>{{ $teacher->user->name }}</p>
            <p>Docente del Curso</p>
        </div>
    </main>
</body>
</html>
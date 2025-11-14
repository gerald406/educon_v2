<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>
    <style>
        /* Estilos de Encabezado y Pie de Página (igual que Fase 71) */
        @page { margin: 3.5cm 1.5cm 2.5cm 1.5cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; line-height: 1.4; }
        header { position: fixed; top: -2.8cm; left: 0cm; right: 0cm; height: 2.5cm; padding: 0 1.5cm; width: 100%; text-align: center; border-bottom: 1px solid #ccc; }
        footer { position: fixed; bottom: -2.5cm; left: 1.5cm; right: 1.5cm; height: 2cm; border-top: 1px solid #ccc; text-align: center; }
        .logo { max-width: 70px; max-height: 70px; margin-bottom: 5px; }
        .header-info h1 { margin: 0; font-size: 16px; font-weight: bold; }
        .header-info h2 { margin: 5px 0 0 0; font-size: 14px; font-weight: normal; }
        .header-info h3 { margin: 5px 0 0 0; font-size: 12px; font-weight: normal; }
        .page-number:after { content: "Página " counter(page); }
        main { width: 100%; }
        
        /* Estilos de la tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; font-size: 12px; text-align: center; }
        .status-present { color: green; }
        .status-absent { color: red; font-weight: bold; }
        .status-late { color: orange; }
        .status-justified { color: blue; }
        .status-no-data { color: gray; }
    </style>
</head>
<body>
    <header>
        @if($logoData)
            <img src="{{ $logoData }}" alt="Logo" class="logo">
        @endif
        <div class="header-info">
            <h1>{{ $institution?->name ?? 'Reporte del Sistema' }}</h1>
            <h2>Reporte de Asistencia</h2>
            <h3>Periodo: {{ $activePeriod?->name }}</h3>
        </div>
    </header>
    <footer>
        <p>Reporte generado el {{ now()->format('d/m/Y h:i A') }} | <span class="page-number"></span></p>
    </footer>

    <main>
        <p>
            <strong>Curso:</strong> {{ $assignment->didacticUnit->name }}<br>
            <strong>Sección:</strong> {{ $assignment->section }} ({{ $assignment->shift->name }})<br>
            <strong>Docente:</strong> {{ $teacher->user->name }}<br>
            <strong>Fecha del Reporte:</strong> {{ \Carbon\Carbon::parse($reportDate)->format('d/m/Y') }}
        </p>
        
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Código Estudiante</th>
                    <th>Nombre Completo</th>
                    <th>Estado de Asistencia</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reportData as $index => $data)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $data['code'] }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td class="status-{{ $data['status_key'] }}">
                            {{ $data['status_text'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No hay estudiantes matriculados en esta sección.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
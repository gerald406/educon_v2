<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Acumulativo de Asistencia</title>
    <style>
        /* Estilos de Encabezado y Pie de Página (igual que Fase 71) */
        @page { margin: 3.5cm 1.5cm 2.5cm 1.5cm; }
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
        
        .details { margin-bottom: 15px; }
        .details p { margin: 3px 0; }
        .text-center { text-align: center; }
        .text-danger { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <header>
        @if($logoData)
            <img src="{{ $logoData }}" alt="Logo" class="logo">
        @endif
        <div class="header-info">
            <h1>{{ $institution?->name ?? 'Reporte del Sistema' }}</h1>
            <h2>Reporte Acumulativo de Asistencia</h2>
            <h3>Periodo: {{ $activePeriod?->name }}</h3>
        </div>
    </header>
    <footer>
        <p>Reporte generado el {{ now()->format('d/m/Y h:i A') }} | <span class="page-number"></span></p>
    </footer>

    <main>
        <div class="details">
            <p><strong>Curso:</strong> {{ $assignment->didacticUnit->name }}</p>
            <p><strong>Docente:</strong> {{ $teacher->user->name }}</p>
            <p><strong>Rango de Fechas:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th rowspan="2">N°</th>
                    <th rowspan="2">Estudiante</th>
                    <th colspan="4">Conteo de Asistencias</th>
                    <th rowspan="2">% Asistencia</th>
                </tr>
                <tr>
                    <th>Presente</th>
                    <th>Tarde</th>
                    <th>Ausente</th>
                    <th>Justificado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reportData as $index => $data)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td class="text-center">{{ $data['present'] }}</td>
                        <td class="text-center">{{ $data['late'] }}</td>
                        <td class="text-center">{{ $data['absent'] }}</td>
                        <td class="text-center">{{ $data['justified'] }}</td>
                        <td @class(['text-center', 'font-bold', 'text-danger' => $data['percentage'] < 70])>
                            {{ number_format($data['percentage'], 2) }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay estudiantes matriculados en esta sección.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
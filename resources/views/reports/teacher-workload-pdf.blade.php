<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Carga Horaria Docente</title>
    <style>
        /* Define el tamaño de la página y los márgenes */
        @page {
            /* Arriba, Derecha, Abajo, Izquierda */
            margin: 3.5cm 1.5cm 2.5cm 1.5cm; 
        }

        body { 
            font-family: 'Helvetica', sans-serif; 
            font-size: 11px; 
            line-height: 1.4;
        }

        /* --- Encabezado Fijo --- */
        header {
            position: fixed;
            top: -2.8cm; /* Distancia desde el borde superior */
            left: 0cm;
            right: 0cm;
            height: 2.5cm; /* Altura del encabezado */
            
            /* [CAMBIO] Centramos todo el contenido del encabezado */
            text-align: center;
            
            width: 100%;
            border-bottom: 1px solid #ccc;
        }

        /* --- Pie de página Fijo --- */
        footer {
            position: fixed; 
            bottom: -2.5cm; /* Distancia desde el borde inferior */
            left: 1.5cm;
            right: 1.5cm;
            height: 2cm;
            border-top: 1px solid #ccc;
            text-align: center;
        }

        /* [CAMBIO] Estilo del Logo Centrado */
        .logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 5px; /* Espacio entre logo y texto */
        }

        /* [CAMBIO] Estilos del Texto del Encabezado */
        .header-info h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header-info h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: normal;
        }
        .header-info h3 {
            margin: 5px 0 0 0;
            font-size: 12px;
            font-weight: normal;
        }
        .clear {
            clear: both;
        }

        /* Numeración de página */
        .page-number:after {
            content: "Página " counter(page);
        }
        
        /* --- Contenido Principal (main) --- */
        main {
            width: 100%;
        }

        /* Estilos de la tabla (sin cambios) */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th, td { 
            border: 1px solid #999; 
            padding: 6px; 
            text-align: left; 
            word-wrap: break-word;
        }
        th { 
            background-color: #f0f0f0; 
            font-size: 12px;
            text-align: center;
        }
        .status-ok { color: green; }
        .status-overload { color: red; font-weight: bold; }
        .status-underload { color: orange; }
    </style>
</head>
<body>
    <header>
        @if($logoData)
            <img src="{{ $logoData }}" alt="Logo" class="logo">
        @endif
        
        <div class="header-info">
            <h1>{{ $institution?->name ?? 'Reporte del Sistema' }}</h1>
            <h2>Reporte de Carga Horaria Docente</h2>
            <h3>Periodo Académico: {{ $activePeriod?->name ?? 'N/A' }}</h3>
        </div>
    </header>

    <footer>
        <p>Reporte generado el {{ now()->format('d/m/Y h:i A') }} | <span class="page-number"></span></p>
    </footer>

    <main>
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Docente</th>
                    <th>Código</th>
                    <th>Horas Asignadas</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($workloadData as $index => $data)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $data['name'] }}</td>
                    <td>{{ $data['code'] }}</td>
                    <td style="text-align: center;">{{ $data['hours'] }}</td>
                    <td class="status-{{ $data['status_key'] }}">
                        {{ $data['status_text'] }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No hay datos de carga horaria para este periodo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
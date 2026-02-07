<!DOCTYPE html>
<html>
<head>
    <title>Lista de Participantes - Aula {{ $classroom->room_number }}</title>
    <style>
        @page { margin: 1cm 1.5cm; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; }
        
        /* Encabezado */
        .header-container { text-align: center; margin-bottom: 20px; position: relative; height: 100px; }
        
        .logo { 
            position: absolute; 
            top: 0; 
            left: 20px; 
            width: 60px; 
            height: auto; 
        }

        .header-text { margin-top: 5px; }
        .h1-title { font-size: 14px; font-weight: bold; margin: 0; line-height: 1.2; }
        .h2-subtitle { font-size: 12px; font-weight: normal; margin: 2px 0 10px 0; font-style: italic; }
        
        .doc-title { 
            font-size: 14px; 
            font-weight: bold; 
            margin-top: 15px; 
            text-transform: uppercase; 
            text-decoration: underline;
        }

        /* Información del Aula */
        .info-section { margin-bottom: 10px; font-size: 12px; }
        .info-row { margin-bottom: 4px; }
        
        /* Tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: middle; }
        th { background-color: #fff; font-weight: bold; text-align: center; font-size: 10px; text-transform: uppercase; }
        td { font-size: 10px; }

        /* Columnas específicas */
        .col-n { width: 5%; text-align: center; }
        .col-name { text-align: left; }
        .col-program { width: 25%; text-align: left; font-size: 9px; }
        .col-sign { width: 15%; }
        
        /* Pie de página */
        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            font-size: 9px; 
            text-align: right; 
            color: #444; 
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <div class="header-container">
        @if($logoData) 
            <img src="{{ $logoData }}" class="logo"> 
        @endif
        
        <div class="header-text">
            <div class="h1-title">
                {{ strtoupper($institution->name ?? 'INSTITUTO DE EDUCACIÓN SUPERIOR') }}
            </div>
            <div class="h2-subtitle">
                Instituto de Excelencia de la Región de Puno
            </div>
            
            <div class="doc-title">
                LISTA DE PARTICIPANTES - AULA {{ $classroom->room_number }}
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <strong>Pabellón:</strong> {{ strtoupper($classroom->pavilion->name) }}
        </div>
        <div class="info-row">
            <strong>Capacidad:</strong> {{ $classroom->capacity }} participantes
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-n">N°</th>
                <th class="col-name">Apellidos y Nombres</th>
                <th class="col-program">Pabellón</th>
                <th class="col-sign">Aula</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $index => $assign)
                <tr>
                    <td class="col-n">{{ $index + 1 }}</td>
                    <td class="col-name">
                        {{ strtoupper($assign->applicant->user->lastname) }} {{ strtoupper($assign->applicant->user->name) }}
                    </td>
                    <td class="col-program">
                        {{ strtoupper($classroom->pavilion->name) }}
                    </td>
                    <td class="col-sign"> AULA {{ $classroom->room_number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Fecha de impresión: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
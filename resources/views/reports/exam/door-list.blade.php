<!DOCTYPE html>
<html>
<head>
    <title>Lista de Aula</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LISTA DE ASISTENCIA - EXAMEN DE ADMISIÓN</h2>
        <h3>{{ $classroom->pavilion->name }} - AULA {{ $classroom->room_number }}</h3>
    </div>

    <div class="info">
        <strong>Capacidad:</strong> {{ $classroom->capacity }} | 
        <strong>Asignados:</strong> {{ $assignments->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">N°</th>
                <th width="15%">DNI</th>
                <th>Apellidos y Nombres</th>
                <th>Carrera</th>
                <th width="15%">Firma</th>
                <th width="15%">Huella</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $index => $assign)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $assign->applicant->user->document_number }}</td>
                    <td>
                        {{ $assign->applicant->user->lastname }}, {{ $assign->applicant->user->name }}
                    </td>
                    <td>{{ $assign->applicant->admissionOffering->career->name }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
    </table>
</body>
</html>
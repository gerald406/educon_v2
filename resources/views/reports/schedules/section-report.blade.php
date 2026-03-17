<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 9pt; 
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18pt;
            color: #1F2937;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 11pt;
            color: #6B7280;
            font-weight: bold;
        }
        
        .info-section {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #4B5563;
            padding: 5px 10px;
            width: 25%;
            background-color: #F3F4F6;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 10px;
            color: #1F2937;
        }
        
        .weekly-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8pt;
        }
        
        .weekly-grid th {
            background-color: #4F46E5;
            color: white;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #3730A3;
        }
        
        .weekly-grid td {
            border: 1px solid #D1D5DB;
            padding: 8px 5px;
            vertical-align: top;
            min-height: 80px;
        }
        
        .weekly-grid .day-header {
            background-color: #EEF2FF;
            font-weight: bold;
            text-align: center;
            padding: 6px;
        }
        
        .schedule-block {
            background-color: #DBEAFE;
            border-left: 3px solid #2563EB;
            padding: 6px;
            margin-bottom: 6px;
            border-radius: 2px;
        }
        
        .schedule-block.morning {
            background-color: #DBEAFE;
            border-left-color: #2563EB;
        }
        
        .schedule-block.night {
            background-color: #E9D5FF;
            border-left-color: #7C3AED;
        }
        
        .schedule-block .time {
            font-weight: bold;
            color: #1F2937;
            font-size: 9pt;
        }
        
        .schedule-block .detail {
            color: #4B5563;
            font-size: 7pt;
            margin-top: 3px;
        }
        
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8pt;
        }
        
        .detail-table th {
            background-color: #F3F4F6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #D1D5DB;
        }
        
        .detail-table td {
            padding: 8px;
            border: 1px solid #E5E7EB;
        }
        
        .detail-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 7pt;
            font-weight: bold;
        }
        
        .badge-morning {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        
        .badge-night {
            background-color: #E9D5FF;
            color: #6B21A8;
        }
        
        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 7pt;
            color: #9CA3AF;
        }
        
        .day-empty {
            text-align: center;
            color: #D1D5DB;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">{{ $subtitle }}</div>
        <div class="subtitle" style="font-size: 8pt; margin-top: 5px;">
            Periodo: {{ $period->code }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})
        </div>
    </div>

    {{-- INFORMACIÓN DE LA SECCIÓN --}}
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Programa de Estudios:</div>
                <div class="info-value">{{ $assignment->didacticUnit->module->studyPlan->career->name ?? 'N/A' }}</div>
                <div class="info-label">Semestre:</div>
                <div class="info-value">{{ $assignment->didacticUnit->semester }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Unidad Didáctica:</div>
                <div class="info-value">{{ $assignment->didacticUnit->name }}</div>
                <div class="info-label">Código:</div>
                <div class="info-value">{{ $assignment->didacticUnit->code ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Docente:</div>
                <div class="info-value">{{ $assignment->teacher->user->name }} {{ $assignment->teacher->user->lastname }}</div>
                <div class="info-label">Turno:</div>
                <div class="info-value">{{ $assignment->shift->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sección:</div>
                <div class="info-value">{{ $assignment->section }}</div>
                <div class="info-label">Vacantes:</div>
                <div class="info-value">{{ $assignment->current_enrolled }} / {{ $assignment->max_capacity }}</div>
            </div>
        </div>
    </div>

    {{-- GRID SEMANAL --}}
    <h3 style="font-size: 11pt; margin-bottom: 10px; color: #1F2937;">Distribución Semanal</h3>
    <table class="weekly-grid">
        <thead>
            <tr>
                <th style="width: 12%;">Lunes</th>
                <th style="width: 12%;">Martes</th>
                <th style="width: 12%;">Miércoles</th>
                <th style="width: 12%;">Jueves</th>
                <th style="width: 12%;">Viernes</th>
                <th style="width: 12%;">Sábado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                    <td>
                        @php
                            $daySchedules = $groupedSchedules->get($day, collect());
                        @endphp
                        
                        @forelse($daySchedules as $schedule)
                            @php
                                $startHour = (int)$schedule->start_time->format('H');
                                $shiftClass = ($startHour >= 7 && $startHour < 14) ? 'morning' : 'night';
                            @endphp
                            
                            <div class="schedule-block {{ $shiftClass }}">
                                <div class="time">
                                    {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                </div>
                                @if($schedule->classroomResource)
                                    <div class="detail">
                                        📍 {{ $schedule->classroomResource->name }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="day-empty">Libre</div>
                        @endforelse
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    {{-- TABLA DETALLADA --}}
    <h3 style="font-size: 11pt; margin-top: 20px; margin-bottom: 10px; color: #1F2937;">Detalle de Bloques Horarios</h3>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Día</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Duración</th>
                <th>Turno</th>
                <th>Aula</th>
            </tr>
        </thead>
        <tbody>
            @php
                $days = [
                    'monday' => 'Lunes',
                    'tuesday' => 'Martes',
                    'wednesday' => 'Miércoles',
                    'thursday' => 'Jueves',
                    'friday' => 'Viernes',
                    'saturday' => 'Sábado'
                ];
            @endphp
            @foreach($schedules as $schedule)
                @php
                    $startHour = (int)$schedule->start_time->format('H');
                    $shiftType = ($startHour >= 7 && $startHour < 14) ? 'morning' : 'night';
                    $shiftName = $shiftType === 'morning' ? 'Mañana' : 'Noche';
                    $duration = $schedule->start_time->diffInMinutes($schedule->end_time);
                    $hours = floor($duration / 60);
                    $minutes = $duration % 60;
                @endphp
                <tr>
                    <td>{{ $days[$schedule->day_of_week] }}</td>
                    <td>{{ $schedule->start_time->format('h:i A') }}</td>
                    <td>{{ $schedule->end_time->format('h:i A') }}</td>
                    <td>{{ $hours }}h {{ $minutes }}m</td>
                    <td>
                        <span class="badge badge-{{ $shiftType }}">{{ $shiftName }}</span>
                    </td>
                    <td>{{ $schedule->classroomResource->name ?? 'Sin asignar' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i') }}</p>
        <p>Total de bloques: {{ $schedules->count() }} | Total de horas semanales: {{ number_format($schedules->sum(fn($s) => $s->start_time->diffInMinutes($s->end_time)) / 60, 2) }}h</p>
    </div>
</body>
</html>
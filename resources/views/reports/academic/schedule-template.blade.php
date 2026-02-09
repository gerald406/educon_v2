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
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 16pt;
            color: #1F2937;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 10pt;
            color: #6B7280;
            font-weight: bold;
        }
        
        .weekly-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 8pt;
        }
        
        .weekly-grid th {
            background-color: #4F46E5;
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #3730A3;
        }
        
        .weekly-grid td {
            border: 1px solid #D1D5DB;
            padding: 6px 4px;
            vertical-align: top;
            height: 120px;
        }
        
        .weekly-grid .hour-cell {
            background-color: #F3F4F6;
            font-weight: bold;
            text-align: center;
            width: 50px;
        }
        
        .schedule-block {
            background-color: #EFF6FF;
            border-left: 3px solid #3B82F6;
            padding: 4px;
            margin-bottom: 4px;
            font-size: 7pt;
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
        }
        
        .schedule-block .course {
            font-weight: bold;
            color: #374151;
            margin-top: 2px;
        }
        
        .schedule-block .room {
            color: #6B7280;
            font-size: 6pt;
            margin-top: 2px;
        }
        
        .schedule-block .section {
            color: #4B5563;
            font-size: 6pt;
        }
        
        .legend {
            margin-top: 15px;
            padding: 8px;
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            font-size: 7pt;
        }
        
        .legend-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #1F2937;
        }
        
        .legend-item {
            display: inline-block;
            margin-right: 15px;
        }
        
        .legend-color {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 4px;
            vertical-align: middle;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 7pt;
            color: #9CA3AF;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">{{ $subtitle }}</div>
        <div class="subtitle" style="font-size: 8pt; margin-top: 5px;">
            {{ $period->start_date ? $period->start_date->format('d/m/Y') : '' }} - 
            {{ $period->end_date ? $period->end_date->format('d/m/Y') : '' }}
        </div>
    </div>

    <table class="weekly-grid">
        <thead>
            <tr>
                <th style="width: 50px;">Hora</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
                <th>Sábado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $hours = [
                    '07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00',
                    '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00',
                    '15:00-16:00', '16:00-17:00', '17:00-18:00', '18:00-19:00',
                    '19:00-20:00', '20:00-21:00'
                ];
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            @endphp
            
            @foreach($hours as $hourRange)
                <tr>
                    <td class="hour-cell">{{ $hourRange }}</td>
                    
                    @foreach($days as $day)
                        <td>
                            @php
                                $daySchedules = $groupedSchedules->get($day, collect());
                                [$rangeStart, $rangeEnd] = explode('-', $hourRange);
                                
                                $matchingSchedules = $daySchedules->filter(function($schedule) use ($rangeStart, $rangeEnd) {
                                    $schedStart = $schedule->start_time->format('H:i');
                                    $schedEnd = $schedule->end_time->format('H:i');
                                    // Lógica de intersección simple
                                    return ($schedStart < $rangeEnd && $schedEnd > $rangeStart);
                                });
                            @endphp
                            
                            @forelse($matchingSchedules as $schedule)
                                @php
                                    $startHour = (int)$schedule->start_time->format('H');
                                    $shiftClass = ($startHour >= 7 && $startHour < 14) ? 'morning' : 'night';
                                @endphp
                                
                                <div class="schedule-block {{ $shiftClass }}">
                                    <div class="time">
                                        {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                    </div>
                                    <div class="course">
                                        {{ $schedule->teacherAssignment->didacticUnit->name }}
                                    </div>
                                    <div class="section">
                                        Sección {{ $schedule->teacherAssignment->section }}
                                    </div>
                                    @if($schedule->classroomResource)
                                        <div class="room">
                                            📍 {{ $schedule->classroomResource->name }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                {{-- Celda vacía --}}
                            @endforelse
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <div class="legend-title">Leyenda:</div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #DBEAFE; border: 1px solid #2563EB;"></span>
            Turno Mañana (07:00 - 14:00)
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #E9D5FF; border: 1px solid #7C3AED;"></span>
            Turno Noche (17:00 - 21:00)
        </div>
    </div>

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i') }}</p>
        <p>Total de bloques horarios: {{ $groupedSchedules->flatten()->count() }}</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 8pt; 
            color: #333;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #F59E0B;
            padding-bottom: 8px;
        }
        
        .header h1 {
            font-size: 16pt;
            color: #1F2937;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 10pt;
            color: #D97706;
            font-weight: bold;
        }
        
        .classroom-info {
            display: table;
            width: 100%;
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border: 2px solid #F59E0B;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 5px 10px;
            text-align: center;
            border-right: 1px solid #F59E0B;
        }
        
        .info-cell:last-child {
            border-right: none;
        }
        
        .info-label {
            font-size: 7pt;
            color: #92400E;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .info-value {
            font-size: 11pt;
            color: #B45309;
            font-weight: bold;
            margin-top: 3px;
        }
        
        .occupancy-meter {
            height: 20px;
            background-color: #E5E7EB;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .occupancy-fill {
            height: 100%;
            background: linear-gradient(90deg, #10B981 0%, #059669 100%);
            transition: width 0.3s;
        }
        
        .weekly-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 7pt;
        }
        
        .weekly-grid th {
            background-color: #F59E0B;
            color: white;
            padding: 6px 3px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #D97706;
        }
        
        .weekly-grid td {
            border: 1px solid #D1D5DB;
            padding: 5px 3px;
            vertical-align: top;
            min-height: 100px;
        }
        
        .schedule-block {
            background-color: #DBEAFE;
            border-left: 3px solid #3B82F6;
            padding: 5px;
            margin-bottom: 5px;
            border-radius: 2px;
        }
        
        .schedule-block .course {
            font-weight: bold;
            color: #1F2937;
            font-size: 7pt;
            margin-bottom: 2px;
        }
        
        .schedule-block .time {
            color: #4B5563;
            font-size: 6pt;
        }
        
        .schedule-block .teacher {
            color: #6B7280;
            font-size: 6pt;
            font-style: italic;
            margin-top: 2px;
        }
        
        .schedule-block .section {
            color: #059669;
            font-size: 6pt;
            font-weight: bold;
        }
        
        .time-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 7pt;
        }
        
        .time-table th {
            background-color: #F3F4F6;
            padding: 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #D1D5DB;
        }
        
        .time-table td {
            padding: 6px;
            border: 1px solid #E5E7EB;
        }
        
        .time-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 6pt;
            color: #9CA3AF;
        }
        
        .alert {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 8px;
            margin-bottom: 15px;
            font-size: 7pt;
        }
        
        .alert strong {
            color: #92400E;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">{{ $subtitle }}</div>
        <div class="subtitle" style="font-size: 7pt; margin-top: 3px;">
            Periodo: {{ $period->code }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})
        </div>
    </div>

    {{-- INFORMACIÓN DEL AULA --}}
    <div class="classroom-info">
        <div class="info-row">
            <div class="info-cell">
                <div class="info-label">Tipo de Aula</div>
                <div class="info-value">{{ $classroom->type ?? 'N/A' }}</div>
            </div>
            <div class="info-cell">
                <div class="info-label">Capacidad</div>
                <div class="info-value">{{ $classroom->capacity ?? 'N/A' }}</div>
            </div>
            <div class="info-cell">
                <div class="info-label">Horas Ocupadas</div>
                <div class="info-value">{{ number_format($occupiedHours, 1) }}h</div>
            </div>
            <div class="info-cell">
                <div class="info-label">Tasa de Ocupación</div>
                <div class="info-value">{{ $occupancyRate }}%</div>
            </div>
        </div>
    </div>

    {{-- MEDIDOR DE OCUPACIÓN --}}
    <div style="margin-bottom: 15px;">
        <div style="font-size: 8pt; font-weight: bold; margin-bottom: 5px; color: #1F2937;">
            Nivel de Ocupación Semanal
        </div>
        <div class="occupancy-meter">
            <div class="occupancy-fill" style="width: {{ $occupancyRate }}%;"></div>
        </div>
        <div style="font-size: 6pt; color: #6B7280; text-align: center;">
            {{ number_format($occupiedHours, 1) }} de 84 horas semanales disponibles
        </div>
    </div>

    @if($occupancyRate < 30)
        <div class="alert">
            <strong>⚠️ Alerta:</strong> El aula tiene una baja tasa de ocupación ({{ $occupancyRate }}%). 
            Considere reasignar o compartir con otros programas.
        </div>
    @elseif($occupancyRate > 85)
        <div class="alert">
            <strong>⚠️ Alerta:</strong> El aula tiene una alta tasa de ocupación ({{ $occupancyRate }}%). 
            Verificar disponibilidad para casos especiales.
        </div>
    @endif

    {{-- HORARIO SEMANAL --}}
    <h3 style="font-size: 10pt; margin-bottom: 8px; color: #1F2937;">Distribución Semanal de Uso</h3>
    <table class="weekly-grid">
        <thead>
            <tr>
                <th style="width: 14%;">Lunes</th>
                <th style="width: 14%;">Martes</th>
                <th style="width: 14%;">Miércoles</th>
                <th style="width: 14%;">Jueves</th>
                <th style="width: 14%;">Viernes</th>
                <th style="width: 14%;">Sábado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                    <td>
                        @php
                            $daySchedules = $groupedSchedules->get($day, collect())->sortBy('start_time');
                        @endphp
                        
                        @forelse($daySchedules as $schedule)
                            <div class="schedule-block">
                                <div class="time">
                                    ⏰ {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                </div>
                                <div class="course">
                                    {{ $schedule->teacherAssignment->didacticUnit->name }}
                                </div>
                                <div class="section">
                                    Sección {{ $schedule->teacherAssignment->section }}
                                </div>
                                <div class="teacher">
                                    👨‍🏫 {{ $schedule->teacherAssignment->teacher->user->name }} 
                                    {{ $schedule->teacherAssignment->teacher->user->lastname }}
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #10B981; padding: 20px; font-style: italic; font-size: 7pt;">
                                ✓ Disponible
                            </div>
                        @endforelse
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    {{-- TABLA DETALLADA --}}
    <h3 style="font-size: 10pt; margin-top: 15px; margin-bottom: 8px; color: #1F2937;">Detalle Cronológico</h3>
    <table class="time-table">
        <thead>
            <tr>
                <th>Día</th>
                <th>Horario</th>
                <th>Curso</th>
                <th>Sección</th>
                <th>Docente</th>
                <th>Programa</th>
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
                <tr>
                    <td>{{ $days[$schedule->day_of_week] }}</td>
                    <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                    <td>{{ $schedule->teacherAssignment->didacticUnit->name }}</td>
                    <td>{{ $schedule->teacherAssignment->section }}</td>
                    <td>
                        {{ $schedule->teacherAssignment->teacher->user->name }} 
                        {{ $schedule->teacherAssignment->teacher->user->lastname }}
                    </td>
                    <td style="font-size: 6pt;">
                        {{ $schedule->teacherAssignment->didacticUnit->module->studyPlan->career->name ?? 'N/A' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i') }}</p>
        <p>Bloques programados: {{ $schedules->count() }} | Horas ocupadas: {{ number_format($occupiedHours, 2) }}h | Tasa de ocupación: {{ $occupancyRate }}%</p>
    </div>
</body>
</html>
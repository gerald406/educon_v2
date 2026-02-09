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
            border-bottom: 3px solid #10B981;
            padding-bottom: 8px;
        }
        
        .header h1 {
            font-size: 16pt;
            color: #1F2937;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 10pt;
            color: #059669;
            font-weight: bold;
        }
        
        .stats-bar {
            display: table;
            width: 100%;
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
            border: 1px solid #6EE7B7;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 5px;
            border-right: 1px solid #6EE7B7;
        }
        
        .stat-item:last-child {
            border-right: none;
        }
        
        .stat-label {
            font-size: 7pt;
            color: #065F46;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .stat-value {
            font-size: 14pt;
            color: #047857;
            font-weight: bold;
            margin-top: 3px;
        }
        
        .courses-section {
            margin-bottom: 15px;
        }
        
        .course-card {
            background-color: #F9FAFB;
            border-left: 4px solid #10B981;
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 2px;
        }
        
        .course-title {
            font-weight: bold;
            color: #1F2937;
            font-size: 9pt;
        }
        
        .course-detail {
            font-size: 7pt;
            color: #6B7280;
            margin-top: 3px;
        }
        
        .weekly-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 7pt;
        }
        
        .weekly-grid th {
            background-color: #10B981;
            color: white;
            padding: 6px 3px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #059669;
            font-size: 7pt;
        }
        
        .weekly-grid td {
            border: 1px solid #D1D5DB;
            padding: 5px 3px;
            vertical-align: top;
            min-height: 100px;
        }
        
        .schedule-block {
            background-color: #DBEAFE;
            border-left: 2px solid #2563EB;
            padding: 4px;
            margin-bottom: 4px;
            border-radius: 2px;
            font-size: 6pt;
        }
        
        .schedule-block.morning {
            background-color: #FEF3C7;
            border-left-color: #F59E0B;
        }
        
        .schedule-block.night {
            background-color: #E0E7FF;
            border-left-color: #6366F1;
        }
        
        .schedule-block .course-name {
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 2px;
        }
        
        .schedule-block .time {
            color: #4B5563;
        }
        
        .schedule-block .section {
            color: #6B7280;
            font-style: italic;
        }
        
        .preparation-day {
            background-color: #FEF3C7;
            text-align: center;
            padding: 30px 5px;
            font-weight: bold;
            color: #92400E;
            font-style: italic;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 6pt;
            color: #9CA3AF;
        }
        
        .legend {
            margin-top: 10px;
            padding: 6px;
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            font-size: 6pt;
        }
        
        .legend-title {
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .legend-item {
            display: inline-block;
            margin-right: 10px;
        }
        
        .legend-color {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 3px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">{{ $subtitle }}</div>
        <div class="subtitle" style="font-size: 7pt; margin-top: 3px;">
            Periodo: {{ $period->code }} | DNI: {{ $teacher->user->document_number }}
        </div>
    </div>

    {{-- ESTADÍSTICAS --}}
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-label">Cursos Asignados</div>
            <div class="stat-value">{{ $totalCourses }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Horas Semanales</div>
            <div class="stat-value">{{ number_format($totalHours, 1) }}h</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Secciones</div>
            <div class="stat-value">{{ $assignments->count() }}</div>
        </div>
        @if($teacher->preparation_day)
        <div class="stat-item">
            <div class="stat-label">Día de Preparación</div>
            <div class="stat-value" style="font-size: 10pt;">
                {{ ucfirst([
                    'monday' => 'Lunes',
                    'tuesday' => 'Martes',
                    'wednesday' => 'Miércoles',
                    'thursday' => 'Jueves',
                    'friday' => 'Viernes',
                    'saturday' => 'Sábado'
                ][$teacher->preparation_day] ?? 'N/A') }}
            </div>
        </div>
        @endif
    </div>

    {{-- LISTA DE CURSOS --}}
    <div class="courses-section">
        <h3 style="font-size: 10pt; margin-bottom: 8px; color: #1F2937;">Cursos a Cargo</h3>
        @foreach($assignments as $assignment)
            <div class="course-card">
                <div class="course-title">
                    {{ $assignment->didacticUnit->name }} - Sección {{ $assignment->section }}
                </div>
                <div class="course-detail">
                    📚 {{ $assignment->didacticUnit->module->studyPlan->career->name ?? 'N/A' }} | 
                    Semestre {{ $assignment->didacticUnit->semester }} | 
                    {{ $assignment->shift->name ?? 'N/A' }} | 
                    {{ $assignment->current_enrolled }}/{{ $assignment->max_capacity }} estudiantes
                </div>
            </div>
        @endforeach
    </div>

    {{-- HORARIO SEMANAL --}}
    <h3 style="font-size: 10pt; margin-top: 15px; margin-bottom: 8px; color: #1F2937;">Distribución Semanal</h3>
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
                        @if($teacher->preparation_day === $day)
                            <div class="preparation-day">
                                🔒<br>
                                Día de<br>
                                Preparación
                            </div>
                        @else
                            @php
                                $daySchedules = $groupedSchedules->get($day, collect())->sortBy('start_time');
                            @endphp
                            
                            @forelse($daySchedules as $schedule)
                                @php
                                    $startHour = (int)$schedule->start_time->format('H');
                                    $shiftClass = ($startHour >= 7 && $startHour < 14) ? 'morning' : 'night';
                                @endphp
                                
                                <div class="schedule-block {{ $shiftClass }}">
                                    <div class="course-name">
                                        {{ $schedule->teacherAssignment->didacticUnit->name }}
                                    </div>
                                    <div class="time">
                                        ⏰ {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                    </div>
                                    <div class="section">
                                        Sec. {{ $schedule->teacherAssignment->section }}
                                    </div>
                                    @if($schedule->classroomResource)
                                        <div class="section">
                                            📍 {{ $schedule->classroomResource->name }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="text-align: center; color: #D1D5DB; padding: 20px; font-style: italic; font-size: 7pt;">
                                    Libre
                                </div>
                            @endforelse
                        @endif
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    {{-- LEYENDA --}}
    <div class="legend">
        <div class="legend-title">Leyenda:</div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #FEF3C7; border: 1px solid #F59E0B;"></span>
            Turno Mañana
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #E0E7FF; border: 1px solid #6366F1;"></span>
            Turno Noche
        </div>
        @if($teacher->preparation_day)
        <div class="legend-item">
            <span class="legend-color" style="background-color: #FEF3C7; border: 1px solid #92400E;"></span>
            Día de Preparación Docente
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i') }}</p>
        <p>Carga Horaria Total: {{ number_format($totalHours, 2) }} horas semanales | Bloques: {{ $schedules->count() }}</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 7pt; 
            color: #333;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #8B5CF6;
            padding-bottom: 8px;
        }
        
        .header h1 {
            font-size: 16pt;
            color: #1F2937;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 11pt;
            color: #7C3AED;
            font-weight: bold;
        }
        
        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-card {
            display: table-cell;
            background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
            border: 1px solid #A78BFA;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
            margin-right: 5px;
        }
        
        .summary-card:last-child {
            margin-right: 0;
        }
        
        .card-label {
            font-size: 6pt;
            color: #5B21B6;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .card-value {
            font-size: 14pt;
            color: #6B21A8;
            font-weight: bold;
            margin-top: 3px;
        }
        
        .semester-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .semester-header {
            background: linear-gradient(90deg, #8B5CF6 0%, #7C3AED 100%);
            color: white;
            padding: 8px 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9pt;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .course-card {
            background-color: #F9FAFB;
            border-left: 3px solid #8B5CF6;
            padding: 6px;
            border-radius: 2px;
        }
        
        .course-title {
            font-weight: bold;
            color: #1F2937;
            font-size: 7pt;
            margin-bottom: 3px;
        }
        
        .course-info {
            font-size: 6pt;
            color: #6B7280;
            line-height: 1.4;
        }
        
        .weekly-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 6pt;
        }
        
        .weekly-grid th {
            background-color: #8B5CF6;
            color: white;
            padding: 5px 2px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #7C3AED;
        }
        
        .weekly-grid td {
            border: 1px solid #D1D5DB;
            padding: 3px 2px;
            vertical-align: top;
            min-height: 80px;
            font-size: 5pt;
        }
        
        .schedule-mini {
            background-color: #EDE9FE;
            border-left: 2px solid #8B5CF6;
            padding: 3px;
            margin-bottom: 3px;
            border-radius: 1px;
        }
        
        .schedule-mini .time {
            font-weight: bold;
            color: #5B21B6;
        }
        
        .schedule-mini .course {
            color: #1F2937;
            margin-top: 1px;
        }
        
        .schedule-mini .section {
            color: #6B7280;
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
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">{{ $subtitle }}</div>
        <div class="subtitle" style="font-size: 8pt; margin-top: 3px;">
            Periodo Académico: {{ $period->code }}
        </div>
    </div>

    {{-- RESUMEN EJECUTIVO --}}
    <div class="summary-cards">
        <div class="summary-row">
            <div class="summary-card">
                <div class="card-label">Total Secciones</div>
                <div class="card-value">{{ $totalSections }}</div>
            </div>
            <div class="summary-card">
                <div class="card-label">Docentes Activos</div>
                <div class="card-value">{{ $totalTeachers }}</div>
            </div>
            <div class="summary-card">
                <div class="card-label">Cursos Diferentes</div>
                <div class="card-value">{{ $assignments->pluck('didactic_unit_id')->unique()->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="card-label">Bloques Horarios</div>
                <div class="card-value">{{ $schedules->count() }}</div>
            </div>
        </div>
    </div>

    {{-- DESGLOSE POR SEMESTRE --}}
    <h3 style="font-size: 10pt; margin-bottom: 10px; color: #1F2937; border-bottom: 2px solid #8B5CF6; padding-bottom: 5px;">
        Distribución por Ciclos Académicos
    </h3>

    @foreach($assignmentsBySemester as $semester => $semesterAssignments)
        <div class="semester-section">
            <div class="semester-header">
                📚 Ciclo {{ $semester }} ({{ $semesterAssignments->count() }} secciones)
            </div>
            
            <div class="courses-grid">
                @foreach($semesterAssignments as $assignment)
                    <div class="course-card">
                        <div class="course-title">
                            {{ $assignment->didacticUnit->name }}
                        </div>
                        <div class="course-info">
                            <div>🔸 Sección: {{ $assignment->section }}</div>
                            <div>👨‍🏫 {{ $assignment->teacher->user->name }} {{ $assignment->teacher->user->lastname }}</div>
                            <div>⏰ {{ $assignment->shift->name ?? 'N/A' }}</div>
                            <div>👥 {{ $assignment->current_enrolled }}/{{ $assignment->max_capacity }} estudiantes</div>
                            <div>📅 {{ $assignment->schedules->count() }} bloques horarios</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- SALTO DE PÁGINA --}}
    <div class="page-break"></div>

    {{-- HORARIO CONSOLIDADO SEMANAL --}}
    <h3 style="font-size: 10pt; margin-bottom: 10px; color: #1F2937; border-bottom: 2px solid #8B5CF6; padding-bottom: 5px;">
        Vista Consolidada Semanal - Todos los Ciclos
    </h3>
    
    <table class="weekly-grid">
        <thead>
            <tr>
                <th>Ciclo</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
                <th>Sábado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignmentsBySemester as $semester => $semesterAssignments)
                <tr>
                    <td style="background-color: #F3F4F6; font-weight: bold; text-align: center; font-size: 7pt;">
                        Ciclo<br>{{ $semester }}
                    </td>
                    
                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                        <td>
                            @php
                                // Obtener horarios de este semestre en este día
                                $daySchedules = $schedules
                                    ->filter(function($schedule) use ($day, $semester) {
                                        return $schedule->day_of_week === $day 
                                            && $schedule->teacherAssignment->didacticUnit->semester == $semester;
                                    })
                                    ->sortBy('start_time');
                            @endphp
                            
                            @foreach($daySchedules as $schedule)
                                <div class="schedule-mini">
                                    <div class="time">
                                        {{ $schedule->start_time->format('H:i') }}-{{ $schedule->end_time->format('H:i') }}
                                    </div>
                                    <div class="course">
                                        {{ Str::limit($schedule->teacherAssignment->didacticUnit->name, 25) }}
                                    </div>
                                    <div class="section">
                                        Sec. {{ $schedule->teacherAssignment->section }}
                                    </div>
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ESTADÍSTICAS FINALES --}}
    <div style="margin-top: 15px; padding: 10px; background-color: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 4px;">
        <h4 style="font-size: 8pt; font-weight: bold; margin-bottom: 8px; color: #1F2937;">
            📊 Estadísticas del Programa
        </h4>
        <div style="font-size: 6pt; color: #4B5563; line-height: 1.6;">
            <div><strong>Total de horas de clase semanales:</strong> 
                {{ number_format($schedules->sum(fn($s) => $s->start_time->diffInMinutes($s->end_time)) / 60, 2) }} horas
            </div>
            <div><strong>Promedio de horas por sección:</strong> 
                {{ $totalSections > 0 ? number_format(($schedules->sum(fn($s) => $s->start_time->diffInMinutes($s->end_time)) / 60) / $totalSections, 2) : 0 }} horas
            </div>
            <div><strong>Distribución por ciclo:</strong>
                @foreach($assignmentsBySemester as $sem => $assigns)
                    Ciclo {{ $sem }}: {{ $assigns->count() }} secciones | 
                @endforeach
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i') }}</p>
        <p>{{ $career->name }} - Periodo {{ $period->code }}</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sesión N° {{ $unit->session_number }}</title>
    <style>
        @page { margin: 1.5cm 1.5cm 2cm 1.5cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; line-height: 1.4; color: #111; }

        footer {
            position: fixed; bottom: -40px; left: 0; right: 0;
            height: 20px; font-size: 9px; color: #555;
            border-top: 1px solid #ccc; padding-top: 5px;
        }
        .page-number:after { content: counter(page); }

        .header-img { width: 100%; height: auto; display: block; }
        .section-title {
            font-size: 11px; font-weight: bold; text-transform: uppercase;
            background-color: #1e2120; color: white;
            padding: 5px 8px; margin-top: 12px; margin-bottom: 6px;
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #444; padding: 5px; vertical-align: top; font-size: 10px; }
        th { background-color: #e9ecef; font-weight: bold; text-align: center; }
        .label { font-weight: bold; width: 30%; }
        .no-border td { border: none; padding: 3px 0; }
        .moment-title { background-color: #f3f4f6; font-weight: bold; }
        .text-center { text-align: center; }
        .signatures { margin-top: 50px; width: 100%; }
        .sig-line { border-top: 1px solid #000; width: 80%; margin: 0 auto; }
    </style>
</head>
<body>

    <footer>
        <table style="border:none; margin:0; padding:0;">
            <tr>
                <td style="border:none; padding:0; color:#555;">
                    Impreso: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                </td>
                <td style="border:none; padding:0; text-align:right; color:#555;">
                    Página <span class="page-number"></span>
                </td>
            </tr>
        </table>
    </footer>

    <main>
        {{-- Encabezado institucional --}}
        <div style="margin-bottom: 10px; border-bottom: 2px solid #222; padding-bottom: 8px;">
            <img src="{{ public_path('images/encabezado-institucional.jpg') }}"
                 class="header-img" alt="Encabezado" />
            <p style="text-align:center; font-weight:bold; font-size:12px; margin-top:6px;">
                SESIÓN DE APRENDIZAJE N° {{ $unit->session_number }}
            </p>
        </div>

        {{-- I. INFORMACIÓN GENERAL --}}
        @php
            $assignment = $syllabus->teacherAssignment;
            $didUnit    = $assignment->didacticUnit;
            $module     = $didUnit->module;
            $plan       = $module->studyPlan;
            $career     = $plan->career;
            $period     = $assignment->academicPeriod;
            $teacher    = $assignment->teacher->user;

            $activityLabels = [
                'teorico'          => 'Teórico',
                'practico'         => 'Práctico',
                'teorico-practico' => 'Teórico-Práctico',
            ];
        @endphp

        <div class="section-title">I. Información General</div>
        <table>
            <tr>
                <td class="label">Programa de estudios</td>
                <td colspan="3">{{ Str::upper($career->name) }}</td>
            </tr>
            <tr>
                <td class="label">Módulo Formativo</td>
                <td colspan="3">{{ $module->name }}</td>
            </tr>
            <tr>
                <td class="label">Unidad de competencia</td>
                <td colspan="3">{{ strip_tags($syllabus->unit_competence) }}</td>
            </tr>
            <tr>
                <td class="label">Unidad didáctica</td>
                <td colspan="3">{{ $didUnit->name }}</td>
            </tr>
            <tr>
                <td class="label">Periodo Lectivo</td>
                <td>{{ $period->name }}</td>
                <td class="label">Periodo académico</td>
                <td>Semestre {{ $didUnit->semester }}</td>
            </tr>
            <tr>
                <td class="label">Capacidad</td>
                <td colspan="3">{{ strip_tags($syllabus->course_capacity) }}</td>
            </tr>
            <tr>
                <td class="label">Indicador de logro vinculado</td>
                <td colspan="3">{{ $unit->indicator->description }}</td>
            </tr>
            <tr>
                <td class="label">Competencia transversal priorizada</td>
                <td colspan="3">{{ $session->transversal_competence ?? '—' }}</td>
            </tr>
            {{-- DESPUÉS --}}
            <tr>
                <td class="label">Sesión de Aprendizaje</td>
                <td>N° {{ $unit->session_number }}</td>
                <td class="label">Fechas de desarrollo</td>
                <td>{{ $unit->week_range ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Tipo de actividad</td>
                <td colspan="3">{{ $activityLabels[$session->activity_type] ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Logro de la sesión</td>
                <td colspan="3">{{ $unit->learning_outcome }}</td>
            </tr>
            <tr>
                <td class="label">Docente responsable</td>
                <td colspan="3">{{ Str::upper(trim($teacher->name . ' ' . $teacher->lastname)) }}</td>
            </tr>
        </table>

        {{-- II. ACTIVIDADES DE APRENDIZAJE --}}
        <div class="section-title">II. Actividades de Aprendizaje</div>
        <table>
            <thead>
                <tr>
                    <th width="15%">Momentos</th>
                    <th width="55%">Actividades de Aprendizaje</th>
                    <th width="20%">Recursos Didácticos</th>
                    <th width="10%">Tiempo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($session->sequence_activities ?? [] as $moment)
                    <tr>
                        <td class="moment-title text-center">
                            <strong>{{ $moment['label'] }}</strong><br>
                            <span style="font-size:9px; font-weight:normal; color:#555;">
                                {{ $moment['hint'] }}
                            </span>
                        </td>
                        <td>{{ $moment['activity'] ?? '' }}</td>
                        <td class="text-center">{{ $moment['resources'] ?? '' }}</td>
                        <td class="text-center">{{ $moment['time'] ?? '' }} min</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- III. ACTIVIDADES DE EVALUACIÓN --}}
        <div class="section-title">III. Actividades de Evaluación</div>
        <table>
            <thead>
                <tr>
                    <th>Indicador de logro de la sesión</th>
                    <th>Técnicas</th>
                    <th>Instrumentos</th>
                    <th>Momento</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $session->evaluation_criteria ?? '—' }}</td>
                    <td class="text-center">{{ $session->evaluation_technique ?? '—' }}</td>
                    <td class="text-center">{{ $session->evaluation_instrument ?? '—' }}</td>
                    <td class="text-center">{{ $session->evaluation_moment ?? '—' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- IV. BIBLIOGRAFÍA --}}
        <div class="section-title">IV. Bibliografía (APA)</div>
        <div style="padding: 6px 10px; font-size: 10px; min-height: 30px;">
            {{ $session->bibliography ?? '—' }}
        </div>

        {{-- FIRMAS --}}
        <table class="signatures" style="border:none; margin-top: 50px;">
            <tr>
                <td style="border:none; text-align:center; vertical-align:bottom;" width="33%">
                    <div class="sig-line"></div><br>Docente Responsable
                </td>
                <td style="border:none; text-align:center; vertical-align:bottom;" width="33%">
                    <div class="sig-line"></div><br>Coordinador del PE
                </td>
                <td style="border:none; text-align:center; vertical-align:bottom;" width="33%">
                    <div class="sig-line"></div><br>Jefe Unidad Académica
                </td>
            </tr>
        </table>
    </main>
</body>
</html>
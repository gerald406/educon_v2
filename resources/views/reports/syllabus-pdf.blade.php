<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sílabo - {{ $syllabus->teacherAssignment?->didacticUnit?->name ?? 'Curso' }}</title>
    <style>
        /* CONFIGURACIÓN GENERAL PARA DOMPDF */
        @page { margin: 1.5cm 1.5cm 2cm 1.5cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            line-height: 1.4; 
            color: #111; 
        }
        
        /* PIE DE PÁGINA (FOOTER) REPETITIVO */
        footer {
            position: fixed; 
            bottom: -40px; 
            left: 0px; 
            right: 0px;
            height: 20px; 
            font-size: 9px; 
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .page-number:after {
            content: counter(page); 
        }
        
        /* ENCABEZADO INSTITUCIONAL */
        /* DESPUÉS */
        .header-box { 
            margin-bottom: 20px; 
            border-bottom: 2px solid #222; 
            padding-bottom: 10px; 
        }
        .header-img {
            width: 100%;
            height: auto;
            display: block;
        }
        h1 { font-size: 15px; margin: 0; text-transform: uppercase; }
        h2 { font-size: 13px; margin: 5px 0; font-weight: bold; }
        
        /* TÍTULOS DE SECCIÓN (I, II, III...) */
        h3 { 
            font-size: 12px; 
            margin-top: 15px; 
            margin-bottom: 8px;
            text-transform: uppercase; 
            background-color: #1e2120; color: white;
            padding: 5px 8px; 
            page-break-after: avoid; 
        }

        /* 🟢 NUEVO: CLASE PARA TABULACIÓN/SANGRÍA DEL CONTENIDO */
        .section-content {
            padding-left: 25px; /* Crea la tabulación a la izquierda */
            padding-right: 5px;
        }
        
        /* TABLAS ESTRUCTURALES */
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 5px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; vertical-align: top; }
        th { background-color: #e9ecef; font-weight: bold; text-align: center; }
        
        /* UTILIDADES */
        .no-border td { border: none; padding: 3px 0; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .mt-2 { margin-top: 10px; }
        .mb-2 { margin-bottom: 10px; }
        
        /* CONTENIDO HTML DESDE CKEDITOR */
        .html-content { text-align: justify; margin-bottom: 10px; }
        .html-content p { margin: 0 0 5px 0; }
        .html-content ul, .html-content ol { 
            padding-left: 20px; 
            margin-top: 5px; 
            margin-bottom: 5px; 
        }
        .html-content li { margin-bottom: 3px; }
        
        /* FIRMAS */
        .footer-signatures { margin-top: 60px; width: 100%; page-break-inside: avoid; }
        .signature-line { border-top: 1px solid #000; width: 85%; margin: 0 auto; }
    </style>
</head>
<body>

    {{-- PIE DE PÁGINA --}}
    <footer>
        <table style="width: 100%; border: none; margin: 0; padding: 0;">
            <tr>
                <td style="border: none; text-align: left; padding: 0; color: #555;">
                    Fecha de impresión: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                </td>
                <td style="border: none; text-align: right; padding: 0; color: #555;">
                    Página <span class="page-number"></span>
                </td>
            </tr>
        </table>
    </footer>

    {{-- CONTENIDO PRINCIPAL --}}
    <main>
        <div class="header-box">
            {{-- Imagen institucional: ruta absoluta requerida por DomPDF --}}
            <img
                src="{{ public_path('images/encabezado-institucional.jpg') }}"
                class="header-img"
                alt="Encabezado Institucional"
            />
            <p style="font-weight: bold; font-size: 12px; margin-top: 8px; text-align: center;">
                SÍLABO DE {{ Str::upper($syllabus->teacherAssignment?->didacticUnit?->name ?? '---') }}
            </p>
        </div>

        {{-- I. DATOS GENERALES --}}
        <h3>I. DATOS GENERALES</h3>
        <div class="section-content">
            @php
                $assignment = $syllabus->teacherAssignment;
                $unit       = $assignment?->didacticUnit;
                $module     = $unit?->module;
                $plan       = $module?->studyPlan;
                $career     = $plan?->career;
                $period     = $assignment?->academicPeriod;
                $shift      = $assignment?->shift;
                $teacher    = $assignment?->teacher;
                $user       = $teacher?->user;

                $totalHours  = $unit?->total_hours ?? 0;
                $weeklyHours = $unit?->weekly_hours > 0 ? $unit->weekly_hours : ($totalHours > 0 ? $totalHours / 16 : 0);
                
                $startDate = $period?->start_date ? \Carbon\Carbon::parse($period->start_date)->format('d/m/Y') : '---';
                $endDate   = $period?->end_date ? \Carbon\Carbon::parse($period->end_date)->format('d/m/Y') : '---';
            @endphp

            <table class="no-border" style="width: 100%; font-size: 11px;">
                <tr>
                    <td width="27%" class="text-bold">1.1 PROGRAMA DE ESTUDIOS</td>
                    <td width="3%">:</td>
                    <td width="70%">{{ Str::upper($career?->name ?? '---') }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.2 PLAN DE ESTUDIOS</td>
                    <td>:</td>
                    <td>{{ Str::upper($plan?->name ?? '---') }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.3 MÓDULO FORMATIVO</td>
                    <td>:</td>
                    <td>{{ Str::upper($module?->name ?? '---') }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.4 UNIDAD DIDÁCTICA</td>
                    <td>:</td>
                    <td>{{ Str::upper($unit?->name ?? '---') }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.5 CRÉDITOS ACADÉMICOS</td>
                    <td>:</td>
                    <td>{{ $unit?->credits ?? '0' }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.6 HORAS TOTALES</td>
                    <td>:</td>
                    <td>{{ $totalHours }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.7 HORAS SEMANALES</td>
                    <td>:</td>
                    <td>{{ $weeklyHours }}h</td>
                </tr>
                <tr>
                    <td class="text-bold">1.8 PERIODO LECTIVO</td>
                    <td>:</td>
                    <td>{{ Str::upper($period?->name ?? '---') }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.9 PERIODO ACADÉMICO</td>
                    <td>:</td>
                    <td>{{ Str::upper($unit?->semester ?? '---') }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.10 FECHA INICIO Y TÉRMINO</td>
                    <td>:</td>
                    <td>{{ $startDate }} – {{ $endDate }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.11 TURNO</td>
                    <td>:</td>
                    <td>{{ Str::title($shift?->name ?? '---') }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.12 DOCENTE</td>
                    <td>:</td>
                    <td>{{ Str::upper(($user?->lastname ?? '') . ' ' . ($user?->name ?? 'NO ASIGNADO')) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">1.13 CORREO INSTITUCIONAL</td>
                    <td>:</td>
                    <td>{{ Str::lower($user?->email ?? '---') }}</td>
                </tr>
            </table>
        </div>

        {{-- II. SUMILLA --}}
        <h3>II. SUMILLA</h3>
        <div class="section-content">
            <div class="html-content">{!! $syllabus->sumilla !!}</div>
        </div>

        {{-- III. UNIDAD DE COMPETENCIA --}}
        <h3>III. UNIDAD DE COMPETENCIA</h3>
        <div class="section-content">
            <div class="html-content">{!! $syllabus->unit_competence !!}</div>
        </div>

        {{-- IV. CAPACIDAD E INDICADORES --}}
        <h3>IV. CAPACIDAD E INDICADORES</h3>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th width="40%">Capacidad de la Unidad Didáctica</th>
                        <th width="60%">Indicadores de Logro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div class="html-content">{!! $syllabus->course_capacity !!}</div></td>
                        <td>
                            <ul style="margin:0; padding-left: 15px;">
                                @foreach($syllabus->indicators as $ind)
                                    <li style="margin-bottom: 5px; text-align: justify;">{{ $ind->description }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- V. EMPLEABILIDAD --}}
        <h3>V. COMPETENCIAS PARA LA EMPLEABILIDAD</h3>
        <div class="section-content">
            <div class="html-content">
                @php
                    $employability = is_array($syllabus->employability_competencies) 
                        ? ($syllabus->employability_competencies[0] ?? '') 
                        : $syllabus->employability_competencies;
                @endphp
                {!! $employability !!}
            </div>
        </div>

        {{-- VI. PROGRAMACIÓN DE SESIONES --}}
        <div style="page-break-before: always;"></div>
        <h3>VI. ACTIVIDADES DE APRENDIZAJE</h3>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th width="5%">Sem</th>
                        <th width="35%">Actividad / Contenidos Básicos</th>
                        <th width="45%">Logro de la Sesión</th>
                        <th width="15%">Instrumento</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($syllabus->indicators as $indicator)
                        <tr>
                            <td colspan="4" style="background-color: #d1d5db; font-size: 10px; font-weight: bold;">
                                Indicador de Logro: {{ $indicator->description }}
                            </td>
                        </tr>
                        @foreach($indicator->units as $unit)
                            <tr>
                                <td class="text-center">{{ $unit->session_number }}</td>
                                <td>
                                    <strong style="font-size: 11px;">{{ $unit->name }}</strong><br>
                                    <div class="html-content" style="font-size: 10px; color: #333; margin-top: 3px;">
                                        {!! $unit->content !!}
                                    </div>
                                </td>
                                <td style="font-size: 10px;">{{ $unit->learning_outcome }}</td>
                                <td style="font-size: 10px;" class="text-center">{{ $unit->evaluation_instrument }}</td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay sesiones programadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- VII. METODOLOGÍA --}}
        <div style="page-break-inside: avoid;">
            <h3>VII. METODOLOGÍA</h3>
            <div class="section-content">
                <div class="html-content">{!! $syllabus->methodology !!}</div>
            </div>
        </div>

        {{-- VIII. AMBIENTES Y RECURSOS --}}
        <div style="page-break-inside: avoid;">
            <h3>VIII. AMBIENTES Y RECURSOS</h3>
            <div class="section-content">
                <p class="text-bold mt-2" style="margin-bottom: 2px;">Ambientes</p>
                <div class="html-content">{!! $syllabus->environments !!}</div>
                
                <p class="text-bold mt-2" style="margin-bottom: 2px;">Recursos</p>
                <div class="html-content">{!! $syllabus->resources !!}</div>
            </div>
        </div>

        {{-- IX. SISTEMA DE EVALUACIÓN --}}
        <div style="page-break-inside: avoid;">
            <h3>IX. SISTEMA DE EVALUACIÓN</h3>
            <div class="section-content">
                <div class="html-content">{!! $syllabus->evaluation_system !!}</div>
            </div>
        </div>

        {{-- X. FUENTES DE INFORMACIÓN --}}
        <div style="page-break-inside: avoid;">
            <h3>X. FUENTES DE INFORMACIÓN</h3>
            <div class="section-content">
                <p class="text-bold mt-2" style="margin-bottom: 2px;">10.1. Bibliografía</p>
                <div class="html-content mb-2">{!! $syllabus->bibliography !!}</div>

                <p class="text-bold mt-2" style="margin-bottom: 2px;">10.2. Páginas Web (URLs)</p>
                <div class="html-content">{!! $syllabus->web_sources !!}</div>
            </div>
        </div>

        {{-- FECHA DE EMISIÓN / IMPRESIÓN --}}
        <div style="text-align: right; margin-top: 2px; margin-bottom: 75px; font-size: 11px;">
            Salcedo, {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
        </div>

        {{-- FIRMAS INSTITUCIONALES --}}
        <table class="footer-signatures" style="border: none; width: 100%;">
            <tr>
                <td style="border: none; text-align: center; vertical-align: bottom;" width="33%">
                    <div class="signature-line"></div>
                    <br>Docente Responsable
                </td>
                <td style="border: none; text-align: center; vertical-align: bottom;" width="33%">
                    <div class="signature-line"></div>
                    <br>Coordinador de Programa
                </td>
                <td style="border: none; text-align: center; vertical-align: bottom;" width="33%">
                    <div class="signature-line"></div>
                    <br>Jefe de Unidad Académica
                </td>
            </tr>
        </table>
    </main>
</body>
</html>
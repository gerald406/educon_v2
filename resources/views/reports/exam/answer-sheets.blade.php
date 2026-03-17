<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hojas de Respuesta — Aula {{ $classroom->room_number }}</title>
    <style>
        @page { margin: 1cm 1.5cm 1.2cm 1.5cm; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        /* ── PAGINACIÓN ── */
        .page { page-break-after: always; }
        .page:last-child { page-break-after: avoid; }

        /* ── ENCABEZADO ── */
        .header-img {
            width: 100%;
            height: auto;
            display: block;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }

        .titulo-doc {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background: #1a1a1a;
            color: #fff;
            padding: 5px 8px;
            margin-bottom: 7px;
            letter-spacing: 1px;
        }

        /* ── INSTRUCCIONES ── */
        .instrucciones {
            border-left: 3px solid #1a1a1a;
            padding: 4px 8px;
            margin-bottom: 7px;
            font-size: 9px;
            background: #f5f5f5;
            color: #333;
        }

        /* ── DATOS DEL POSTULANTE ── */
        .datos-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
        }
        .datos-table td {
            border: 1px solid #bbb;
            padding: 3.5px 7px;
            font-size: 10px;
        }
        .datos-table .lbl {
            font-weight: bold;
            background: #f0f0f0;
            width: 30%;
            color: #222;
        }

        /* ── QR ── */
        .qr-wrap {
            position: absolute;
            top: 0;
            right: 0;
            width: 2.3cm;
            text-align: center;
        }
        .qr-wrap img {
            width: 2.3cm;
            height: 2.3cm;
        }
        .qr-wrap .qr-label {
            font-size: 7px;
            color: #666;
            margin-top: 2px;
            letter-spacing: 1px;
        }

        /* ── TÍTULO CUADRÍCULA ── */
        .section-title {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border-top: 1.5px solid #1a1a1a;
            border-bottom: 1.5px solid #1a1a1a;
            padding: 4px;
            margin: 7px 0 6px 0;
            letter-spacing: 2px;
            color: #1a1a1a;
        }

        /* ── CUADRÍCULA DE RESPUESTAS ── */
        .grid-cols {
            width: 100%;
            border-collapse: collapse;
        }
        .grid-cols > tr > td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .col-sep {
            width: 6px;
            border-left: 1px dashed #ccc !important;
            padding: 0 6px !important;
        }

        .answer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .answer-table tr td {
            border: none;
            padding: 2.5px 1px;
            white-space: nowrap;
            vertical-align: middle;
        }

        .q-num {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
            width: 18px;
            font-size: 9.5px;
            color: #333;
        }

        .bubble {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 1.5px solid #1a1a1a;
            border-radius: 50%;
            text-align: center;
            line-height: 17px;
            font-size: 9.5px;
            font-weight: bold;
            margin: 0 1.5px;
            color: #1a1a1a;
            vertical-align: middle;
        }

        /* ── PIE ── */
        .pie {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 3px;
            font-style: italic;
        }
    </style>
</head>
<body>

@foreach($assignments as $assignment)
@php
    $applicant = $assignment->applicant;
    $user      = $applicant->user;
    $offering  = $applicant->admissionOffering;
    $career    = $offering?->career;
    $shift     = $offering?->shift;
    $dni       = $user->document_number ?? '00000000';
    $fullName  = strtoupper(trim(($user->lastname ?? '') . ', ' . ($user->name ?? '')));
    $peturno   = strtoupper(($career?->name ?? 'SIN PROGRAMA') . ' / ' . ($shift?->name ?? 'SIN TURNO'));
    $ubicacion = strtoupper(
        ($classroom->pavilion->name ?? 'SIN PABELLÓN') .
        ' — Aula: ' . $classroom->room_number
    );
    $qr = $qrCodes[$assignment->applicant_id] ?? null;
@endphp

<div class="page">

    {{-- ENCABEZADO --}}
    @if($headerImageData)
        <img src="{{ $headerImageData }}" class="header-img" alt="Encabezado Institucional">
    @else
        <div style="text-align:center;font-size:12px;font-weight:bold;border-bottom:2px solid #000;padding-bottom:6px;margin-bottom:6px;">
            {{ strtoupper($institution?->name ?? 'INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO') }}
        </div>
    @endif

    <div class="titulo-doc">
        Hoja de Respuestas &nbsp;—&nbsp; Proceso de Admisión {{ \Carbon\Carbon::now()->year }}
    </div>

    {{-- INSTRUCCIONES --}}
    <div class="instrucciones">
        <strong>INSTRUCCIONES:</strong>
        Rellene el círculo completamente con lapiz.
        Marque solo <strong> UNA</strong> alternativa por pregunta.
        No use corrector ni borradores. Las respuestas enmendadas serán anuladas.
    </div>

    {{-- DATOS + QR --}}
    <div style="position: relative;">
        @if($qr)
            <div class="qr-wrap">
                <img src="{{ $qr }}" alt="QR">
                {{-- <div class="qr-label">{{ $dni }}</div> --}}
            </div>
        @endif

        <table class="datos-table" style="width: {{ $qr ? '87%' : '100%' }};">
            <tr>
                <td class="lbl">Apellidos y Nombres</td>
                <td>{{ $fullName }}</td>
            </tr>
            <tr>
                <td class="lbl">CÓDIGO</td>
                <td><strong>2026{{ $dni }}2807</strong></td>
            </tr>
            <tr>
                <td class="lbl">Programa / Turno</td>
                <td>{{ $peturno }}</td>
            </tr>
            <tr>
                <td class="lbl">Ubicación</td>
                <td>{{ $ubicacion }}</td>
            </tr>
        </table>
    </div>

    {{-- TÍTULO CUADRÍCULA --}}
    <div class="section-title">
        ◆ &nbsp; Cuadrícula de Respuestas &nbsp; ◆
    </div>

    {{-- CUADRÍCULA: 3 COLUMNAS --}}
    <table class="grid-cols">
        <tr>

            {{-- Columna 1: 1–25 --}}
            <td>
                <table class="answer-table">
                    @for($i = 1; $i <= 25; $i++)
                        <tr>
                            <td class="q-num">{{ $i }}.</td>
                            <td>
                                <span class="bubble">A</span>
                                <span class="bubble">B</span>
                                <span class="bubble">C</span>
                                <span class="bubble">D</span>
                                <span class="bubble">E</span>
                            </td>
                        </tr>
                    @endfor
                </table>
            </td>

            <td class="col-sep"></td>

            {{-- Columna 2: 26–50 --}}
            <td>
                <table class="answer-table">
                    @for($i = 26; $i <= 50; $i++)
                        <tr>
                            <td class="q-num">{{ $i }}.</td>
                            <td>
                                <span class="bubble">A</span>
                                <span class="bubble">B</span>
                                <span class="bubble">C</span>
                                <span class="bubble">D</span>
                                <span class="bubble">E</span>
                            </td>
                        </tr>
                    @endfor
                </table>
            </td>

            <td class="col-sep"></td>


            {{-- Columna 3: 51–60 --}}
            <td style="vertical-align: top;">
                <table class="answer-table">
                    @for($i = 51; $i <= 60; $i++)
                        <tr>
                            <td class="q-num">{{ $i }}.</td>
                            <td>
                                <span class="bubble">A</span>
                                <span class="bubble">B</span>
                                <span class="bubble">C</span>
                                <span class="bubble">D</span>
                                <span class="bubble">E</span>
                            </td>
                        </tr>
                    @endfor
                </table>
            </td>

        </tr>
    </table>

    {{-- PIE FIJO --}}
    <div class="pie">
        Impreso: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        &nbsp;·&nbsp; Sistema Educon
        &nbsp;·&nbsp; {{ $institution?->name ?? '' }}
    </div>

</div>
@endforeach

</body>
</html>
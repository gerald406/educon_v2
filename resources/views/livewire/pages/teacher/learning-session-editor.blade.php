<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">
                    Sesión N° {{ $unit->session_number }} — {{ $unit->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $syllabus->teacherAssignment->didacticUnit->name }}
                    · {{ $syllabus->teacherAssignment->academicPeriod->name }}
                </p>
            </div>
            <a href="{{ route('teacher.sessions.list', $syllabus->id) }}"
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Sesiones
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ====================================
                 I. INFORMACIÓN GENERAL (solo lectura)
                 ==================================== --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="bg-teal-700 px-6 py-3">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wide">
                        I. Información General
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $assignment = $syllabus->teacherAssignment;
                        $unit_db    = $assignment->didacticUnit;
                        $module     = $unit_db->module;
                        $plan       = $module->studyPlan;
                        $career     = $plan->career;
                        $period     = $assignment->academicPeriod;
                        $teacher    = $assignment->teacher->user;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Programa de Estudios:</span>
                            <span class="text-gray-900">{{ $career->name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Módulo Formativo:</span>
                            <span class="text-gray-900">{{ $module->name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Unidad Didáctica:</span>
                            <span class="text-gray-900">{{ $unit_db->name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Periodo Lectivo:</span>
                            <span class="text-gray-900">{{ $period->name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Periodo Académico:</span>
                            <span class="text-gray-900">Semestre {{ $unit_db->semester }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Sesión N°:</span>
                            <span class="text-gray-900 font-bold">{{ $unit->session_number }}</span>
                        </div>
                        {{-- DESPUÉS --}}
                        <div class="md:col-span-2 flex gap-2 items-center">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Fechas de Desarrollo:</span>
                            <div class="flex items-center gap-2 flex-1">
                                <x-input
                                    type="text"
                                    wire:model="week_range"
                                    placeholder="Ej: 13/03/2026 al 18/03/2026"
                                    class="flex-1 text-sm" />
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    Formato libre (rango de fechas)
                                </span>
                            </div>
                        </div>
                        <x-input-error for="week_range" class="mt-1" />
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Docente Responsable:</span>
                            <span class="text-gray-900">
                                {{ trim($teacher->name . ' ' . $teacher->lastname) }}
                            </span>
                        </div>
                        <div class="md:col-span-2 flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Indicador de Logro:</span>
                            <span class="text-gray-900">{{ $unit->indicator->description }}</span>
                        </div>
                        <div class="md:col-span-2 flex gap-2">
                            <span class="font-semibold text-gray-600 w-48 shrink-0">Logro de la Sesión:</span>
                            <span class="text-gray-900">{{ $unit->learning_outcome }}</span>
                        </div>
                    </div>

                    {{-- Campos editables de la sección I --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5 pt-4 border-t">
                        <div>
                            <x-label value="Tipo de Actividad *" />
                            <select wire:model="activity_type"
                                    class="mt-1 w-full border-gray-300 focus:border-teal-500
                                           focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                <option value="teorico">Teórico</option>
                                <option value="practico">Práctico</option>
                                <option value="teorico-practico">Teórico-Práctico</option>
                            </select>
                            <x-input-error for="activity_type" class="mt-1" />
                        </div>
                        <div>
                            <x-label value="Competencia Transversal Priorizada" />
                            <x-input type="text" class="w-full mt-1"
                                     wire:model="transversal_competence"
                                     placeholder="Ej: Comunicación efectiva" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====================================
                 II. ACTIVIDADES DE APRENDIZAJE
                 ==================================== --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="bg-teal-700 px-6 py-3">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wide">
                        II. Actividades de Aprendizaje — Secuencia Didáctica
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    @foreach($sequence_activities as $index => $moment)
                        @php
                            $colors = [
                                'inicio'    => 'bg-blue-50 border-blue-200',
                                'desarrollo'=> 'bg-yellow-50 border-yellow-200',
                                'cierre'    => 'bg-green-50 border-green-200',
                            ];
                            $colorClass = $colors[$moment['moment']] ?? 'bg-gray-50 border-gray-200';
                        @endphp
                        <div class="border rounded-lg {{ $colorClass }} p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-bold text-gray-800 text-sm uppercase tracking-wide">
                                    {{ $moment['label'] }}
                                </h4>
                                <span class="text-xs text-gray-500 italic">
                                    {{ $moment['hint'] }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <div class="md:col-span-3">
                                    <x-label value="Actividades del Docente y Estudiante" />
                                    <textarea
                                        wire:model="sequence_activities.{{ $index }}.activity"
                                        rows="4"
                                        placeholder="Describe las actividades para este momento..."
                                        class="mt-1 w-full border-gray-300 focus:border-teal-500
                                               focus:ring-teal-500 rounded-md shadow-sm text-sm resize-none">
                                    </textarea>
                                    <x-input-error for="sequence_activities.{{ $index }}.activity" class="mt-1" />
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <x-label value="Recursos Didácticos" />
                                        <textarea
                                            wire:model="sequence_activities.{{ $index }}.resources"
                                            rows="3"
                                            placeholder="PPT, videos, guías..."
                                            class="mt-1 w-full border-gray-300 focus:border-teal-500
                                                   focus:ring-teal-500 rounded-md shadow-sm text-sm resize-none">
                                        </textarea>
                                    </div>
                                    <div>
                                        <x-label value="Tiempo (min)" />
                                        <x-input type="number" class="w-full mt-1"
                                                 wire:model="sequence_activities.{{ $index }}.time"
                                                 min="1" max="300" />
                                        <x-input-error for="sequence_activities.{{ $index }}.time" class="mt-1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ====================================
                 III. ACTIVIDADES DE EVALUACIÓN
                 ==================================== --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="bg-teal-700 px-6 py-3">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wide">
                        III. Actividades de Evaluación
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <x-label value="Indicador de Logro de la Sesión" />
                        <textarea wire:model="evaluation_criteria" rows="2"
                                  placeholder="Indicador específico que se evaluará en esta sesión..."
                                  class="mt-1 w-full border-gray-300 focus:border-teal-500
                                         focus:ring-teal-500 rounded-md shadow-sm text-sm resize-none">
                        </textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-label value="Técnica de Evaluación" />
                            <x-input type="text" class="w-full mt-1"
                                     wire:model="evaluation_technique"
                                     placeholder="Observación, Prueba escrita..." />
                        </div>
                        <div>
                            <x-label value="Instrumento de Evaluación" />
                            <x-input type="text" class="w-full mt-1"
                                     wire:model="evaluation_instrument"
                                     placeholder="Lista de cotejo, Rúbrica..." />
                        </div>
                        <div>
                            <x-label value="Momento" />
                            <select wire:model="evaluation_moment"
                                    class="mt-1 w-full border-gray-300 focus:border-teal-500
                                           focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                <option value="">-- Seleccionar --</option>
                                <option value="Inicio">Inicio</option>
                                <option value="Proceso">Proceso</option>
                                <option value="Salida">Salida</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====================================
                 IV. BIBLIOGRAFÍA
                 ==================================== --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="bg-teal-700 px-6 py-3">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wide">
                        IV. Bibliografía (APA)
                    </h3>
                </div>
                <div class="p-6">
                    <textarea wire:model="bibliography" rows="3"
                              placeholder="Referencias bibliográficas en formato APA..."
                              class="w-full border-gray-300 focus:border-teal-500
                                     focus:ring-teal-500 rounded-md shadow-sm text-sm resize-none">
                    </textarea>
                </div>
            </div>

            {{-- ====================================
                 BARRA DE ACCIONES
                 ==================================== --}}
            <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @switch($status)
                        @case('pending')
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                                Pendiente
                            </span>
                            @break
                        @case('completed')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                Completada
                            </span>
                            @break
                        @case('executed')
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                Ejecutada
                            </span>
                            @break
                    @endswitch
                </div>

                <div class="flex items-center gap-3">
                    {{-- Guardar borrador --}}
                    <button wire:click="save"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700
                                   text-white text-sm font-semibold rounded-md transition-colors
                                   disabled:opacity-50">
                        Guardar Borrador
                    </button>

                    {{-- Marcar como completada --}}
                    @if($status === 'pending')
                        <button wire:click="saveAndComplete"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700
                                       text-white text-sm font-semibold rounded-md transition-colors
                                       disabled:opacity-50">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Marcar como Completada
                        </button>
                    @endif

                    {{-- PDF --}}
                    @if($status !== 'pending')
                        <a href="{{ route('teacher.sessions.pdf', [$syllabus->id, $unit->id]) }}"
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700
                                  text-white text-sm font-semibold rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116
                                         7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Exportar PDF
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
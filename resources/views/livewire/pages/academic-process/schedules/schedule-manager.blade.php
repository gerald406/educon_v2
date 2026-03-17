<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Horarios
            </h2>  
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- BOTÓN DE EXPORTACIÓN --}}
            @if($activePeriod)
                <x-button wire:click="$set('showExportModal', true)" class="bg-green-600 hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar Horarios
                </x-button>
            @endif
            
            @if(!$activePeriod)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow">
                    <p class="font-bold">No hay periodo académico activo.</p>
                    <p>Active un periodo para gestionar horarios.</p>
                </div>
            @else

            {{-- FILTROS DE SELECCIÓN --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6 border-l-4 border-indigo-500">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div>
                        <x-label value="1. Programa de Estudios" class="mb-1 text-indigo-700 font-bold" />
                        <select wire:model.live="selectedCareerId" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Seleccione --</option>
                            @foreach($careers as $c) 
                                <option value="{{ $c->id }}">{{ $c->name }}</option> 
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <x-label value="2. Unidad Didáctica (Curso)" class="mb-1 text-indigo-700 font-bold" />
                        <x-input type="text" wire:model.live.debounce.300ms="searchUnit" 
                                 placeholder="Escriba para buscar..." 
                                 class="w-full {{ $selectedUnitId ? 'bg-green-50 border-green-500' : '' }}"
                                 :disabled="!$selectedCareerId" />
                        
                        @if(!empty($unitResults))
                            <div class="absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto mt-1">
                                @foreach($unitResults as $u)
                                    <div wire:click="selectUnit({{ $u->id }}, '{{ $u->name }}', {{ $u->semester }})"
                                         class="p-2 hover:bg-indigo-50 cursor-pointer text-sm border-b">
                                        <span class="font-bold">{{ $u->name }}</span>
                                        <span class="text-xs text-gray-500 block">Semestre {{ $u->semester }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div>
                        <x-label value="3. Sección / Docente" class="mb-1 text-indigo-700 font-bold" />
                        <select wire:model.live="selectedAssignmentId" class="w-full border-gray-300 rounded-md shadow-sm" {{ $sectionAssignments->isEmpty() ? 'disabled' : '' }}>
                            <option value="">-- Seleccione --</option>
                            @foreach($sectionAssignments as $sa)
                                <option value="{{ $sa->id }}">
                                    Sección {{ $sa->section }} - {{ $sa->shift->name }} ({{ $sa->teacher->user->lastname }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- GRID DE HORARIOS --}}
            @if($selectedAssignment)
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    
                    {{-- FORMULARIO AGREGAR BLOQUE --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white shadow-xl sm:rounded-lg p-5 sticky top-24">
                            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Agregar Bloque</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <x-label value="Día" />
                                    <select wire:model="day_of_week" class="w-full border-gray-300 rounded-md">
                                        <option value="monday">Lunes</option>
                                        <option value="tuesday">Martes</option>
                                        <option value="wednesday">Miércoles</option>
                                        <option value="thursday">Jueves</option>
                                        <option value="friday">Viernes</option>
                                        <option value="saturday">Sábado</option>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <x-label value="Inicio" />
                                        <x-input type="time" wire:model="start_time" class="w-full" />
                                    </div>
                                    <div>
                                        <x-label value="Fin" />
                                        <x-input type="time" wire:model="end_time" class="w-full" />
                                    </div>
                                </div>
                                <x-input-error for="start_time" />
                                <x-input-error for="end_time" />

                                <div class="flex gap-2 justify-center">
                                    <button wire:click="setPresetTime('morning')" class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded hover:bg-yellow-200">Mañana (08-13)</button>
                                    <button wire:click="setPresetTime('night')" class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded hover:bg-indigo-200">Noche (17-21)</button>
                                </div>

                                <div>
                                    <x-label value="Aula (Opcional)" />
                                    <select wire:model="classroom_resource_id" class="w-full border-gray-300 rounded-md text-sm">
                                        <option value="">-- Sin Aula --</option>
                                        @foreach($classrooms as $cr)
                                            <option value="{{ $cr->id }}">{{ $cr->name }} ({{ $cr->type }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button wire:click="addSchedule" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                    + Agregar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- GRID SEMANAL --}}
                    <div class="lg:col-span-3">
                        <div class="bg-white shadow-xl sm:rounded-lg p-5 overflow-x-auto">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-gray-800">
                                    Horario Semanal
                                </h3>
                                <div class="flex gap-2 text-xs">
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-800">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-1"></span> Mañana
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-purple-100 text-purple-800">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-1"></span> Noche
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-7 gap-2 min-w-[900px]">
                                {{-- COLUMNA DE HORAS --}}
                                <div class="space-y-[38px] pt-8">
                                    <div class="text-xs font-semibold text-gray-600 text-center bg-gray-50 p-1 rounded">Hora</div>
                                    @for($h = 7; $h <= 21; $h++)
                                        <div class="text-xs text-gray-500 text-center py-1">
                                            {{ sprintf('%02d:00', $h) }}
                                        </div>
                                    @endfor
                                </div>

                                {{-- DÍAS DE LA SEMANA --}}
                                @foreach(['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado'] as $dayKey => $dayLabel)
                                    <div>
                                        {{-- Header del día --}}
                                        <div class="text-center font-bold bg-gradient-to-r from-indigo-100 to-blue-100 p-2 rounded text-sm text-gray-700 mb-2 shadow-sm">
                                            {{ $dayLabel }}
                                        </div>

                                        {{-- Contenedor de bloques con altura proporcional --}}
                                        <div class="relative min-h-[560px] bg-gray-50 p-2 rounded border border-gray-200">
                                            {{-- Líneas guía de horas (fondo sutil) --}}
                                            <div class="absolute inset-0 grid grid-rows-15 pointer-events-none">
                                                @for($h = 7; $h <= 21; $h++)
                                                    <div class="border-t border-gray-200 border-dashed opacity-30"></div>
                                                @endfor
                                            </div>

                                            {{-- Bloques de horario --}}
                                            @php
                                                $daySchedules = $currentSchedules->where('day_of_week', $dayKey)->sortBy('start_time');
                                            @endphp

                                            @foreach($daySchedules as $sched)
                                                @php
                                                    // Determinar turno por hora
                                                    $startHour = (int)$sched->start_time->format('H');
                                                    $isMorning = $startHour >= 7 && $startHour < 14;
                                                    $shiftClass = $isMorning 
                                                        ? 'border-blue-500 bg-blue-50 text-blue-900' 
                                                        : 'border-purple-500 bg-purple-50 text-purple-900';
                                                    
                                                    // Calcular posición vertical (proporcional a la hora)
                                                    $topPercent = (($startHour - 7) / 14) * 100;
                                                    $duration = $sched->start_time->diffInMinutes($sched->end_time);
                                                    $heightPercent = ($duration / 60 / 14) * 100;
                                                @endphp

                                                <div class="relative z-10 border-l-4 {{ $shiftClass }} shadow-sm p-2 rounded mb-2 group hover:shadow-md hover:scale-105 transition-all duration-200"
                                                    style="margin-top: {{ $topPercent }}%;">
                                                    
                                                    {{-- Horario --}}
                                                    <div class="text-sm font-bold flex items-center">
                                                        <svg class="w-3 h-3 mr-1 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ $sched->start_time->format('H:i') }} - {{ $sched->end_time->format('H:i') }}
                                                    </div>
                                                    
                                                    {{-- Aula --}}
                                                    <div class="text-xs opacity-75 mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        {{ $sched->classroomResource->name ?? 'Sin Aula' }}
                                                    </div>

                                                    {{-- Badge de turno --}}
                                                    <div class="text-xs font-semibold mt-1 opacity-60">
                                                        {{ $isMorning ? '☀️ Mañana' : '🌙 Noche' }}
                                                    </div>

                                                    {{-- Botón eliminar (visible al hover) --}}
                                                    <button wire:click="deleteSchedule({{ $sched->id }})" 
                                                            wire:confirm="¿Eliminar este bloque de horario?"
                                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:bg-red-600">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                            
                                            {{-- Estado vacío --}}
                                            @if($daySchedules->isEmpty())
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <div class="text-center text-gray-300">
                                                        <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <p class="text-xs italic">Sin horarios</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-lg shadow border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Seleccione un curso y sección</h3>
                    <p class="mt-1 text-sm text-gray-500">Use los filtros superiores para comenzar a programar.</p>
                </div>
            @endif

            @endif {{-- FIN DEL IF DE activePeriod --}}
        </div>
    </div>

    {{-- MODAL DE EXPORTACIÓN --}}
    <x-dialog-modal wire:model.live="showExportModal" maxWidth="2xl">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exportar Horarios
            </div>
        </x-slot>
        
        <x-slot name="content">
            <div class="space-y-6">
                
                {{-- SELECTOR DE TIPO DE REPORTE --}}
                <div>
                    <x-label value="Tipo de Reporte" class="mb-3 text-lg font-bold text-gray-800" />
                    <div class="grid grid-cols-2 gap-4">
                        
                        {{-- OPCIÓN 1: POR DOCENTE --}}
                        <label class="relative flex flex-col cursor-pointer rounded-lg border-2 p-5 hover:border-green-500 transition {{ $exportType === 'teacher' ? 'border-green-600 bg-green-50' : 'border-gray-300 bg-white' }}">
                            <input type="radio" wire:model.live="exportType" value="teacher" class="sr-only">
                            <div class="flex items-center mb-3">
                                <div class="text-4xl mr-3">👨‍🏫</div>
                                <div>
                                    <p class="text-base font-bold text-gray-900">Por Docente</p>
                                    <p class="text-xs text-gray-600">Carga horaria individual</p>
                                </div>
                            </div>
                            @if($exportType === 'teacher')
                                <div class="absolute top-3 right-3">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </label>

                        {{-- OPCIÓN 2: CONSOLIDADO POR PROGRAMA --}}
                        <label class="relative flex flex-col cursor-pointer rounded-lg border-2 p-5 hover:border-purple-500 transition {{ $exportType === 'career' ? 'border-purple-600 bg-purple-50' : 'border-gray-300 bg-white' }}">
                            <input type="radio" wire:model.live="exportType" value="career" class="sr-only">
                            <div class="flex items-center mb-3">
                                <div class="text-4xl mr-3">📚</div>
                                <div>
                                    <p class="text-base font-bold text-gray-900">Consolidado</p>
                                    <p class="text-xs text-gray-600">Por programa y turno</p>
                                </div>
                            </div>
                            @if($exportType === 'career')
                                <div class="absolute top-3 right-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </label>
                    </div>
                </div>

                {{-- FILTROS SEGÚN TIPO --}}
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                    
                    @if($exportType === 'teacher')
                        {{-- FILTROS PARA DOCENTE --}}
                        <div>
                            <x-label value="Seleccionar Docente" class="mb-2 font-bold text-gray-700" />
                            <select wire:model.live="exportTeacherId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">-- Seleccione un docente --</option>
                                @foreach($teachers->sortBy('user.lastname') as $teacher)
                                    <option value="{{ $teacher->id }}">
                                        {{ $teacher->user->lastname }}, {{ $teacher->user->name }} - DNI: {{ $teacher->user->document_number }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="exportTeacherId" class="mt-1" />
                            
                            @if($exportTeacherId)
                                <div class="mt-3 bg-green-100 border border-green-300 rounded p-3 text-sm text-green-800">
                                    <strong>✓ Docente seleccionado:</strong> 
                                    {{ $teachers->find($exportTeacherId)?->user->name }} 
                                    {{ $teachers->find($exportTeacherId)?->user->lastname }}
                                </div>
                            @endif
                        </div>

                    @elseif($exportType === 'career')
                        {{-- FILTROS PARA PROGRAMA --}}
                        <div class="space-y-4">
                            <div>
                                <x-label value="Programa de Estudios" class="mb-2 font-bold text-gray-700" />
                                <select wire:model.live="exportCareerId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="">-- Seleccione programa --</option>
                                    @foreach($careers as $career)
                                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error for="exportCareerId" class="mt-1" />
                            </div>

                            @if($exportCareerId)
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-label value="Semestre" class="mb-2 font-bold text-gray-700" />
                                        <select wire:model.live="exportSemester" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            <option value="">-- Todos los semestres --</option>
                                            @for($i = 1; $i <= 6; $i++)
                                                <option value="{{ $i }}">Semestre {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div>
                                        <x-label value="Turno" class="mb-2 font-bold text-gray-700" />
                                        <select wire:model.live="exportShiftId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            <option value="">-- Todos los turnos --</option>
                                            @foreach($shifts as $shift)
                                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if($exportCareerId)
                                    <div class="bg-purple-100 border border-purple-300 rounded p-3 text-sm text-purple-800">
                                        <strong>✓ Programa seleccionado:</strong><br>
                                        {{ $careers->find($exportCareerId)?->name }}
                                        @if($exportSemester)
                                            - Semestre {{ $exportSemester }}
                                        @endif
                                        @if($exportShiftId)
                                            - {{ $shifts->find($exportShiftId)?->name }}
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

                {{-- SELECTOR DE FORMATO --}}
                <div>
                    <x-label value="Formato de Exportación" class="mb-2 font-bold text-gray-700" />
                    <div class="flex gap-3">
                        <label class="flex items-center px-4 py-3 border-2 rounded-lg cursor-pointer {{ $exportFormat === 'pdf' ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }} hover:border-red-400 transition">
                            <input type="radio" wire:model.live="exportFormat" value="pdf" class="mr-2">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                            <span class="font-semibold">PDF</span>
                        </label>

                        <label class="flex items-center px-4 py-3 border-2 rounded-lg cursor-pointer {{ $exportFormat === 'excel' ? 'border-green-500 bg-green-50' : 'border-gray-300 bg-white' }} hover:border-green-400 transition">
                            <input type="radio" wire:model.live="exportFormat" value="excel" class="mr-2">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                            </svg>
                            <span class="font-semibold">Excel</span>
                        </label>
                    </div>
                </div>

            </div>
        </x-slot>
        
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showExportModal', false)">
                Cancelar
            </x-secondary-button>
            
            <x-button 
                class="ml-3 {{ !$this->canExport ? 'opacity-50 cursor-not-allowed' : '' }}" 
                wire:click="generateReport"
                :disabled="!$this->canExport">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Generar Reporte
            </x-button>
        </x-slot>
    </x-dialog-modal>

    {{-- @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-in-new-tab', (event) => {
                const url = event.url || event[0]?.url || event;
                
                if (url) {
                    const newWindow = window.open(url, '_blank');
                    
                    if (!newWindow || newWindow.closed || typeof newWindow.closed === 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Popup bloqueado',
                            text: 'Su navegador bloqueó la ventana emergente.',
                            confirmButtonText: 'Abrir en esta pestaña',
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url;
                            }
                        });
                    }
                }
            });
        });
    </script>
    @endpush --}}
    {{-- @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Listener alternativo si el método js() falla
            Livewire.on('open-report', (event) => {
                const url = event.url || event[0]?.url;
                if (url) {
                    window.open(url, '_blank');
                }
            });
        });
    </script>
    @endpush --}}
</div>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Proceso de Matrícula
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                    @if($step == 'loading')
                        <p class="text-center text-gray-500">Cargando información de matrícula...</p>
                    @elseif($step == 'error')
                        <p class="text-center text-red-600">No se pudo cargar el proceso de matrícula. (Sin periodo activo o sin perfil de estudiante).</p>
                    @elseif($step == 'payment')
                        <div class="text-center text-red-700 p-10 border-red-200 border rounded-md bg-red-50">
                            <h3 class="text-xl font-semibold">Matrícula Pendiente de Pago</h3>
                            <p class="mt-2">Se ha detectado una deuda de matrícula pendiente para el periodo <strong>{{ $activePeriod->name }}</strong>.</p>
                            <p class="mt-4">Por favor, acérquese a Tesorería para regularizar su pago y poder continuar con su matrícula.</p>
                        </div>
                    @endif


                    @if($step == 'confirmation')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Cursos a Matricular (Semestre {{ $student->current_semester }})
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    Estos son los cursos asignados para tu matrícula. No se pueden modificar.
                                </p>
                                <div class="space-y-4">
                                    @forelse ($coursesToEnroll as $course)
                                        <div @class([
                                            'p-4 rounded-md border flex justify-between items-start',
                                            'bg-gray-50' => $course->validation_status == 'available',
                                            'border-red-400 bg-red-50' => $course->validation_status != 'available',
                                        ])>
                                            <div>
                                                <strong class="text-gray-900">{{ $course->didacticUnit->name }}</strong>
                                                <span class="text-sm text-gray-600">(Sec. {{ $course->section }} - {{ $course->shift->name }})</span>
                                                
                                                @if($course->validation_message)
                                                    <p class="text-sm ml-0 mt-1 font-semibold text-red-600">
                                                        Error: {{ $course->validation_message }}
                                                    </p>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-700">
                                                Vacantes: {{ $course->current_enrolled }}/{{ $course->max_capacity }}
                                            </span>
                                        </div>
                                    @empty
                                        <p class="text-gray-500">No se encontraron cursos obligatorios para tu semestre en este periodo.</p>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="md:col-span-1">
                                <div class="p-4 border rounded-md sticky top-24">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Resumen de Matrícula</h3>
                                    <p class="text-sm">Total de Cursos: <strong>{{ $coursesToEnroll->count() }}</strong></p>
                                    
                                    <h4 class="font-semibold mt-4 mb-2">Horario Resultante:</h4>
                                    <div class="space-y-2 max-h-60 overflow-y-auto">
                                        @forelse($confirmedSchedules as $schedule)
                                            @if($schedule)
                                                <div class="text-sm p-2 bg-gray-100 rounded">
                                                    <strong>{{ ucfirst($schedule->day_of_week) }}</strong>
                                                    {{ $schedule->start_time->format('h:i A') }} - {{ $schedule->end_time->format('h:i A') }}
                                                    <span class="text-gray-600">({{ $schedule->classroomResource->name ?? 'N/A' }})</span>
                                                </div>
                                            @endif
                                        @empty
                                            <p class="text-sm text-gray-500">No hay horario definido.</p>
                                        @endforelse
                                    </div>

                                    @if($hasConflicts)
                                        <p class="mt-4 text-center p-2 bg-red-100 text-red-700 rounded-md text-sm">
                                            ¡Error! Se detectaron conflictos de horario o falta de vacantes. Contacte a secretaría.
                                        </p>
                                    @elseif($coursesToEnroll->count() > 0)
                                        <x-button class="w-full justify-center mt-6" wire:click="confirmEnrollment" wire:loading.attr="disabled">
                                            Confirmar Matrícula
                                        </x-button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($step == 'confirmed')
                         <div class="p-4 bg-green-50 border border-green-200 rounded-md mb-6">
                            <h3 class="text-lg font-semibold text-green-800">
                                ¡Matrícula Confirmada!
                            </h3>
                            <p class="text-sm text-green-700">
                                Ya te encuentras matriculado en el periodo <strong>{{ $activePeriod->name }}</strong>.
                            </p>
                        </div>
                        <div class="mb-6">
                            <x-button wire:click="downloadEnrollmentForm">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 17a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-4V3a1 1 0 00-1-1H9a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10zm0 2V7h4v2h6V7h4v12H6zM10 9a1 1 0 112 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" /></svg>
                                Descargar Ficha de Matrícula (PDF)
                            </x-button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">Cursos Inscritos</h4>
                                <div class="space-y-3">
                                    @foreach($coursesToEnroll as $assignment) <div class="p-4 rounded-md border">
                                            <strong class="text-gray-900">{{ $assignment->didacticUnit->name }}</strong>
                                            <p class="text-sm text-gray-600">
                                                Docente: {{ $assignment->teacher->user->name }} <br>
                                                Turno: {{ $assignment->shift->name }} (Sección {{ $assignment->section }})
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="md:col-span-1">
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">Mi Horario</h4>
                                <div class="space-y-2">
                                    @forelse($confirmedSchedules as $schedule)
                                        @if($schedule)
                                            <div class="text-sm p-2 bg-gray-100 rounded">
                                                <strong>{{ ucfirst($schedule->day_of_week) }}</strong>
                                                {{ $schedule->start_time->format('h:i A') }} - {{ $schedule->end_time->format('h:i A') }}
                                                <span class="text-gray-600">({{ $schedule->classroomResource->name ?? 'N/A' }})</span>
                                            </div>
                                        @endif
                                    @empty
                                        <p class="text-sm text-gray-500">No se generó horario.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
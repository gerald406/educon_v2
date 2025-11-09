<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de Asistencia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="md:col-span-1">
                                <x-label for="selectedAssignmentId" value="Seleccione la Sección (Curso)" />
                                <select id="selectedAssignmentId" wire:model.live="selectedAssignmentId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Seleccione un curso --</option>
                                    @foreach($assignments as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="md:col-span-1">
                                <x-label for="selectedScheduleId" value="Bloque de Horario" />
                                <select id="selectedScheduleId" wire:model.live="selectedScheduleId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    @if($schedules->isEmpty()) disabled @endif>
                                    <option value="">-- Seleccione un bloque --</option>
                                    @foreach($schedules as $schedule)
                                        <option value="{{ $schedule->id }}">
                                            {{ ucfirst($schedule->day_of_week) }} ({{ $schedule->start_time->format('h:i A') }} - {{ $schedule->end_time->format('h:i A') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="md:col-span-1">
                                <x-label for="selectedDate" value="Fecha de Clase" />
                                <x-input id="selectedDate" type="date" class="mt-1 block w-full" wire:model.live="selectedDate" />
                            </div>
                        </div>

                        @if($selectedAssignmentId && $selectedScheduleId && $selectedDate)
                            <div class="overflow-x-auto mt-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium sticky left-0 bg-gray-50 z-10">N°</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium sticky left-12 bg-gray-50 z-10">Estudiante</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium">Asistencia</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($registrations as $index => $registration)
                                            <tr>
                                                <td class="px-6 py-4 sticky left-0 bg-white z-10">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 sticky left-12 bg-white z-10 whitespace-nowrap">
                                                    {{ $registration->enrollment->student->user->name ?? 'Estudiante no encontrado' }}
                                                </td>
                                                
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex justify-center space-x-4">
                                                        <label class="flex items-center">
                                                            <input type="radio" wire:model="attendances.{{ $registration->id }}" value="present" class="form-radio text-green-600">
                                                            <span class="ml-2 text-sm text-green-700">Presente</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="radio" wire:model="attendances.{{ $registration->id }}" value="absent" class="form-radio text-red-600">
                                                            <span class="ml-2 text-sm text-red-700">Ausente</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="radio" wire:model="attendances.{{ $registration->id }}" value="late" class="form-radio text-yellow-600">
                                                            <span class="ml-2 text-sm text-yellow-700">Tarde</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="radio" wire:model="attendances.{{ $registration->id }}" value="justified" class="form-radio text-blue-600">
                                                            <span class="ml-2 text-sm text-blue-700">Justificado</span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                                    No hay estudiantes matriculados en esta sección.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="flex justify-end mt-6 border-t pt-6">
                                <x-button wire:click="saveAttendances" wire:loading.attr="disabled">
                                    {{ $isAttendanceTaken ? 'Actualizar Asistencia' : 'Guardar Asistencia' }}
                                </x-button>
                            </div>

                        @else
                            <p class="text-center text-gray-500 p-4">
                                Por favor, seleccione una sección, un bloque de horario y una fecha para registrar la asistencia.
                            </p>
                        @endif
                        
                    @else
                        <div class="text-center text-red-500 p-10 border rounded-md">
                            <h3 class="mt-2 text-sm font-medium text-red-900">No hay un Periodo Académico Activo</h3>
                            <p class="mt-1 text-sm text-red-700">
                                No se puede registrar asistencia sin un periodo activo.
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Horarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="mb-4 p-4 bg-gray-50 rounded-md grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div>
                                <x-label for="selectedCareerId" value="Programa (Carrera)" />
                                <select id="selectedCareerId" wire:model.live="selectedCareerId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Seleccione carrera --</option>
                                    @foreach($careers as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label for="selectedUnitId" value="Unidad Didáctica (Curso)" />
                                <select id="selectedUnitId" wire:model.live="selectedUnitId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    @if(empty($availableUnits)) disabled @endif>
                                    <option value="">-- Seleccione unidad --</option>
                                    @foreach($availableUnits as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label for="selectedAssignmentId" value="Sección / Docente" />
                                <select id="selectedAssignmentId" wire:model.live="selectedAssignmentId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    @if(empty($availableAssignments)) disabled @endif>
                                    <option value="">-- Seleccione sección --</option>
                                    @foreach($availableAssignments as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if ($selectedAssignment)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-1">
                                    <div class="p-4 border rounded-md sticky top-24">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Añadir Bloque</h3>
                                        @if($teacherPreparationDay)
                                            <p class="text-sm text-yellow-700 bg-yellow-100 p-2 rounded-md mb-4">
                                                Día de preparación del docente: <strong>{{ ucfirst($teacherPreparationDay) }}</strong>
                                            </p>
                                        @endif
                                        <form wire:submit.prevent="addSchedule" class="space-y-4">
                                            <div>
                                                <x-label for="day_of_week" value="Día de la Semana" />
                                                <select id="day_of_week" wire:model="day_of_week" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                    <option value="monday">Lunes</option>
                                                    <option value="tuesday">Martes</option>
                                                    <option value="wednesday">Miércoles</option>
                                                    <option value="thursday">Jueves</option>
                                                    <option value="friday">Viernes</option>
                                                    <option value="saturday">Sábado</option>
                                                </select>
                                                <x-input-error for="day_of_week" class="mt-1" />
                                            </div>
                                            <div>
                                                <x-label for="start_time" value="Hora de Inicio" />
                                                <x-input id="start_time" type="time" class="mt-1 block w-full" wire:model.blur="start_time" />
                                                <x-input-error for="start_time" class="mt-1" />
                                            </div>
                                            <div>
                                                <x-label for="end_time" value="Hora de Fin" />
                                                <x-input id="end_time" type="time" class="mt-1 block w-full" wire:model.blur="end_time" />
                                                <x-input-error for="end_time" class="mt-1" />
                                            </div>
                                            <div>
                                                <x-label for="classroom_resource_id" value="Aula (Opcional)" />
                                                <select id="classroom_resource_id" wire:model="classroom_resource_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                    <option value="">-- Sin aula asignada --</option>
                                                    @foreach($availableClassrooms as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error for="classroom_resource_id" class="mt-1" />
                                            </div>
                                            <x-button type="submit" class="w-full justify-center">
                                                Añadir Horario
                                            </x-button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        Horario Programado ({{ $currentSchedules->count() }} bloques)
                                    </h3>
                                    <div class="overflow-x-auto border rounded-md">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium">Día</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium">Hora Inicio</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium">Hora Fin</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium">Aula</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse ($currentSchedules as $schedule)
                                                    <tr>
                                                        <td class="px-4 py-3">{{ ucfirst($schedule->day_of_week) }}</td>
                                                        <td class="px-4 py-3">{{ $schedule->start_time->format('h:i A') }}</td>
                                                        <td class="px-4 py-3">{{ $schedule->end_time->format('h:i A') }}</td>
                                                        <td class="px-4 py-3">{{ $schedule->classroomResource->name ?? 'N/A' }}</td>
                                                        <td class="px-4 py-3 text-right">
                                                            <x-danger-button wire:click="confirmDelete({{ $schedule->id }})">
                                                                Quitar
                                                            </x-danger-button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                                            Aún no se han asignado horarios para esta sección.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-gray-500 p-10 border rounded-md">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Seleccione una Sección</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Utilice los filtros de arriba para seleccionar el curso y la sección a la que desea asignar un horario.
                                </p>
                            </div>
                        @endif

                    @else
                        <div class="text-center text-red-500 p-10 border rounded-md">
                            <h3 class="mt-2 text-sm font-medium text-red-900">No hay un Periodo Académico Activo</h3>
                            <p class="mt-1 text-sm text-red-700">
                                No se puede gestionar horarios sin un periodo activo. Vaya a "Periodos Académicos" y active uno.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
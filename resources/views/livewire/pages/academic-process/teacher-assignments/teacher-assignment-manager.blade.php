<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Carga Académica (Secciones)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="mb-4 p-4 bg-blue-100 border border-blue-200 rounded-md">
                            <h3 class="font-semibold text-lg text-blue-800">
                                Gestionando Carga para el Periodo Activo: {{ $activePeriod->name }}
                            </h3>
                            <p class="text-sm text-blue-700">
                                Todas las secciones creadas se asociarán a este periodo.
                            </p>
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por docente, curso o sección..." class="w-1/2" />
                            <x-button wire:click="openCreateModal">
                                Asignar Nuevo Curso
                            </x-button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Unidad Didáctica (Curso)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Horas Sem.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Docente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Turno</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Sección</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Vac./Mat.</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($assignments as $assignment)
                                        <tr>
                                            <td class="px-6 py-4">{{ $assignment->didacticUnit->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $assignment->didacticUnit->weekly_hours ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $assignment->teacher->user->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $assignment->shift->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $assignment->section }}</td>
                                            <td class="px-6 py-4">{{ $assignment->current_enrolled }} / {{ $assignment->max_capacity }}</td>
                                            
                                            <td class="px-6 py-4 text-right space-x-1">
                                                <x-button 
                                                wire:click="exportEnrolledStudents({{ $assignment->id }})"
                                                title="Descargar Nómina de Estudiantes"
                                                class="bg-danger hover:bg-blue-700">                                                    Nómina
                                                </x-button>
                                                
                                                <x-button wire:click="openEditModal({{ $assignment->id }})">Editar</x-button>
                                                <x-danger-button wire:click="confirmDelete({{ $assignment->id }})">Eliminar</x-danger-button>
                                            </td>
                                        </tr>
                                    @empty
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">{{ $assignments->links() }}</div>

                    @else
                        <div class="text-center text-red-500 p-10 border rounded-md">
                            <svg class="mx-auto h-12 w-12 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                            <h3 class="mt-2 text-sm font-medium text-red-900">No hay un Periodo Académico Activo</h3>
                            <p class="mt-1 text-sm text-red-700">
                                Por favor, vaya a "Procesos Académicos" > "Periodos Académicos" y asegúrese de que un periodo esté marcado como "Activo".
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingAssignment ? 'Editar Asignación de Carga' : 'Crear Nueva Asignación de Carga' }}
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-600 mb-4">
                Asignando para el periodo: <span class="font-semibold">{{ $activePeriod?->name }}</span>
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="didactic_unit_id" value="Unidad Didáctica (Curso)" />
                    <select id="didactic_unit_id" wire:model="didactic_unit_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione un curso --</option>
                        @foreach($didacticUnits as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="didactic_unit_id" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-label for="teacher_id" value="Docente" />
                    <select id="teacher_id" wire:model="teacher_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione un docente --</option>
                        @foreach($teachers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="teacher_id" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="shift_id" value="Turno" />
                    <select id="shift_id" wire:model="shift_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione un turno --</option>
                        @foreach($shifts as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="shift_id" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="section" value="Sección" />
                    <x-input id="section" type="text" class="mt-1 block w-full" wire:model.blur="section" placeholder="Ej. A" />
                    <x-input-error for="section" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="max_capacity" value="Vacantes" />
                    <x-input id="max_capacity" type="number" class="mt-1 block w-full" wire:model.blur="max_capacity" />
                    <x-input-error for="max_capacity" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="active">Activa</option>
                        <option value="suspended">Suspendida</option>
                        <option value="completed">Completada</option>
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                Guardar
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
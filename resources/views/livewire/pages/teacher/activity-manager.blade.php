<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Actividades (Tareas, Proyectos)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="mb-4">
                            <x-label for="selectedAssignmentId" value="Seleccione la Sección (Curso) para gestionar actividades" />
                            <select id="selectedAssignmentId" wire:model.live="selectedAssignmentId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Seleccione un curso asignado --</option>
                                @foreach($assignments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($selectedAssignmentId)
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-medium text-gray-900">
                                    Actividades Programadas
                                </h3>
                                <x-button wire:click="openCreateModal">
                                    Crear Nueva Actividad
                                </x-button>
                            </div>

                            <div class="space-y-4">
                                @forelse ($activities as $activity)
                                    <div class="p-4 border rounded-md flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-lg text-gray-900">{{ $activity->title }}</h4>
                                            <span class="text-sm text-gray-600">
                                                Fecha Límite: {{ $activity->due_date->format('d/m/Y h:i A') }}
                                            </span>
                                            <p class="text-xs text-gray-500">Tipo: {{ $activity->activity_type }} | Peso: {{ $activity->weight }}%</p>
                                            
                                            @if($activity->activity_file_url)
                                                <a href="{{ asset('storage/' . $activity->activity_file_url) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900">
                                                    Ver Archivo Adjunto
                                                </a>
                                            @endif
                                            </div>
                                        <div class="flex-shrink-0 space-x-2">
                                            <x-button wire:click="openEditModal({{ $activity->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $activity->id }})">Eliminar</x-danger-button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center">No hay actividades creadas para esta sección.</p>
                                @endforelse
                            </div>
                            <div class="mt-4">{{ $activities->links() }}</div>
                        @else
                            <p class="text-center text-gray-500">Seleccione un curso para ver sus actividades.</p>
                        @endif
                        
                    @else
                        <p class="text-center text-red-500">No hay un periodo académico activo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingActivity ? 'Editar Actividad' : 'Crear Nueva Actividad' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="title" value="Título de la Actividad" />
                    <x-input id="title" type="text" class="mt-1 block w-full" wire:model.blur="title" />
                    <x-input-error for="title" class="mt-2" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="description" value="Descripción / Instrucciones (Opcional)" />
                    <textarea id="description" wire:model.blur="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="activity_type" value="Tipo de Actividad" />
                    <select id="activity_type" wire:model="activity_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="practice">Práctica</option>
                        <option value="project">Proyecto</option>
                        <option value="research">Investigación</option>
                        <option value="presentation">Exposición</option>
                        <option value="exam">Examen</option>
                        <option value="workshop">Taller</option>
                        <option value="laboratory">Laboratorio</option>
                    </select>
                    <x-input-error for="activity_type" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="weight" value="Peso sobre la nota (0-100)" />
                    <x-input id="weight" type="number" step="1" class="mt-1 block w-full" wire:model.blur="weight" />
                    <x-input-error for="weight" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="assigned_date" value="Fecha de Asignación" />
                    <x-input id="assigned_date" type="datetime-local" class="mt-1 block w-full" wire:model.blur="assigned_date" />
                    <x-input-error for="assigned_date" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="due_date" value="Fecha Límite de Entrega" />
                    <x-input id="due_date" type="datetime-local" class="mt-1 block w-full" wire:model.blur="due_date" />
                    <x-input-error for="due_date" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-label for="fileUpload" value="Adjuntar Archivo (Opcional)" />
                    <x-input id="fileUpload" type="file" class="mt-1 block w-full" wire:model="fileUpload" />
                    <x-input-error for="fileUpload" class="mt-2" />
                    @if ($activity_file_url && !$fileUpload)
                        <p class="text-sm text-gray-600 mt-2">Archivo actual: 
                            <a href="{{ asset('storage/' . $activity_file_url) }}" target="_blank" class="text-indigo-600">Ver archivo</a>
                        </p>
                    @endif
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                Guardar Actividad
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
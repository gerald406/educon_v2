<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Tutorías
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por estudiante o docente..." class="w-1/2" />
                        <x-button wire:click="openCreateModal">
                            Registrar Nueva Sesión
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estudiante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Docente (Tutor)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Sesión</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($tutorings as $item)
                                    <tr>
                                        <td class="px-6 py-4">{{ $item->student->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $item->teacher->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $item->tutoring_date->format('d/m/Y h:i A') }}</td>
                                        <td class="px-6 py-4">{{ ucfirst($item->tutoring_type) }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-blue-100 text-blue-800' => $item->status == 'scheduled',
                                                'bg-green-100 text-green-800' => $item->status == 'completed',
                                                'bg-gray-100 text-gray-800' => $item->status == 'cancelled',
                                            ])>
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $item->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $item->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron sesiones de tutoría.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $tutorings->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingTutoring ? 'Editar Sesión de Tutoría' : 'Registrar Nueva Sesión' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="studentSearch" value="Buscar Estudiante" />
                    <x-input id="studentSearch" type="text" class="mt-1 block w-full" 
                             wire:model.live.debounce.300ms="studentSearch" 
                             placeholder="Buscar por nombre o código..." 
                             autocomplete="off" />
                    @if($students->count() > 0)
                        <div class="mt-1 border rounded-md max-h-32 overflow-y-auto">
                            @foreach($students as $student)
                                <div class="p-2 hover:bg-gray-100 cursor-pointer"
                                     wire:click="selectStudent({{ $student->id }})">
                                    {{ $student->user->name }} ({{ $student->code }})
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input type="hidden" wire:model="student_id">
                    <x-input-error for="student_id" class="mt-2" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="teacher_id" value="Docente (Tutor)" />
                    <select id="teacher_id" wire:model="teacher_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione un docente --</option>
                        @foreach($teachers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="teacher_id" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="tutoring_date" value="Fecha y Hora de Sesión" />
                    <x-input id="tutoring_date" type="datetime-local" class="mt-1 block w-full" wire:model.blur="tutoring_date" />
                    <x-input-error for="tutoring_date" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="tutoring_type" value="Tipo de Tutoría" />
                    <select id="tutoring_type" wire:model="tutoring_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="academic">Académica</option>
                        <option value="personal">Personal</option>
                        <option value="vocational">Vocacional</option>
                        <option value="group">Grupal</option>
                    </select>
                    <x-input-error for="tutoring_type" class="mt-2" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="reason" value="Motivo de la Sesión" />
                    <textarea id="reason" wire:model.blur="reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <x-input-error for="reason" class="mt-2" />
                </div>
                
                @if($editingTutoring)
                    <div class="col-span-2">
                        <x-label for="session_development" value="Desarrollo de la Sesión (Opcional)" />
                        <textarea id="session_development" wire:model.blur="session_development" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="col-span-2">
                        <x-label for="agreements_commitments" value="Acuerdos y Compromisos (Opcional)" />
                        <textarea id="agreements_commitments" wire:model.blur="agreements_commitments" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="col-span-1">
                        <x-label for="status" value="Estado" />
                        <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="scheduled">Programada</option>
                            <option value="completed">Completada</option>
                            <option value="cancelled">Cancelada</option>
                            <option value="rescheduled">Reprogramada</option>
                        </select>
                    </div>
                    <div class="col-span-1 flex items-end pb-2">
                        <label class="flex items-center">
                            <x-checkbox wire:model="follow_up_required" />
                            <span class="ms-2 text-sm text-gray-600">¿Requiere Seguimiento?</span>
                        </label>
                    </div>
                @endif
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
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Procesos de Titulación
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por estudiante o título..." />
                        <x-button wire:click="openCreateModal">
                            Registrar Nuevo Proceso
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estudiante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Título del Proyecto/Tesis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Modalidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Asesor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($processes as $item)
                                    <tr>
                                        <td class="px-6 py-4">{{ $item->student->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $item->title }}</td>
                                        <td class="px-6 py-4">{{ $item->process_type }}</td>
                                        <td class="px-6 py-4">{{ $item->advisor->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $item->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $item->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron procesos de titulación.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $processes->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingProcess ? 'Editar Proceso de Titulación' : 'Registrar Nuevo Proceso' }}
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
                    @endif <input type="hidden" wire:model="student_id">
                    <x-input-error for="student_id" class="mt-2" />
                </div>
                
                <div class="col-span-2 border-t mt-2"></div>

                <div class="col-span-2">
                    <x-label for="title" value="Título del Proyecto o Tesis" />
                    <textarea id="title" wire:model.blur="title" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    <x-input-error for="title" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="process_type" value="Modalidad" />
                    <select id="process_type" wire:model="process_type" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="thesis">Tesis</option>
                        <option value="project">Proyecto</option>
                        <option value="sufficiency_exam">Examen de Suficiencia</option>
                    </select>
                    <x-input-error for="process_type" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="advisor_id" value="Asesor (Docente)" />
                    <select id="advisor_id" wire:model="advisor_id" class_="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sin Asesor --</option>
                        @foreach($teachers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="advisor_id" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="proposal_date" value="Fecha de Propuesta" />
                    <x-input id="proposal_date" type="date" class="mt-1 block w-full" wire:model.blur="proposal_date" />
                    <x-input-error for="proposal_date" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="defense_date" value="Fecha Sustentación (Opcional)" />
                    <x-input id="defense_date" type="date" class="mt-1 block w-full" wire:model.blur="defense_date" />
                    <x-input-error for="defense_date" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="final_grade" value="Nota (0-20, Opcional)" />
                    <x-input id="final_grade" type="number" step="0.5" class="mt-1 block w-full" wire:model.blur="final_grade" />
                    <x-input-error for="final_grade" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado del Proceso" />
                    <select id="status" wire:model="status" class_S="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="proposal">Propuesta</option>
                        <option value="in_development">En Desarrollo</option>
                        <option value="review">En Revisión</option>
                        <option value="defended">Sustentado</option>
                        <option value="approved">Aprobado</option>
                        <option valueD="rejected">Rechazado</option>
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="jury_president_id" value="Presidente de Jurado (Opcional)" />
                    <select id="jury_president_id" wire:model="jury_president_id" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sin Asignar --</option>
                        @foreach($teachers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
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
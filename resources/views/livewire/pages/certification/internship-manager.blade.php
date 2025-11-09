<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Pasantías y Prácticas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por estudiante o empresa..." />
                        <x-button wire:click="openCreateModal">
                            Registrar Nueva Pasantía
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estudiante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Empresa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Inicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Fin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Horas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($internships as $item)
                                    <tr>
                                        <td class="px-6 py-4">{{ $item->student->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $item->company_name }}</td>
                                        <td class="px-6 py-4">{{ $item->start_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $item->end_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $item->total_hours }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-blue-100 text-blue-800' => $item->status == 'planned',
                                                'bg-yellow-100 text-yellow-800' => $item->status == 'in_progress',
                                                'bg-green-100 text-green-800' => $item->status == 'completed',
                                                'bg-red-100 text-red-800' => $item->status == 'cancelled',
                                            ])>
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
                                        <td colspan="7" class="px-6 py-4 text-center">No se encontraron registros de pasantías.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $internships->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingInternship ? 'Editar Pasantía' : 'Registrar Nueva Pasantía' }}
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
                
                <div class="col-span-2 border-t mt-2"></div>
                
                <div class="col-span-2">
                    <x-label for="company_name" value="Nombre de la Empresa" />
                    <x-input id="company_name" type="text" class="mt-1 block w-full" wire:model.blur="company_name" />
                    <x-input-error for="company_name" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="company_ruc" value="RUC de la Empresa (Opcional)" />
                    <x-input id="company_ruc" type="text" class="mt-1 block w-full" wire:model.blur="company_ruc" />
                    <x-input-error for="company_ruc" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="supervisor_name" value="Nombre del Supervisor" />
                    <x-input id="supervisor_name" type="text" class="mt-1 block w-full" wire:model.blur="supervisor_name" />
                    <x-input-error for="supervisor_name" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="start_date" value="Fecha de Inicio" />
                    <x-input id="start_date" type="date" class="mt-1 block w-full" wire:model.blur="start_date" />
                    <x-input-error for="start_date" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="end_date" value="Fecha de Fin" />
                    <x-input id="end_date" type="date" class="mt-1 block w-full" wire:model.blur="end_date" />
                    <x-input-error for="end_date" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="total_hours" value="Total de Horas" />
                    <x-input id="total_hours" type="number" class="mt-1 block w-full" wire:model.blur="total_hours" />
                    <x-input-error for="total_hours" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="evaluation_score" value="Nota (0-20, Opcional)" />
                    <x-input id="evaluation_score" type="number" step="0.5" class="mt-1 block w-full" wire:model.blur="evaluation_score" />
                    <x-input-error for="evaluation_score" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="planned">Planeado</option>
                        <option value="in_progress">En Progreso</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
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
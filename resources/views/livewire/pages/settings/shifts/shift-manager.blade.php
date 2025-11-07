<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Turnos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar turno..." />
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Turno
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre del Turno</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Hora Inicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Hora Fin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($shifts as $shift)
                                    <tr>
                                        <td class="px-6 py-4">{{ $shift->name }}</td>
                                        <td class="px-6 py-4">{{ $shift->start_time->format('H:i A') }}</td>
                                        <td class="px-6 py-4">{{ $shift->end_time->format('H:i A') }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $shift->status == 'active',
                                                'bg-red-100 text-red-800' => $shift->status == 'inactive',
                                            ])>
                                                {{ $shift->status == 'active' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $shift->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $shift->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">No se encontraron turnos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $shifts->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingShift ? 'Editar Turno' : 'Crear Nuevo Turno' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Turno" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" placeholder="Ej. Turno Mañana" />
                    <x-input-error for="name" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="start_time" value="Hora de Inicio" />
                    <x-input id="start_time" type="time" class="mt-1 block w-full" wire:model.blur="start_time" />
                    <x-input-error for="start_time" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="end_time" value="Hora de Fin" />
                    <x-input id="end_time" type="time" class="mt-1 block w-full" wire:model.blur="end_time" />
                    <x-input-error for="end_time" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-label for="description" value="Descripción (Opcional)" />
                    <x-input id="description" type="text" class="mt-1 block w-full" wire:model.blur="description" />
                    <x-input-error for="description" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
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
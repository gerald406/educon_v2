<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Tipos de Evaluación
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar tipo..." />
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Tipo
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Orden</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Peso (%)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">¿Anulable?</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($types as $type)
                                    <tr>
                                        <td class="px-6 py-4">{{ $type->sort_order }}</td>
                                        <td class="px-6 py-4">{{ $type->name }}</td>
                                        <td class="px-6 py-4">{{ number_format($type->weight_percentage, 2) }}%</td>
                                        <td class="px-6 py-4">{{ $type->is_droppable ? 'Sí' : 'No' }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $type->status == 'active',
                                                'bg-red-100 text-red-800' => $type->status == 'inactive',
                                            ])>
                                                {{ $type->status == 'active' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $type->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $type->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron tipos de evaluación.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $types->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingType ? 'Editar Tipo de Evaluación' : 'Crear Nuevo Tipo de Evaluación' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Tipo" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" placeholder="Ej. Examen Parcial" />
                    <x-input-error for="name" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="weight_percentage" value="Ponderación (%)" />
                    <x-input id="weight_percentage" type="number" step="0.01" class="mt-1 block w-full" wire:model.blur="weight_percentage" placeholder="Ej. 30.00" />
                    <x-input-error for="weight_percentage" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="sort_order" value="Orden" />
                    <x-input id="sort_order" type="number" class="mt-1 block w-full" wire:model.blur="sort_order" placeholder="Ej. 1" />
                    <x-input-error for="sort_order" class="mt-2" />
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
                
                <div class="col-span-1 flex items-end pb-2">
                    <label class="flex items-center">
                        <x-checkbox wire:model="is_droppable" />
                        <span class="ms-2 text-sm text-gray-600">¿Es nota anulable?</span>
                    </label>
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
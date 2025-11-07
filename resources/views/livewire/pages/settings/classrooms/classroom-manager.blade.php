<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Aulas y Laboratorios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar aulas..." />
                        
                        <x-button wire:click="openCreateModal">
                            Crear Nueva Aula
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Edificio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Capacidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($classrooms as $classroom)
                                    <tr>
                                        <td class="px-6 py-4">{{ $classroom->classroom_code }}</td>
                                        <td class="px-6 py-4">{{ $classroom->name }}</td>
                                        <td class="px-6 py-4">{{ $classroom->building }}</td>
                                        <td class="px-6 py-4">{{ $classroom->capacity }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $classroom->status == 'available',
                                                'bg-yellow-100 text-yellow-800' => $classroom->status == 'maintenance',
                                                'bg-red-100 text-red-800' => $classroom->status == 'unavailable',
                                            ])>
                                                {{ $classroom->status == 'available' ? 'Disponible' : ($classroom->status == 'maintenance' ? 'Mantenimiento' : 'No Disponible') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $classroom->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $classroom->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron aulas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $classrooms->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingClassroom ? 'Editar Aula/Laboratorio' : 'Crear Nueva Aula/Laboratorio' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1">
                    <x-label for="classroom_code" value="Código" />
                    <x-input id="classroom_code" type="text" class="mt-1 block w-full" wire:model.blur="classroom_code" />
                    <x-input-error for="classroom_code" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="name" value="Nombre del Aula" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="building" value="Edificio/Pabellón" />
                    <x-input id="building" type="text" class="mt-1 block w-full" wire:model.blur="building" />
                    <x-input-error for="building" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="capacity" value="Capacidad" />
                    <x-input id="capacity" type="number" class="mt-1 block w-full" wire:model.blur="capacity" />
                    <x-input-error for="capacity" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="available">Disponible</option>
                        <option value="maintenance">En Mantenimiento</option>
                        <option value="unavailable">No Disponible</option>
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>

                <div class="col-span-2 mt-4 space-y-2">
                    <label class="flex items-center">
                        <x-checkbox wire:model="has_projector" />
                        <span class="ms-2 text-sm text-gray-600">Tiene Proyector</span>
                    </label>
                    <label class="flex items-center">
                        <x-checkbox wire:model.live="has_computers" />
                        <span class="ms-2 text-sm text-gray-600">Tiene Computadoras (Laboratorio)</span>
                    </label>
                </div>
                
                @if ($has_computers)
                    <div class="col-span-1">
                        <x-label for="computer_count" value="Nro. de Computadoras" />
                        <x-input id="computer_count" type="number" class="mt-1 block w-full" wire:model.blur="computer_count" />
                        <x-input-error for="computer_count" class="mt-2" />
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Guardar Cambios</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
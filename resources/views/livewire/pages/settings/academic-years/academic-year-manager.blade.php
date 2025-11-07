<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Años Académicos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por año o nombre..." />
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Año
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Año</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Inicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Fin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($years as $year)
                                    <tr>
                                        <td class="px-6 py-4">{{ $year->year }}</td>
                                        <td class="px-6 py-4">{{ $year->name }}</td>
                                        <td class="px-6 py-4">{{ $year->start_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $year->end_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-blue-100 text-blue-800' => $year->status == 'planned',
                                                'bg-green-100 text-green-800' => $year->status == 'active',
                                                'bg-gray-100 text-gray-800' => $year->status == 'closed',
                                            ])>
                                                {{ ucfirst($year->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $year->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $year->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron años académicos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $years->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingYear ? 'Editar Año Académico' : 'Crear Nuevo Año Académico' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-1">
                    <x-label for="year" value="Año" />
                    <x-input id="year" type="number" step="1" placeholder="Ej. 2025" class="mt-1 block w-full" wire:model.live="year" />
                    <x-input-error for="year" class="mt-2" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Año" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" />
                    <x-input-error for="name" class="mt-2" />
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
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="planned">Planeado</option>
                        <option value="active">Activo</option>
                        <option value="closed">Cerrado</option>
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
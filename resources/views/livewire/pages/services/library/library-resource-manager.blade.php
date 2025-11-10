<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión del Catálogo de Biblioteca
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por título, autor o código..." class="w-1/2" />
                        <x-button wire:click="openCreateModal">
                            Añadir Nuevo Recurso
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Autor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Copias</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($resources as $resource)
                                    <tr>
                                        <td class="px-6 py-4">{{ $resource->code }}</td>
                                        <td class="px-6 py-4">{{ $resource->title }}</td>
                                        <td class="px-6 py-4">{{ $resource->author }}</td>
                                        <td class="px-6 py-4">{{ $resource->resource_type }}</td>
                                        <td class="px-6 py-4">{{ $resource->copies_available }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $resource->status == 'available',
                                                'bg-yellow-100 text-yellow-800' => $resource->status == 'borrowed',
                                                'bg-red-100 text-red-800' => $resource->status == 'lost',
                                            ])>
                                                {{ ucfirst($resource->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $resource->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $resource->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center">No se encontraron recursos en la biblioteca.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $resources->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingResource ? 'Editar Recurso' : 'Añadir Nuevo Recurso' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="title" value="Título" />
                    <x-input id="title" type="text" class="mt-1 block w-full" wire:model.blur="title" />
                    <x-input-error for="title" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="author" value="Autor (Opcional)" />
                    <x-input id="author" type="text" class="mt-1 block w-full" wire:model.blur="author" />
                    <x-input-error for="author" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="publisher" value="Editorial (Opcional)" />
                    <x-input id="publisher" type="text" class="mt-1 block w-full" wire:model.blur="publisher" />
                    <x-input-error for="publisher" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="code" value="Código (Catalogación)" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model.blur="code" />
                    <x-input-error for="code" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="isbn" value="ISBN (Opcional)" />
                    <x-input id="isbn" type="text" class="mt-1 block w-full" wire:model.blur="isbn" />
                    <x-input-error for="isbn" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="resource_type" value="Tipo de Recurso" />
                    <select id="resource_type" wire:model="resource_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="book">Libro</option>
                        <option value="magazine">Revista</option>
                        <option value="thesis">Tesis</option>
                        <option value="manual">Manual</option>
                        <option value="digital">Digital</option>
                        <option value="audiovisual">Audiovisual</option>
                    </select>
                    <x-input-error for="resource_type" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="publication_year" value="Año Publicación (Opcional)" />
                    <x-input id="publication_year" type="number" class="mt-1 block w-full" wire:model.blur="publication_year" placeholder="Ej. 2021" />
                    <x-input-error for="publication_year" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="copies_available" value="Copias Disponibles" />
                    <x-input id="copies_available" type="number" class="mt-1 block w-full" wire:model.blur="copies_available" />
                    <x-input-error for="copies_available" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="physical_location" value="Ubicación Física (Opcional)" />
                    <x-input id="physical_location" type="text" class="mt-1 block w-full" wire:model.blur="physical_location" placeholder="Ej. Estante A-3" />
                    <x-input-error for="physical_location" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="career_id" value="Asociar a Carrera (Opcional)" />
                    <select id="career_id" wire:model="career_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- General / Ninguna --</option>
                        @foreach($careers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="career_id" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="available">Disponible</option>
                        <option value="borrowed">Prestado</option>
                        <option value="reserved">Reservado</option>
                        <option value="maintenance">Mantenimiento</option>
                        <option value="lost">Perdido</option>
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
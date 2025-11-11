<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Anuncios y Comunicación
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por título..." class="w-1/2" />
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Anuncio
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Audiencia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Publicación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($announcements as $item)
                                    <tr>
                                        <td class="px-6 py-4">{{ $item->title }}</td>
                                        <td class="px-6 py-4">{{ $item->announcement_type }}</td>
                                        <td class="px-6 py-4">{{ $item->target_audience }}</td>
                                        <td class="px-6 py-4">{{ $item->publish_date->format('d/m/Y h:i A') }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-blue-100 text-blue-800' => $item->status == 'published',
                                                'bg-gray-100 text-gray-800' => $item->status == 'draft',
                                                'bg-red-100 text-red-800' => $item->status == 'archived',
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
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron anuncios.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $announcements->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingAnnouncement ? 'Editar Anuncio' : 'Crear Nuevo Anuncio' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="title" value="Título del Anuncio" />
                    <x-input id="title" type="text" class="mt-1 block w-full" wire:model.blur="title" />
                    <x-input-error for="title" class="mt-2" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="content" value="Contenido del Anuncio" />
                    <textarea id="content" wire:model.blur="content" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <x-input-error for="content" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="announcement_type" value="Tipo" />
                    <select id="announcement_type" wire:model="announcement_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="announcement">Anuncio</option>
                        <option value="news">Noticia</option>
                        <option value="event">Evento</option>
                        <option value="notice">Comunicado</option>
                        <option value="urgent">Urgente</option>
                    </select>
                </div>
                
                <div class="col-span-1">
                    <x-label for="target_audience" value="Audiencia" />
                    <select id="target_audience" wire:model="target_audience" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="all">Todos</option>
                        <option value="students">Solo Estudiantes</option>
                        <option value="teachers">Solo Docentes</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <x-label for="publish_date" value="Fecha de Publicación" />
                    <x-input id="publish_date" type="datetime-local" class="mt-1 block w-full" wire:model.blur="publish_date" />
                    <x-input-error for="publish_date" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="expiration_date" value="Fecha de Expiración (Opcional)" />
                    <x-input id="expiration_date" type="datetime-local" class="mt-1 block w-full" wire:model.blur="expiration_date" />
                    <x-input-error for="expiration_date" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="draft">Borrador</option>
                        <option value="published">Publicado</option>
                        <option value="archived">Archivado</option>
                    </select>
                </div>
                
                <div class="col-span-1 flex items-end pb-2">
                    <label class="flex items-center">
                        <x-checkbox wire:model="is_featured" />
                        <span class="ms-2 text-sm text-gray-600">¿Es un anuncio destacado?</span>
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                Guardar Anuncio
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
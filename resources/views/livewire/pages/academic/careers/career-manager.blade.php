<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Programas de Estudio (Carreras)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <div class="w-1/3 relative">
                            <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar programa..." class="w-full" />
                            <div wire:loading wire:target="search" class="absolute right-3 top-2.5 text-gray-400">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        <x-button wire:click="openCreateModal" wire:loading.attr="disabled" wire:target="openCreateModal">
                            <span wire:loading.remove wire:target="openCreateModal">Crear Nuevo Programa</span>
                            <span wire:loading wire:target="openCreateModal">Cargando...</span>
                        </x-button>
                    </div>

                    <div class="overflow-x-auto relative">
                        <div wire:loading.block wire:target="search, deleteCareer" class="absolute inset-0 bg-white/50 z-10"></div>
                        
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nombre del Programa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Semestres</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($careers as $career)
                                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $career->code }}</td>
                                        <td class="px-6 py-4">{{ $career->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $career->duration_semesters }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $career->status == 'active',
                                                'bg-red-100 text-red-800' => $career->status == 'inactive',
                                            ])>
                                                {{ $career->status == 'active' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="openEditModal({{ $career->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3 transition duration-150 ease-in-out">Editar</button>
                                            <button wire:click="confirmDelete({{ $career->id }})" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">Eliminar</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                <span class="text-lg">No se encontraron programas de estudio.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $careers->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingCareer ? 'Editar Programa de Estudio' : 'Crear Nuevo Programa de Estudio' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="col-span-2">
                    <x-label for="institution_id" value="Institución" />
                    <select id="institution_id" wire:model="institution_id" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach($institutions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="institution_id" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="code" value="Código del Programa" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model.blur="code" placeholder="Ej. APSTI" />
                    <x-input-error for="code" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="duration_semesters" value="Duración (Semestres)" />
                    <x-input id="duration_semesters" type="number" class="mt-1 block w-full" wire:model.blur="duration_semesters" />
                    <x-input-error for="duration_semesters" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Programa" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-label for="degree_awarded" value="Título que Otorga (Opcional)" />
                    <x-input id="degree_awarded" type="text" class="mt-1 block w-full" wire:model.blur="degree_awarded" />
                    <x-input-error for="degree_awarded" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Guardar</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
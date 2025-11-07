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
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar programa..." />
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Programa
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre del Programa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Semestres</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($careers as $career)
                                    <tr>
                                        <td class="px-6 py-4">{{ $career->code }}</td>
                                        <td class="px-6 py-4">{{ $career->name }}</td>
                                        <td class="px-6 py-4">{{ $career->duration_semesters }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $career->status == 'active',
                                                'bg-red-100 text-red-800' => $career->status == 'inactive',
                                            ])>
                                                {{ $career->status == 'active' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $career->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $career->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">No se encontraron programas de estudio.</td>
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
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                Guardar
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Modalidades de Admisión
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-end mb-4">
                        <x-button wire:click="create">
                            Nueva Modalidad
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($modalities as $modality)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $modality->name }}</div>
                                            @if($modality->description)
                                                <div class="text-xs text-gray-500 truncate w-64">{{ $modality->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-blue-100 text-blue-800' => $modality->type === 'ordinario',
                                                'bg-purple-100 text-purple-800' => $modality->type === 'extraordinario',
                                            ])>
                                                {{ $modality->type === 'ordinario' ? 'Ordinario' : 'Extraordinario' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $modality->is_active,
                                                'bg-red-100 text-red-800' => !$modality->is_active,
                                            ])>
                                                {{ $modality->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <x-button wire:click="edit({{ $modality->id }})" class="bg-indigo-600 hover:bg-indigo-700">
                                                Editar
                                            </x-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No hay modalidades registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $modalities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            {{ $editingModality ? 'Editar Modalidad' : 'Crear Nueva Modalidad' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <x-label for="name" value="Nombre de la Modalidad" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Ej. Primeros Puestos" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div>
                    <x-label for="type" value="Tipo de Examen" />
                    <select id="type" wire:model="type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ordinario">Ordinario</option>
                        <option value="extraordinario">Extraordinario</option>
                    </select>
                    <x-input-error for="type" class="mt-2" />
                </div>

                <div>
                    <x-label for="description" value="Descripción o Requisitos (Opcional)" />
                    <textarea id="description" wire:model="description" rows="3" class="form-textarea mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <label for="is_active" class="flex items-center">
                        <x-checkbox id="is_active" wire:model="is_active" />
                        <span class="ml-2 text-sm text-gray-600">Habilitado para inscripción</span>
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isModalOpen', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                {{ $editingModality ? 'Actualizar' : 'Guardar' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
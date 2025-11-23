<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Entidades Financieras (Bancos / Cajas)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-end mb-4">
                        <x-button wire:click="create">
                            Nueva Entidad Financiera
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código / Cuenta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($entities as $entity)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $entity->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $entity->code ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $entity->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $entity->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <x-button wire:click="edit({{ $entity->id }})" class="bg-indigo-600 hover:bg-indigo-700">
                                                Editar
                                            </x-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No hay entidades financieras registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $entities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            {{ $editingEntity ? 'Editar Entidad Financiera' : 'Crear Nueva Entidad Financiera' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <x-label for="name" value="Nombre de la Entidad (Banco/Caja)" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Ej. Banco de la Nación" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div>
                    <x-label for="code" value="Código Interno o Nro. Cuenta (Opcional)" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model="code" placeholder="Ej. BN-001" />
                    <x-input-error for="code" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <label for="is_active" class="flex items-center">
                        <x-checkbox id="is_active" wire:model="is_active" />
                        <span class="ml-2 text-sm text-gray-600">Habilitado para pagos</span>
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isModalOpen', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                {{ $editingEntity ? 'Actualizar' : 'Guardar' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
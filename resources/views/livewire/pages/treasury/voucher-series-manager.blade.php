<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Correlativos de Comprobantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por serie o tipo..." class="w-1/2" />
                        <x-button wire:click="openCreateModal">
                            Crear Nueva Serie
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número Actual</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($seriesList as $series)
                                    <tr>
                                        <td class="px-6 py-4">{{ ucfirst($series->voucher_type) }}</td>
                                        <td class="px-6 py-4">{{ $series->series }}</td>
                                        <td class="px-6 py-4">{{ $series->current_number }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $series->status == 'active',
                                                'bg-red-100 text-red-800' => $series->status == 'inactive',
                                            ])>
                                                {{ ucfirst($series->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <x-button wire:click="openEditModal({{ $series->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $series->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">No se encontraron series de comprobantes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $seriesList->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingSeries ? 'Editar Serie' : 'Crear Nueva Serie' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-1">
                    <x-label for="voucher_type" value="Tipo de Comprobante" />
                    <select id="voucher_type" wire:model="voucher_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="recibo">Recibo de Caja</option>
                        <option value="boleta">Boleta</option>
                        <option value="factura">Factura</option>
                        <option value="nota_credito">Nota de Crédito</option>
                    </select>
                    <x-input-error for="voucher_type" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="series" value="Serie" />
                    <x-input id="series" type="text" class="mt-1 block w-full" wire:model.blur="series" placeholder="Ej. R001 o B001" />
                    <x-input-error for="series" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="current_number" value="Número Actual (Correlativo)" />
                    <x-input id="current_number" type="number" class="mt-1 block w-full" wire:model.blur="current_number" />
                    <x-input-error for="current_number" class="mt-2" />
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
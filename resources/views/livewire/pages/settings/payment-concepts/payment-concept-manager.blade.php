<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Conceptos de Pago (TUPA)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar concepto..." />
                        
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Concepto
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Cód. TUPA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Descripción</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Monto (S/.)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($concepts as $concept)
                                    <tr>
                                        <td class="px-6 py-4">{{ $concept->code }}</td>
                                        <td class="px-6 py-4">{{ $concept->tupa_code }}</td>
                                        <td class="px-6 py-4">{{ $concept->description }}</td>
                                        <td class="px-6 py-4">{{ number_format($concept->amount, 2) }}</td>
                                        <td class="px-6 py-4">{{ $concept->concept_type }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $concept->status == 'active',
                                                'bg-red-100 text-red-800' => $concept->status == 'inactive',
                                            ])>
                                                {{ $concept->status == 'active' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $concept->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $concept->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center">No se encontraron conceptos de pago.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $concepts->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingConcept ? 'Editar Concepto de Pago' : 'Crear Nuevo Concepto de Pago' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-1">
                    <x-label for="code" value="Código Interno (SKU)" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model.blur="code" />
                    <x-input-error for="code" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="tupa_code" value="Código TUPA (Opcional)" />
                    <x-input id="tupa_code" type="text" class="mt-1 block w-full" wire:model.blur="tupa_code" />
                    <x-input-error for="tupa_code" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-label for="description" value="Descripción del Concepto" />
                    <x-input id="description" type="text" class="mt-1 block w-full" wire:model.blur="description" />
                    <x-input-error for="description" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="amount" value="Monto (S/.)" />
                    <x-input id="amount" type="number" step="0.01" class="mt-1 block w-full" wire:model.blur="amount" />
                    <x-input-error for="amount" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="concept_type" value="Tipo de Concepto" />
                    <select id="concept_type" wire:model="concept_type" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="enrollment">Matrícula</option>
                        <option value="tuition">Pensión</option>
                        <option value="certificate">Certificado</option>
                        <option value="statement">Constancia</option>
                        <option value="fee">Tasa / Examen</option>
                        <option value="other">Otro</option>
                    </select>
                    <x-input-error for="concept_type" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>

                <div class="col-span-2 mt-4 space-y-2">
                    <label class="flex items-center">
                        <x-checkbox wire:model="is_mandatory" />
                        <span class="ms-2 text-sm text-gray-600">¿Es obligatorio?</span>
                    </label>
                    <label class="flex items-center">
                        <x-checkbox wire:model.live="is_taxable" />
                        <span class="ms-2 text-sm text-gray-600">¿Es afecto a impuestos (IGV)?</span>
                    </label>
                </div>
                
                @if ($is_taxable)
                    <div class="col-span-1">
                        <x-label for="tax_rate" value="Tasa de Impuesto (%)" />
                        <x-input id="tax_rate" type="number" step="0.01" class="mt-1 block w-full" wire:model.blur="tax_rate" />
                        <x-input-error for="tax_rate" class="mt-2" />
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
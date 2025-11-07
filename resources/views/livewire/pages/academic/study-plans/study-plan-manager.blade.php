<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Planes de Estudio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar plan o carrera..." />
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Plan
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre del Plan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Programa (Carrera)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Créditos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($plans as $plan)
                                    <tr>
                                        <td class="px-6 py-4">{{ $plan->code }}</td>
                                        <td class="px-6 py-4">{{ $plan->name }}</td>
                                        <td class="px-6 py-4">{{ $plan->career->name }}</td> 
                                        <td class="px-6 py-4">{{ $plan->total_credits }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $plan->status == 'active',
                                                'bg-yellow-100 text-yellow-800' => $plan->status == 'inactive',
                                                'bg-gray-100 text-gray-800' => $plan->status == 'obsolete',
                                            ])>
                                                {{ ucfirst($plan->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $plan->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $plan->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron planes de estudio.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $plans->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingStudyPlan ? 'Editar Plan de Estudio' : 'Crear Nuevo Plan de Estudio' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="col-span-2">
                    <x-label for="career_id" value="Programa de Estudio (Carrera)" />
                    <select id="career_id" wire:model="career_id" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Seleccione un programa --</option>
                        @foreach($careers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="career_id" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="code" value="Código del Plan" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model.blur="code" placeholder="Ej. APSTI-2021" />
                    <x-input-error for="code" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="version" value="Versión" />
                    <x-input id="version" type="text" class="mt-1 block w-full" wire:model.blur="version" placeholder="Ej. 2021" />
                    <x-input-error for="version" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Plan" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" placeholder="Ej. Plan de Estudios 2021" />
                    <x-input-error for="name" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="total_credits" value="Total Créditos" />
                    <x-input id="total_credits" type="number" class="mt-1 block w-full" wire:model.blur="total_credits" />
                    <x-input-error for="total_credits" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="total_hours" value="Total Horas" />
                    <x-input id="total_hours" type="number" class="mt-1 block w-full" wire:model.blur="total_hours" />
                    <x-input-error for="total_hours" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="start_date" value="Fecha de Inicio" />
                    <x-input id="start_date" type="date" class="mt-1 block w-full" wire:model.blur="start_date" />
                    <x-input-error for="start_date" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="end_date" value="Fecha de Fin (Opcional)" />
                    <x-input id="end_date" type="date" class="mt-1 block w-full" wire:model.blur="end_date" />
                    <x-input-error for="end_date" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                        <option value="obsolete">Obsoleto</option>
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
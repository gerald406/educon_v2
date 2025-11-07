<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Módulos Formativos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar módulo, plan o carrera..." />
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Módulo
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nro.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre del Módulo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Plan de Estudio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Programa (Carrera)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($modules as $module)
                                    <tr>
                                        <td class="px-6 py-4">{{ $module->module_number }}</td>
                                        <td class="px-6 py-4">{{ $module->name }}</td>
                                        <td class="px-6 py-4">{{ $module->studyPlan->name }}</td>
                                        <td class="px-6 py-4">{{ $module->studyPlan->career->name }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $module->status == 'active',
                                                'bg-red-100 text-red-800' => $module->status == 'inactive',
                                            ])>
                                                {{ $module->status == 'active' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $module->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $module->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron módulos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $modules->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingModule ? 'Editar Módulo Formativo' : 'Crear Nuevo Módulo Formativo' }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4 p-4 bg-gray-50 rounded-md grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1">
                    <x-label for="selectedCareerId" value="Programa (Carrera)" />
                    <select id="selectedCareerId" wire:model.live="selectedCareerId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione un programa --</option>
                        @foreach($careers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-1">
                    <x-label for="study_plan_id" value="Plan de Estudio" />
                    <select id="study_plan_id" wire:model="study_plan_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        @if($availableStudyPlans->isEmpty()) disabled @endif>
                        
                        <option value="">-- Seleccione un plan --</option>
                        @foreach($availableStudyPlans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="study_plan_id" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Módulo" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="module_number" value="Nro. de Módulo" />
                    <x-input id="module_number" type="number" class="mt-1 block w-full" wire:model.blur="module_number" />
                    <x-input-error for="module_number" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="sort_order" value="Orden" />
                    <x-input id="sort_order" type="number" class="mt-1 block w-full" wire:model.blur="sort_order" />
                    <x-input-error for="sort_order" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="minimum_credits_approval" value="Créditos Mín. Aprob." />
                    <x-input id="minimum_credits_approval" type="number" class="mt-1 block w-full" wire:model.blur="minimum_credits_approval" />
                    <x-input-error for="minimum_credits_approval" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="total_hours" value="Horas Totales Módulo" />
                    <x-input id="total_hours" type="number" class="mt-1 block w-full" wire:model.blur="total_hours" />
                    <x-input-error for="total_hours" class="mt-2" />
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
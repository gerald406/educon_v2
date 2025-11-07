<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Unidades Didácticas (Cursos)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="mb-4 p-4 bg-gray-50 rounded-md grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-label for="selectedCareerId" value="Programa (Carrera)" />
                            <select id="selectedCareerId" wire:model.live="selectedCareerId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($careers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-label for="selectedStudyPlanId" value="Plan de Estudio" />
                            <select id="selectedStudyPlanId" wire:model.live="selectedStudyPlanId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                @if($studyPlans->isEmpty()) disabled @endif>
                                <option value="">-- Seleccione un plan --</option>
                                @foreach($studyPlans as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-label for="selectedModuleId" value="Módulo Formativo" />
                            <select id="selectedModuleId" wire:model.live="selectedModuleId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                @if($modules->isEmpty()) disabled @endif>
                                <option value="">-- Seleccione un módulo --</option>
                                @foreach($modules as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($selectedModuleId)
                        <div class="flex justify-between items-center mb-4">
                            <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar unidad..." />
                            <x-button wire:click="openCreateModal">
                                Crear Nueva Unidad
                            </x-button>
                        </div>
                    @else
                        <div class="text-center text-gray-500 p-4">
                            Por favor, seleccione un módulo para ver y gestionar las unidades didácticas.
                        </div>
                    @endif

                    @if($selectedModuleId)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Nombre de la Unidad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Sem.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Créd.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Horas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Tipo</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($units as $unit)
                                        <tr>
                                            <td class="px-6 py-4">{{ $unit->code }}</td>
                                            <td class="px-6 py-4">{{ $unit->name }}</td>
                                            <td class="px-6 py-4">{{ $unit->semester }}</td>
                                            <td class="px-6 py-4">{{ $unit->credits }}</td>
                                            <td class="px-6 py-4">{{ $unit->total_hours }}</td>
                                            <td class="px-6 py-4">{{ ucfirst($unit->unit_type) }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <x-button wire:click="openEditModal({{ $unit->id }})">Editar</x-button>
                                                <x-danger-button wire:click="confirmDelete({{ $unit->id }})">Eliminar</x-danger-button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center">No se encontraron unidades para este módulo.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $units->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingUnit ? 'Editar Unidad Didáctica' : 'Crear Nueva Unidad Didáctica' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-1">
                    <x-label for="code" value="Código de Unidad" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model.blur="code" />
                    <x-input-error for="code" class="mt-2" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="name" value="Nombre de la Unidad" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="semester" value="Semestre" />
                    <x-input id="semester" type="number" class="mt-1 block w-full" wire:model.blur="semester" />
                    <x-input-error for="semester" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="semester_order" value="Orden en Semestre" />
                    <x-input id="semester_order" type="number" class="mt-1 block w-full" wire:model.blur="semester_order" />
                    <x-input-error for="semester_order" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="credits" value="Créditos" />
                    <x-input id="credits" type="number" class="mt-1 block w-full" wire:model.blur="credits" />
                    <x-input-error for="credits" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="weekly_hours" value="Horas Semanales" />
                    <x-input id="weekly_hours" type="number" class="mt-1 block w-full" wire:model.blur="weekly_hours" />
                    <x-input-error for="weekly_hours" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="total_hours" value="Horas Totales (Semestral)" />
                    <x-input id="total_hours" type="number" class="mt-1 block w-full" wire:model.blur="total_hours" />
                    <x-input-error for="total_hours" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="unit_type" value="Tipo de Unidad" />
                    <select id="unit_type" wire:model="unit_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="career">De Carrera</option>
                        <option value="transversal">Transversal</option>
                    </select>
                    <x-input-error for="unit_type" class="mt-2" />
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
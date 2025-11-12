<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Periodos Académicos (Semestres)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por código o nombre..." />
                        <x-button wire:click="openCreateModal">
                            Crear Nuevo Periodo
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Año Académico</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Inicio Clases</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fin Clases</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($periods as $period)
                                    <tr>
                                        <td class="px-6 py-4">{{ $period->code }}</td>
                                        <td class="px-6 py-4">{{ $period->name }}</td>
                                        <td class="px-6 py-4">{{ $period->academicYear->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $period->classes_start_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $period->classes_end_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-blue-100 text-blue-800' => $period->status == 'planned',
                                                'bg-green-100 text-green-800' => $period->status == 'active',
                                                'bg-gray-100 text-gray-800' => $period->status == 'closed',
                                            ])>
                                                {{ ucfirst($period->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $period->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $period->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center">No se encontraron periodos académicos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $periods->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingPeriod ? 'Editar Periodo Académico' : 'Crear Nuevo Periodo Académico' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-1">
                    <x-label for="academic_year_id" value="Año Académico" />
                    <select id="academic_year_id" wire:model="academic_year_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione un año --</option>
                        @foreach($academicYears as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="academic_year_id" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="code" value="Código del Periodo" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model.blur="code" placeholder="Ej. 2025-II" />
                    <x-input-error for="code" class="mt-2" />
                </div>

                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Periodo" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" placeholder="Ej. Periodo Académico 2025-II" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="start_date" value="Inicio del Periodo" />
                    <x-input id="start_date" type="date" class="mt-1 block w-full" wire:model.blur="start_date" />
                    <x-input-error for="start_date" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="end_date" value="Fin del Periodo" />
                    <x-input id="end_date" type="date" class="mt-1 block w-full" wire:model.blur="end_date" />
                    <x-input-error for="end_date" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="enrollment_start_date" value="Inicio de Matrícula" />
                    <x-input id="enrollment_start_date" type="date" class="mt-1 block w-full" wire:model.blur="enrollment_start_date" />
                    <x-input-error for="enrollment_start_date" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="enrollment_end_date" value="Fin de Matrícula" />
                    <x-input id="enrollment_end_date" type="date" class="mt-1 block w-full" wire:model.blur="enrollment_end_date" />
                    <x-input-error for="enrollment_end_date" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="classes_start_date" value="Inicio de Clases" />
                    <x-input id="classes_start_date" type="date" class="mt-1 block w-full" wire:model.blur="classes_start_date" />
                    <x-input-error for="classes_start_date" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="classes_end_date" value="Fin de Clases" />
                    <x-input id="classes_end_date" type="date" class="mt-1 block w-full" wire:model.blur="classes_end_date" />
                    <x-input-error for="classes_end_date" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="grade_entry_start_date" value="Inicio Registro de Notas" />
                    <x-input id="grade_entry_start_date" type="datetime-local" class="mt-1 block w-full" wire:model.blur="grade_entry_start_date" />
                    <x-input-error for="grade_entry_start_date" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="grade_entry_end_date" value="Fin Registro de Notas" />
                    <x-input id="grade_entry_end_date" type="datetime-local" class="mt-1 block w-full" wire:model.blur="grade_entry_end_date" />
                    <x-input-error for="grade_entry_end_date" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="planned">Planeado</option>
                        <option value="active">Activo</option>
                        <option value="closed">Cerrado</option>
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
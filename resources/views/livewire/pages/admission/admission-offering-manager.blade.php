<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Configuración de Vacantes ({{ $activePeriod?->name ?? 'Sin Periodo Activo' }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if(!$activePeriod)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">¡Atención!</strong>
                            <span class="block sm:inline">No hay un periodo académico activo. Debe activar uno en "Procesos Académicos" para configurar las vacantes.</span>
                        </div>
                    @else
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-sm text-gray-600">
                                Configure las carreras y turnos disponibles para el examen de admisión actual.
                            </p>
                            <x-button wire:click="create">
                                Agregar Vacantes
                            </x-button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programa de Estudio (Carrera)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Turno</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nro. Vacantes</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($offerings as $offering)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $offering->career->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $offering->career->code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $offering->shift->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">{{ $offering->vacancies }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span @class([
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                    'bg-green-100 text-green-800' => $offering->is_active,
                                                    'bg-red-100 text-red-800' => !$offering->is_active,
                                                ])>
                                                    {{ $offering->is_active ? 'Abierto' : 'Cerrado' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <x-button wire:click="edit({{ $offering->id }})" class="bg-indigo-600 hover:bg-indigo-700">
                                                    Editar
                                                </x-button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                No se han configurado vacantes para este periodo.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $offerings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            {{ $editingOffering ? 'Editar Vacantes' : 'Agregar Nueva Oferta' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-6">
                
                <div>
                    <x-label for="career_id" value="Programa de Estudio" />
                    <select id="career_id" wire:model="career_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione --</option>
                        @foreach($careers as $career)
                            <option value="{{ $career->id }}">{{ $career->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="career_id" class="mt-2" />
                </div>

                <div>
                    <x-label for="shift_id" value="Turno" />
                    <select id="shift_id" wire:model="shift_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="shift_id" class="mt-2" />
                </div>

                <div>
                    <x-label for="vacancies" value="Número de Vacantes" />
                    <x-input id="vacancies" type="number" min="1" class="mt-1 block w-full" wire:model="vacancies" />
                    <x-input-error for="vacancies" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <label for="is_active_offering" class="flex items-center">
                        <x-checkbox id="is_active_offering" wire:model="is_active" />
                        <span class="ml-2 text-sm text-gray-600">Abierto para inscripción</span>
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isModalOpen', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                {{ $editingOffering ? 'Actualizar' : 'Guardar' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
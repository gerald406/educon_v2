<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Prerrequisitos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="md:col-span-1 p-4 bg-gray-50 rounded-md shadow-inner">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Seleccionar Curso</h3>
                            
                            <div class="mb-4">
                                <x-label for="selectedCareerId" value="Programa (Carrera)" />
                                <select id="selectedCareerId" wire:model.live="selectedCareerId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach($careers as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <x-label for="selectedStudyPlanId" value="Plan de Estudio" />
                                <select id="selectedStudyPlanId" wire:model.live="selectedStudyPlanId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    @if($studyPlans->isEmpty()) disabled @endif>
                                    <option value="">-- Seleccione un plan --</option>
                                    @foreach($studyPlans as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <x-label for="selectedModuleId" value="Módulo Formativo" />
                                <select id="selectedModuleId" wire:model.live="selectedModuleId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    @if($modules->isEmpty()) disabled @endif>
                                    <option value="">-- Seleccione un módulo --</option>
                                    @foreach($modules as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <x-label for="selectedUnitId" value="Unidad Didáctica (Curso)" />
                                <select id="selectedUnitId" wire:model.live="selectedUnitId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    @if($units->isEmpty()) disabled @endif>
                                    <option value="">-- Seleccione una unidad --</option>
                                    @foreach($units as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            @if ($mainUnit)
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Gestionando Prerrequisitos para: 
                                    <span class="font-semibold text-indigo-600">{{ $mainUnit->name }}</span>
                                </h3>

                                <div class="mb-6 p-4 bg-gray-50 rounded-md">
                                    <h4 class="font-semibold mb-2">Añadir Prerrequisito</h4>
                                    <div class="flex items-end gap-2">
                                        <div class="flex-1">
                                            <x-label for="unitToAddId" value="Seleccione un curso del plan" />
                                            <select id="unitToAddId" wire:model="unitToAddId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                <option value="">-- Seleccione un curso --</option>
                                                @foreach($availableUnitsToAdd as $unit)
                                                    <option value="{{ $unit->id }}">
                                                        (Sem. {{ $unit->semester }}) - {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error for="unitToAddId" class="mt-1" />
                                        </div>
                                        <x-button wire:click="addPrerequisite">
                                            Añadir
                                        </x-button>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold mb-2">Prerrequisitos Actuales ({{ $currentPrerequisites->count() }})</h4>
                                    <div class="overflow-x-auto border rounded-md">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium">Código</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium">Nombre del Prerrequisito</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium">Sem.</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse ($currentPrerequisites as $prereq)
                                                    <tr>
                                                        <td class="px-4 py-3">{{ $prereq->code }}</td>
                                                        <td class="px-4 py-3">{{ $prereq->name }}</td>
                                                        <td class="px-4 py-3">{{ $prereq->semester }}</td>
                                                        <td class="px-4 py-3 text-right">
                                                            <x-danger-button wire:click="removePrerequisite({{ $prereq->id }})">
                                                                Quitar
                                                            </x-danger-button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                                            Esta unidad no tiene prerrequisitos obligatorios.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            @else
                                <div class="text-center text-gray-500 p-10 border rounded-md">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay curso seleccionado</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Utilice los filtros de la izquierda para seleccionar un curso y gestionar sus prerrequisitos.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
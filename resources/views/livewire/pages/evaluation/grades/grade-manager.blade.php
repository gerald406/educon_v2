<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de Notas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="mb-4">
                            <x-label for="selectedAssignmentId" value="Seleccione la Sección (Curso)" />
                            <select id="selectedAssignmentId" wire:model.live="selectedAssignmentId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Seleccione un curso asignado --</option>
                                @foreach($assignments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($isLocked)
                            <div class="mt-4 mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                                <p class="font-semibold">Registro Finalizado</p>
                                <p>Las notas de esta sección ya han sido consolidadas y bloqueadas.</p>
                            </div>
                        @elseif($isOutOfDate)
                            <div class="mt-4 mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-md">
                                <p class="font-semibold">Registro de Notas Cerrado</p>
                                <p>{{ $gradeEntryMessage }}</p>
                            </div>
                        @else
                            <div class="mt-4 mb-4 p-4 bg-blue-100 border border-blue-300 text-blue-800 rounded-md">
                                <p class="font-semibold">Registro de Notas Abierto</p>
                                <p>{{ $gradeEntryMessage }}</p>
                            </div>
                        @endif
                        
                        @if($selectedAssignmentId)
                            <div class="overflow-x-auto mt-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium sticky left-0 bg-gray-50 z-10">N°</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium sticky left-12 bg-gray-50 z-10">Estudiante</th>
                                            
                                            @foreach($evaluationTypes as $type)
                                                <th class="px-6 py-3 text-center text-xs font-medium">
                                                    {{ $type->name }} <span class="font-normal">({{ $type->weight_percentage }}%)</span>
                                                </th>
                                            @endforeach
                                            
                                            <th class="px-6 py-3 text-center text-xs font-medium">Promedio Final</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($registrations as $index => $registration)
                                            <tr>
                                                <td class="px-6 py-4 sticky left-0 bg-white z-10">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 sticky left-12 bg-white z-10 whitespace-nowrap">
                                                    {{ $registration->enrollment->student->user->name ?? 'Estudiante no encontrado' }}
                                                </td>
                                                
                                                @foreach($evaluationTypes as $type)
                                                    <td class="px-2 py-1 text-center">
                                                        <x-input type="number" step="0.5" min="0" max="20"
                                                            class="w-24 text-center"
                                                            wire:model.blur="grades.{{ $registration->id }}.{{ $type->id }}"
                                                            wire:change="saveGrade({{ $registration->id }}, {{ $type->id }})"
                                                            :disabled="$isLocked || $isOutOfDate"
                                                            />
                                                    </td>
                                                @endforeach
                                                
                                                <td class="px-6 py-4 text-center font-semibold">
                                                    @php
                                                        $finalGrade = $finalGrades[$registration->id] ?? null;
                                                    @endphp
                                                    @if ($finalGrade !== null)
                                                        <span @class([
                                                            'text-green-600' => $finalGrade >= $minPassingGrade,
                                                            'text-red-600' => $finalGrade < $minPassingGrade,
                                                        ])>
                                                            {{ number_format($finalGrade, 0) }}
                                                        </span>
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 4 + $evaluationTypes->count() }}" class="px-6 py-4 text-center text-gray-500">
                                                    No hay estudiantes matriculados en esta sección.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            @if(!$isLocked && !$isOutOfDate && $registrations->count() > 0)
                                <div class="flex justify-end mt-6 border-t pt-6">
                                    <x-danger-button wire:click="confirmFinalizeGrades" wire:loading.attr="disabled">
                                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                        Finalizar y Bloquear Registro de Notas
                                    </x-danger-button>
                                </div>
                            
                            @elseif($isLocked)
                                <div class="flex justify-between items-center mt-6 border-t pt-6">
                                    <p class="text-green-600 font-semibold text-center">
                                        <svg class="w-6 h-6 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        Este registro de notas ya ha sido finalizado.
                                    </p>
                                    <x-button wire:click="downloadFinalGradesPdf">
                                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 17a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-4V3a1 1 0 00-1-1H9a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10zm0 2V7h4v2h6V7h4v12H6zM10 9a1 1 0 112 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" /></svg>
                                        Descargar Acta Final (PDF)
                                    </x-button>
                                </div>
                            @endif

                        @else
                            <p class="text-center text-gray-500">Seleccione un curso para ver la matriz de notas.</p>
                        @endif
                        
                    @else
                        <div class="text-center text-red-500 p-10 border rounded-md">
                            <h3 class="mt-2 text-sm font-medium text-red-900">No hay un Periodo Académico Activo</h3>
                            <p class="mt-1 text-sm text-red-700">
                                No se puede registrar notas sin un periodo activo.
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
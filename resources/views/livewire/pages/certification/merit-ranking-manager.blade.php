<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Cuadro de Méritos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="md:flex justify-between items-end mb-4">
                        <div class="flex-1">
                            <x-label for="selectedPeriodId" value="Seleccione el Periodo Académico" />
                            <select id="selectedPeriodId" wire:model.live="selectedPeriodId" class="form-select mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Seleccione un periodo --</option>
                                @foreach($academicPeriods as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-danger-button wire:click="generateRanking" wire:loading.attr="disabled">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5H10.75V5z" clip-rule="evenodd" /></svg>
                            Generar/Actualizar Ranking del Periodo
                        </x-danger-button>
                    </div>
                    
                    <div class="flex justify-between items-center mb-4 mt-6">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por estudiante..." class="w-1/2" />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Posición</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estudiante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Programa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Créditos (Periodo)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Promedio Ponderado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($rankings as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-lg {{ $item->general_position <= 3 ? 'text-blue-600' : '' }}">
                                                #{{ $item->general_position }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $item->student->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $item->student->career->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $item->period_credits }}</td>
                                        <td class="px-6 py-4">{{ number_format($item->weighted_average, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">
                                            No hay un cuadro de méritos generado para este periodo.
                                            <br>
                                            (Asegúrese de que los docentes hayan finalizado el registro de notas)
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $rankings->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
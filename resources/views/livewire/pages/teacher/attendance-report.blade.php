<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de Asistencia Diaria
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="md:col-span-1">
                                <x-label for="selectedAssignmentId" value="1. Seleccione la Sección (Curso)" />
                                <select
                                    id="selectedAssignmentId"
                                    wire:model.live="selectedAssignmentId"
                                    class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                >
                                    <option value="">-- Seleccione un curso --</option>
                                    @foreach($assignments as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <x-label for="reportDate" value="2. Seleccione la Fecha" />
                                <x-input
                                    id="reportDate"
                                    type="date"
                                    class="mt-1 block w-full"
                                    wire:model.live="reportDate"
                                />
                            </div>

                            <div class="md:col-span-1 flex items-end">
                                <x-button
                                    wire:click="generatePdf"
                                    class="w-full justify-center"
                                    :disabled="$reportData->isEmpty()"
                                >
                                    <svg
                                        class="w-5 h-5 mr-2"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4 17a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-4V3a1 1 0 00-1-1H9a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10zm0 2V7h4v2h6V7h4v12H6zM10 9a1 1 0 112 0v6a1 1 0 11-2 0V9z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Descargar PDF
                                </x-button>
                            </div>
                        </div>

                        @if($reportData->count() > 0)
                            <h3 class="text-xl font-medium text-gray-900 mb-4 mt-6">
                                Vista Previa del Reporte
                            </h3>

                            <div class="overflow-x-auto border rounded-md">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium">N°</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium">Estudiante</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($reportData as $index => $data)
                                            <tr>
                                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4">{{ $data['name'] }}</td>
                                                
                                                @php
                                                    $class = 'px-6 py-4';
                                                    if ($data['status_key'] == 'present') $class .= ' text-green-600';
                                                    if ($data['status_key'] == 'absent') $class .= ' text-red-600 font-semibold';
                                                    if ($data['status_key'] == 'late') $class .= ' text-orange-600';
                                                    if ($data['status_key'] == 'justified') $class .= ' text-blue-600';
                                                    if ($data['status_key'] == 'no-data' || $data['status_key'] == 'scheduled') $class .= ' text-gray-500';
                                                @endphp
                                                
                                                <td class="{{ $class }}">
                                                    {{ $data['status_text'] }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center">
                                                    No hay estudiantes matriculados en esta sección.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-gray-500 mt-8">
                                Seleccione un curso y una fecha para ver la asistencia.
                            </p>
                        @endif

                    @else
                        <p class="text-center text-red-500">
                            No hay un periodo académico activo.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Reportes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        
                        <h3 class="text-xl font-medium text-gray-900 mb-4">
                            Reporte de Carga Horaria Docente (Periodo: {{ $activePeriod->name }})
                        </h3>
                        
                        <div class="flex justify-end mb-4 space-x-2">
                            <x-button wire:click="generateWorkloadPdf">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 17a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-4V3a1 1 0 00-1-1H9a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10zm0 2V7h4v2h6V7h4v12H6zM10 9a1 1 0 112 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" /></svg>
                                Descargar PDF
                            </x-button>
                            <x-secondary-button wire:click="generateWorkloadExcel">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V3zm2 2v10h12V5H4zM8 8a1 1 0 100 2h4a1 1 0 100-2H8z" /></svg>
                                Descargar Excel
                            </x-secondary-button>
                        </div>

                        <div class="overflow-x-auto border rounded-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Docente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Horas Asignadas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($workloadData as $data)
                                        <tr>
                                            <td class="px-6 py-4">{{ $data['name'] }}</td>
                                            <td class="px-6 py-4">{{ $data['code'] }}</td>
                                            <td class="px-6 py-4">{{ $data['hours'] }}</td>
                                            <td @class([
                                                'px-6 py-4',
                                                'text-green-600' => $data['status_key'] == 'ok',
                                                'text-red-600 font-semibold' => $data['status_key'] == 'overload',
                                                'text-orange-600' => $data['status_key'] == 'underload',
                                            ])>
                                                {{ $data['status_text'] }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center">No hay docentes activos.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                    @else
                        <p class="text-center text-red-500">No hay un periodo académico activo. No se pueden generar reportes.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
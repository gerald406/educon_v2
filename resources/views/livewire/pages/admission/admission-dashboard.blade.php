<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard de Admisión
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium">Total Postulantes</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalApplicants }}</div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm font-medium">Nuevos (7 días)</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $recentRegistrations }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Postulantes por Programa</h3>
                    <div class="overflow-y-auto max-h-60">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="divide-y divide-gray-200">
                                @forelse($applicantsByProgram as $item)
                                    <tr>
                                        <td class="py-2 text-sm text-gray-700">
                                            {{ $item->career_name }}
                                        </td>
                                        <td class="py-2 text-sm font-bold text-right">
                                            {{ $item->total }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4 text-center text-gray-500 text-sm">No hay datos para mostrar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Postulantes por Modalidad</h3>
                    <div class="overflow-y-auto max-h-60">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="divide-y divide-gray-200">
                                @forelse($applicantsByModality as $item)
                                    <tr>
                                        <td class="py-2 text-sm text-gray-700">
                                            {{ $item->modality_name }}
                                        </td>
                                        <td class="py-2 text-sm font-bold text-right">
                                            {{ $item->total }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4 text-center text-gray-500 text-sm">No hay datos para mostrar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Reporte A: Por Programa</h3>
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Descarga la lista general filtrada opcionalmente por carrera.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <x-label value="Filtrar por Programa (Opcional)" />
                            <select wire:model="reportA_careerId" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">-- Todos los Programas --</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}">{{ $career->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-button wire:click="downloadReportA" class="w-full justify-center bg-green-600 hover:bg-green-700">
                            Descargar Excel
                        </x-button>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Reporte B: Filtros Avanzados</h3>
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <x-label value="Programa de Estudio" />
                            <select wire:model="reportB_careerId" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">-- Todos --</option>
                                @foreach($careers as $career) <option value="{{ $career->id }}">{{ $career->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <x-label value="Modalidad" />
                                <select wire:model="reportB_modalityId" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">-- Todas --</option>
                                    @foreach($modalities as $mod) <option value="{{ $mod->id }}">{{ $mod->name }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label value="Turno" />
                                <select wire:model="reportB_shiftId" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">-- Todos --</option>
                                    @foreach($shifts as $shift) <option value="{{ $shift->id }}">{{ $shift->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        <x-button wire:click="downloadReportB" class="w-full justify-center mt-2">
                            Generar Reporte Personalizado
                        </x-button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
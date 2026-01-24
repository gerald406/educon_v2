<div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard de Admisión
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-blue-500 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Total Postulantes</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalApplicants }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-green-500 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Nuevos (7 días)</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $recentRegistrations }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Gráfico de Barras: Postulantes por Programa -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Postulantes por Programa</h3>
                    <div 
                        x-data="{
                            init() {
                                new Chart(this.$refs.canvas, {
                                    type: 'bar',
                                    data: {
                                        labels: @js($applicantsByProgram['labels']),
                                        datasets: [{
                                            label: 'Postulantes',
                                            data: @js($applicantsByProgram['data']),
                                            backgroundColor: 'rgba(79, 70, 229, 0.6)',
                                            borderColor: 'rgb(79, 70, 229)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            }
                        }"
                        class="h-64"
                    >
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

                <!-- Gráfico Torta: Postulantes por Modalidad -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Postulantes por Modalidad</h3>
                    <div 
                        x-data="{
                            init() {
                                new Chart(this.$refs.canvas, {
                                    type: 'doughnut',
                                    data: {
                                        labels: @js($applicantsByModality['labels']),
                                        datasets: [{
                                            label: 'Postulantes',
                                            data: @js($applicantsByModality['data']),
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.6)',
                                                'rgba(54, 162, 235, 0.6)',
                                                'rgba(255, 206, 86, 0.6)',
                                                'rgba(75, 192, 192, 0.6)',
                                                'rgba(153, 102, 255, 0.6)',
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                position: 'right',
                                            }
                                        }
                                    }
                                });
                            }
                        }"
                        class="h-64 flex justify-center"
                    >
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>
            </div>

            <!-- Reportes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Reporte A: Por Programa</h3>
                        <div class="p-2 bg-green-100 rounded-full text-green-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Descarga la lista general filtrada opcionalmente por carrera.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <x-label value="Filtrar por Programa (Opcional)" />
                            <select wire:model="reportA_careerId" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Todos los Programas --</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}">{{ $career->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-button wire:click="downloadReportA" class="w-full justify-center bg-green-600 hover:bg-green-700 transition duration-150">
                            Descargar Excel
                        </x-button>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Reporte B: Filtros Avanzados</h3>
                        <div class="p-2 bg-indigo-100 rounded-full text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <x-label value="Programa de Estudio" />
                            <select wire:model="reportB_careerId" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Todos --</option>
                                @foreach($careers as $career) <option value="{{ $career->id }}">{{ $career->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <x-label value="Modalidad" />
                                <select wire:model="reportB_modalityId" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Todas --</option>
                                    @foreach($modalities as $mod) <option value="{{ $mod->id }}">{{ $mod->name }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label value="Turno" />
                                <select wire:model="reportB_shiftId" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Todos --</option>
                                    @foreach($shifts as $shift) <option value="{{ $shift->id }}">{{ $shift->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        <x-button wire:click="downloadReportB" class="w-full justify-center mt-2 transition duration-150">
                            Generar Reporte Personalizado
                        </x-button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard de Admisión
            </h2>
            <button
                wire:click="loadMetrics"
                class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300
                       rounded-md text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0
                             0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Actualizar
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================
                 KPIs
                 ============================================ --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                {{-- Total Postulantes --}}
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Total Postulantes
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">
                                {{ $totalApplicants }}
                            </p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7
                                         20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002
                                         5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Nuevos últimos 7 días --}}
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Nuevos (7 días)
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">
                                {{ $recentRegistrations }}
                            </p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3
                                         20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Aprobados --}}
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-emerald-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Aprobados
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">
                                {{ $approvedApplicants }}
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-full">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pendientes --}}
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                En Proceso
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">
                                {{ $pendingApplicants }}
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ============================================
                 FILA 1 DE GRÁFICOS: Programa y Modalidad
                 ============================================ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Gráfico: Por Programa --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                        Postulantes por Programa de Estudios
                    </h3>
                    <div
                        wire:ignore
                        x-data="{
                            chart: null,
                            init() {
                                this.chart = new Chart(this.$refs.canvas, {
                                    type: 'bar',
                                    data: {
                                        labels: @js($applicantsByProgram['labels']),
                                        datasets: [{
                                            label: 'Postulantes',
                                            data: @js($applicantsByProgram['data']),
                                            backgroundColor: 'rgba(79, 70, 229, 0.7)',
                                            borderColor: 'rgb(79, 70, 229)',
                                            borderWidth: 1,
                                            borderRadius: 4,
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { display: false } },
                                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                                    }
                                });
                            }
                        }"
                        class="h-64">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

                {{-- Gráfico: Por Modalidad --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                        Postulantes por Modalidad
                    </h3>
                    <div
                        wire:ignore
                        x-data="{
                            chart: null,
                            init() {
                                this.chart = new Chart(this.$refs.canvas, {
                                    type: 'doughnut',
                                    data: {
                                        labels: @js($applicantsByModality['labels']),
                                        datasets: [{
                                            data: @js($applicantsByModality['data']),
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.7)',
                                                'rgba(54, 162, 235, 0.7)',
                                                'rgba(255, 206, 86, 0.7)',
                                                'rgba(75, 192, 192, 0.7)',
                                                'rgba(153, 102, 255, 0.7)',
                                            ],
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { position: 'right' } }
                                    }
                                });
                            }
                        }"
                        class="h-64 flex justify-center">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

            </div>

            {{-- ============================================
                 FILA 2 DE GRÁFICOS: Turno, Estado y Distrito
                 ============================================ --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Gráfico: Por Turno --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                        Por Turno
                    </h3>
                    <div
                        wire:ignore
                        x-data="{
                            init() {
                                new Chart(this.$refs.canvas, {
                                    type: 'pie',
                                    data: {
                                        labels: @js($applicantsByShift['labels']),
                                        datasets: [{
                                            data: @js($applicantsByShift['data']),
                                            backgroundColor: [
                                                'rgba(251, 191, 36, 0.7)',
                                                'rgba(59, 130, 246, 0.7)',
                                                'rgba(16, 185, 129, 0.7)',
                                            ],
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { position: 'bottom' } }
                                    }
                                });
                            }
                        }"
                        class="h-52">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

                {{-- Gráfico: Por Estado --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                        Por Estado del Proceso
                    </h3>
                    <div
                        wire:ignore
                        x-data="{
                            init() {
                                new Chart(this.$refs.canvas, {
                                    type: 'bar',
                                    data: {
                                        labels: @js($applicantsByStatus['labels']),
                                        datasets: [{
                                            label: 'Postulantes',
                                            data: @js($applicantsByStatus['data']),
                                            backgroundColor: @js($applicantsByStatus['colors']),
                                            borderWidth: 1,
                                            borderRadius: 4,
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { display: false } },
                                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                                    }
                                });
                            }
                        }"
                        class="h-52">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

                {{-- Gráfico: Top 10 Distritos --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                        Top 10 Distritos de Procedencia
                    </h3>
                    <div
                        wire:ignore
                        x-data="{
                            init() {
                                new Chart(this.$refs.canvas, {
                                    type: 'bar',
                                    data: {
                                        labels: @js($applicantsByDistrict['labels']),
                                        datasets: [{
                                            label: 'Postulantes',
                                            data: @js($applicantsByDistrict['data']),
                                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                                            borderWidth: 1,
                                            borderRadius: 4,
                                        }]
                                    },
                                    options: {
                                        indexAxis: 'y',
                                        responsive: true,
                                        plugins: { legend: { display: false } },
                                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
                                    }
                                });
                            }
                        }"
                        class="h-52">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

            </div>

            {{-- ============================================
                 TABLA RESUMEN POR OFERTA
                 ============================================ --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                        Resumen por Programa y Turno
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Turno</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-blue-500 uppercase">Registrado</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-yellow-500 uppercase">Evaluado</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-green-500 uppercase">Aprobado</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-red-500 uppercase">Sin Vacante</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Cancelado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($offeringSummary as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $row->career }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $row->shift }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-gray-900">
                                        {{ $row->total }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                            {{ $row->registered }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                            {{ $row->evaluated }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                            {{ $row->approved }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                            {{ $row->no_vacancy }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                            {{ $row->cancelled }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        No hay datos disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ============================================
                 REPORTES EXCEL
                 ============================================ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Reporte A --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                            Reporte A — Por Programa
                        </h3>
                        <div class="p-2 bg-green-100 rounded-full text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                         01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-4">
                        Lista general de postulantes filtrada por carrera.
                    </p>
                    <div class="space-y-3">
                        <div>
                            <x-label value="Filtrar por Programa (Opcional)" />
                            <select wire:model="reportA_careerId"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm
                                           focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Todos los Programas --</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}">{{ $career->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button wire:click="downloadReportA"
                                wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center items-center px-4 py-2
                                       bg-green-600 hover:bg-green-700 text-white text-sm font-semibold
                                       rounded-md transition-colors disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Descargar Excel
                        </button>
                    </div>
                </div>

                {{-- Reporte B --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                            Reporte B — Filtros Avanzados
                        </h3>
                        <div class="p-2 bg-indigo-100 rounded-full text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414
                                         6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293
                                         7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <x-label value="Programa de Estudio" />
                            <select wire:model="reportB_careerId"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm
                                           focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Todos --</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}">{{ $career->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <x-label value="Modalidad" />
                                <select wire:model="reportB_modalityId"
                                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm
                                               focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Todas --</option>
                                    @foreach($modalities as $mod)
                                        <option value="{{ $mod->id }}">{{ $mod->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label value="Turno" />
                                <select wire:model="reportB_shiftId"
                                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm
                                               focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Todos --</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button wire:click="downloadReportB"
                                wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center items-center px-4 py-2
                                       bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                                       rounded-md transition-colors disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Generar Reporte Personalizado
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Chart.js cargado directamente --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush
</div>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reporte de Asistencia — Examen de Admisión
            </h2>
            <div class="flex items-center gap-3">
                {{-- Botón actualizar --}}
                <button onclick="window.location.reload()"
                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300
                               rounded-md text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0
                                 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualizar
                </button>

                <a href="{{ route('admission.exam.attendance') }}" target="_blank"
                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700
                          text-white text-sm font-semibold rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14
                                 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Abrir Control de Ingreso
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================
                 RESUMEN GLOBAL
                 ============================================ --}}
            @php
                $totalAsignados  = $classrooms->sum('assignments_count');
                $totalAsistieron = $classrooms->sum('attended_count');
                $totalFaltaron   = $classrooms->sum('absent_count');
                $pct = $totalAsignados > 0
                    ? round(($totalAsistieron / $totalAsignados) * 100) : 0;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Total Asignados
                    </p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalAsignados }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $classrooms->count() }} aula(s)
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Asistieron
                    </p>
                    <p class="text-3xl font-bold text-green-700 mt-1">{{ $totalAsistieron }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                            <div class="bg-green-500 h-1.5 rounded-full"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-green-600">{{ $pct }}%</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Faltaron
                    </p>
                    <p class="text-3xl font-bold text-red-700 mt-1">{{ $totalFaltaron }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $totalAsignados > 0 ? round(($totalFaltaron / $totalAsignados) * 100) : 0 }}% de inasistencia
                    </p>
                </div>
            </div>

            {{-- ============================================
                 DETALLE POR AULA CON TABS
                 ============================================ --}}
            @foreach($classrooms as $room)
                @php
                    $total     = $room->assignments_count;
                    $asistio   = $room->attended_count;
                    $falto     = $room->absent_count;
                    $pctAula   = $total > 0 ? round(($asistio / $total) * 100) : 0;
                    $presentes = $room->assignments->filter(fn($a) => !is_null($a->attended_at))
                                      ->sortBy('attended_at');
                    $ausentes  = $room->assignments->filter(fn($a) => is_null($a->attended_at))
                                      ->sortBy(fn($a) => $a->applicant->user->lastname);
                @endphp

                <div class="bg-white rounded-lg shadow overflow-hidden"
                     x-data="{ tab: 'ausentes' }">

                    {{-- Header del aula --}}
                    {{-- DESPUÉS --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                                px-6 py-4 bg-gradient-to-r from-indigo-700 to-indigo-500 gap-2">
                        <div>
                            <span class="text-white font-bold text-sm tracking-wide">
                                {{ $room->pavilion->name }} — Aula {{ $room->room_number }}
                            </span>
                            @if($room->pavilion->location)
                                <span class="text-indigo-200 text-xs ml-2">
                                    {{ $room->pavilion->location }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 text-xs font-semibold flex-wrap">
                            <span class="bg-white/20 text-white px-2.5 py-1 rounded-full">
                                Total: {{ $total }}
                            </span>
                            <span class="bg-emerald-400/30 text-emerald-100 px-2.5 py-1 rounded-full">
                                ✓ {{ $asistio }} asistieron
                            </span>
                            <span class="bg-red-400/30 text-red-100 px-2.5 py-1 rounded-full">
                                ✗ {{ $falto }} faltaron
                            </span>
                            <span class="bg-white text-indigo-700 font-black px-2.5 py-1 rounded-full">
                                {{ $pctAula }}%
                            </span>
                        </div>
                    </div>

                    {{-- Barra de progreso --}}
                    <div class="w-full bg-red-100 h-2.5">
                        <div class="bg-green-500 h-2.5 transition-all duration-500"
                             style="width: {{ $pctAula }}%"></div>
                    </div>

                    {{-- TABS --}}
                    <div class="border-b border-gray-200 px-6 pt-3">
                        <div class="flex gap-1">
                            {{-- Tab Ausentes --}}
                            <button
                                @click="tab = 'ausentes'"
                                :class="tab === 'ausentes'
                                    ? 'border-b-2 border-red-500 text-red-600 font-semibold'
                                    : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm transition-colors focus:outline-none">
                                ✗ Ausentes
                                <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-xs font-bold"
                                      :class="tab === 'ausentes'
                                          ? 'bg-red-100 text-red-700'
                                          : 'bg-gray-100 text-gray-600'">
                                    {{ $falto }}
                                </span>
                            </button>

                            {{-- Tab Presentes --}}
                            <button
                                @click="tab = 'presentes'"
                                :class="tab === 'presentes'
                                    ? 'border-b-2 border-green-500 text-green-600 font-semibold'
                                    : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm transition-colors focus:outline-none">
                                ✓ Presentes
                                <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-xs font-bold"
                                      :class="tab === 'presentes'
                                          ? 'bg-green-100 text-green-700'
                                          : 'bg-gray-100 text-gray-600'">
                                    {{ $asistio }}
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- CONTENIDO TAB AUSENTES --}}
                    <div x-show="tab === 'ausentes'" class="px-6 py-4">
                        @if($ausentes->isNotEmpty())
                            <table class="min-w-full text-sm divide-y divide-gray-200">
                                <thead class="bg-red-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-red-600 uppercase w-8">#</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-red-600 uppercase">DNI</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-red-600 uppercase">Apellidos y Nombres</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-red-600 uppercase">Programa</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($ausentes->values() as $idx => $a)
                                        <tr class="hover:bg-red-50 transition-colors">
                                            <td class="px-4 py-2.5 text-gray-400 text-xs">
                                                {{ $idx + 1 }}
                                            </td>
                                            <td class="px-4 py-2.5 font-mono text-gray-700 text-xs">
                                                {{ $a->applicant->user->document_number }}
                                            </td>
                                            <td class="px-4 py-2.5 font-medium text-gray-900">
                                                {{ $a->applicant->user->lastname }},
                                                {{ $a->applicant->user->name }}
                                            </td>
                                            <td class="px-4 py-2.5 text-gray-500 text-xs">
                                                {{ $a->applicant->admissionOffering?->career?->name ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="py-6 text-center">
                                <div class="text-3xl mb-2">🎉</div>
                                <p class="text-green-600 font-semibold text-sm">
                                    Todos los postulantes de esta aula registraron su ingreso.
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- CONTENIDO TAB PRESENTES --}}
                    <div x-show="tab === 'presentes'" class="px-6 py-4">
                        @if($presentes->isNotEmpty())
                            <table class="min-w-full text-sm divide-y divide-gray-200">
                                <thead class="bg-green-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-green-600 uppercase w-8">#</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-green-600 uppercase">DNI</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-green-600 uppercase">Apellidos y Nombres</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-green-600 uppercase">Programa</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold
                                                   text-green-600 uppercase">Hora Ingreso</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($presentes->values() as $idx => $a)
                                        <tr class="hover:bg-green-50 transition-colors">
                                            <td class="px-4 py-2.5 text-gray-400 text-xs">
                                                {{ $idx + 1 }}
                                            </td>
                                            <td class="px-4 py-2.5 font-mono text-gray-700 text-xs">
                                                {{ $a->applicant->user->document_number }}
                                            </td>
                                            <td class="px-4 py-2.5 font-medium text-gray-900">
                                                {{ $a->applicant->user->lastname }},
                                                {{ $a->applicant->user->name }}
                                            </td>
                                            <td class="px-4 py-2.5 text-gray-500 text-xs">
                                                {{ $a->applicant->admissionOffering?->career?->name ?? '—' }}
                                            </td>
                                            <td class="px-4 py-2.5 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5
                                                             bg-green-100 text-green-700 rounded-full
                                                             text-xs font-semibold">
                                                    {{ \Carbon\Carbon::parse($a->attended_at)->format('H:i:s') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="py-6 text-center">
                                <div class="text-3xl mb-2">⏳</div>
                                <p class="text-gray-500 text-sm">
                                    Aún no hay postulantes registrados en esta aula.
                                </p>
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach

            {{-- Pie con fecha de actualización --}}
            <p class="text-center text-xs text-gray-400 pb-4">
                Última actualización: {{ now()->format('d/m/Y H:i:s') }}
                &nbsp;·&nbsp;
                <button onclick="window.location.reload()"
                        class="text-indigo-500 hover:underline">
                    Actualizar ahora
                </button>
            </p>

        </div>
    </div>
</x-app-layout>
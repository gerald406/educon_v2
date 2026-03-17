<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Sesiones de Aprendizaje
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $syllabus->teacherAssignment->didacticUnit->name }}
                    · Sección {{ $syllabus->teacherAssignment->section }}
                    · {{ $syllabus->teacherAssignment->academicPeriod->name }}
                </p>
            </div>
            <a href="{{ route('teacher.my-syllabi') }}"
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Mis Sílabos
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @foreach($syllabus->indicators as $indicator)
                    {{-- Encabezado del indicador --}}
                    <div class="mb-2 mt-6 first:mt-0">
                        <div class="bg-gray-100 border border-gray-300 rounded-lg px-4 py-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">
                                Indicador de Logro
                            </span>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                {{ $indicator->description }}
                            </p>
                        </div>
                    </div>

                    {{-- Tabla de sesiones de este indicador --}}
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-16">
                                        Ses.
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Tema / Actividad
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Logro de la Sesión
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-28">
                                        Fecha
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-28">
                                        Estado
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase w-32">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($indicator->units as $unit)
                                    @php
                                        $session = $unit->learningSession;
                                        $status  = $session?->status ?? 'pending';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">

                                        {{-- N° Sesión --}}
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8
                                                         bg-teal-100 text-teal-800 rounded-full text-xs font-bold">
                                                {{ $unit->session_number }}
                                            </span>
                                        </td>

                                        {{-- Tema --}}
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $unit->name }}
                                            </div>
                                            @if($unit->content)
                                                <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">
                                                    {!! strip_tags($unit->content) !!}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Logro --}}
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ Str::limit($unit->learning_outcome, 80) }}
                                        </td>

                                        {{-- DESPUÉS --}}
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $unit->week_range ?? ($unit->execution_date?->format('d/m/Y') ?? '—') }}
                                        </td>

                                        {{-- Estado --}}
                                        <td class="px-4 py-3">
                                            @switch($status)
                                                @case('pending')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-semibold">
                                                        Pendiente
                                                    </span>
                                                    @break
                                                @case('completed')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">
                                                        Completada
                                                    </span>
                                                    @break
                                                @case('executed')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                                                        Ejecutada
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                            {{-- Editar sesión --}}
                                            <a href="{{ route('teacher.sessions.edit', [$syllabus->id, $unit->id]) }}"
                                               class="inline-flex items-center px-2.5 py-1.5 bg-indigo-600
                                                      hover:bg-indigo-700 text-white text-xs font-semibold
                                                      rounded-md transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            {{-- PDF sesión --}}
                                            @if($status !== 'pending')
                                                <a href="{{ route('teacher.sessions.pdf', [$syllabus->id, $unit->id]) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center px-2.5 py-1.5 bg-red-600
                                                          hover:bg-red-700 text-white text-xs font-semibold
                                                          rounded-md transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                              d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116
                                                                 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                    PDF
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach

                {{-- Resumen de progreso --}}
                @php
                    $totalUnits     = $syllabus->indicators->flatMap->units->count();
                    $completedUnits = $syllabus->indicators->flatMap->units
                        ->filter(fn($u) => in_array($u->learningSession?->status, ['completed', 'executed']))
                        ->count();
                    $percent = $totalUnits > 0 ? round(($completedUnits / $totalUnits) * 100) : 0;
                @endphp
                <div class="mt-6 border-t pt-4">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-600 font-medium">
                            Progreso: {{ $completedUnits }} / {{ $totalUnits }} sesiones completadas
                        </span>
                        <span class="text-sm font-bold text-teal-700">{{ $percent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-teal-600 h-2.5 rounded-full transition-all"
                             style="width: {{ $percent }}%"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
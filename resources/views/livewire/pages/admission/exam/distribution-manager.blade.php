<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Distribución de Aulas — Examen de Admisión
            </h2>
            @if($distributedCount > 0)
                <button wire:click="resetDistribution"
                        wire:confirm="¿Estás seguro? Esto eliminará TODAS las asignaciones actuales."
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Resetear Todo
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ══════════════════════════════════════════════════════
                 PANEL PRINCIPAL: FILTROS + TABLA + ASIGNACIÓN
            ══════════════════════════════════════════════════════ --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- ── FILA 1: FILTROS ── --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    {{-- Modalidad --}}
                    <div>
                        <x-label value="Modalidad" />
                        <select wire:model.live="filterModality" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">— Todas —</option>
                            @foreach($modalities as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Programa --}}
                    <div>
                        <x-label value="Programa de Estudios" />
                        <select wire:model.live="filterCareer" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">— Todos —</option>
                            @foreach($careers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Turno --}}
                    <div>
                        <x-label value="Turno" />
                        <select wire:model.live="filterShift" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">— Todos —</option>
                            @foreach($shifts as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Búsqueda --}}
                    <div>
                        <x-label value="Buscar (DNI / Apellido / Nombre)" />
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search"
                                   type="text" placeholder="Ej: 12345678 o García…"
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm pl-9">
                            <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- ── FILA 2: BARRA DE ACCIÓN --}}
                <div class="flex flex-col md:flex-row justify-between items-end bg-gray-50 p-4 rounded-lg border border-gray-200 gap-4 mb-4">

                    {{-- Asignación manual --}}
                    <div class="w-full md:w-2/3 flex items-end gap-2">
                        <div class="flex-grow">
                            <x-label value="Asignar seleccionados al aula:" />
                            <select wire:model="targetClassroom" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">— Seleccionar Aula Disponible —</option>
                                @foreach($availableClassrooms as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->pavilion->name }} – {{ $room->room_number }}
                                        (Libres: {{ $room->capacity - $room->assignments_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button wire:click="assignManual"
                                wire:loading.attr="disabled"
                                class="bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-bold py-2 px-4 rounded shadow whitespace-nowrap text-sm">
                            <span wire:loading.remove wire:target="assignManual">Asignar</span>
                            <span wire:loading wire:target="assignManual">Asignando…</span>
                        </button>
                    </div>

                    <div class="hidden md:block h-10 w-px bg-gray-300 mx-2"></div>

                    {{-- Distribución automática --}}
                    <div class="w-full md:w-1/3">
                        <button wire:click="autoDistribute"
                                wire:loading.attr="disabled"
                                wire:confirm="¿Distribuir aleatoriamente TODOS los postulantes listados (con los filtros actuales)?"
                                class="w-full bg-green-600 hover:bg-green-700 disabled:opacity-60 text-white font-bold py-2 px-4 rounded shadow flex justify-center items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <span wire:loading.remove wire:target="autoDistribute">Distribución Automática</span>
                            <span wire:loading wire:target="autoDistribute">Procesando…</span>
                        </button>
                    </div>
                </div>

                {{-- ── FILA 3: INFO + PAGINACIÓN POR PÁGINA ── --}}
                <div class="flex justify-between items-center mb-2 text-sm text-gray-600">
                    <div>
                        {{-- Contador de seleccionados --}}
                        @if($selectAllMode)
                            <span class="font-semibold text-indigo-700">
                                ✔ Todos los {{ $totalFiltered }} postulantes seleccionados
                            </span>
                            <button wire:click="clearSelection" class="ml-3 text-xs text-red-500 hover:underline">Limpiar selección</button>
                        @elseif(count($selectedApplicants) > 0)
                            <span class="font-semibold text-blue-700">
                                {{ count($selectedApplicants) }} seleccionado(s)
                            </span>
                            <button wire:click="clearSelection" class="ml-3 text-xs text-red-500 hover:underline">Limpiar</button>
                        @else
                            <span class="text-gray-400">Ninguno seleccionado</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-gray-500">
                            Total pendientes: <strong>{{ $totalFiltered }}</strong>
                        </span>
                        <div class="flex items-center gap-1">
                            <label class="text-xs text-gray-500">Mostrar</label>
                            <select wire:model.live="perPage" class="text-xs border-gray-300 rounded shadow-sm py-1">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ── BANNER "Seleccionar todos los filtrados" (estilo Gmail) ── --}}
                @if($selectAll && !$selectAllMode && $totalFiltered > count($selectedApplicants))
                    <div class="bg-indigo-50 border border-indigo-200 rounded-md px-4 py-2 mb-3 text-sm text-indigo-800 flex items-center justify-between">
                        <span>
                            Solo los <strong>{{ count($selectedApplicants) }}</strong> postulantes de esta página están seleccionados.
                        </span>
                        <button wire:click="activateSelectAllMode"
                                class="ml-4 font-semibold text-indigo-600 hover:text-indigo-800 underline whitespace-nowrap">
                            Seleccionar los {{ $totalFiltered }} restantes
                        </button>
                    </div>
                @endif

                @if($selectAllMode)
                    <div class="bg-indigo-100 border border-indigo-300 rounded-md px-4 py-2 mb-3 text-sm text-indigo-900 flex items-center justify-between">
                        <span>✔ <strong>Todos los {{ $totalFiltered }} postulantes</strong> con estos filtros están seleccionados.</span>
                        <button wire:click="clearSelection" class="ml-4 text-xs text-red-600 hover:underline">Cancelar</button>
                    </div>
                @endif

                {{-- ── TABLA DE POSTULANTES ── --}}
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 w-10">
                                    <input type="checkbox"
                                           wire:model.live="selectAll"
                                           :checked="$selectAllMode"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellidos y Nombres</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modalidad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Turno</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($applicants as $applicant)
                                <tr class="hover:bg-indigo-50 transition-colors {{ in_array((string)$applicant->id, array_map('strval', $selectedApplicants)) || $selectAllMode ? 'bg-indigo-50' : '' }}">
                                    <td class="px-4 py-3">
                                        @if($selectAllMode)
                                            <input type="checkbox" checked disabled class="rounded border-gray-300 text-indigo-600 shadow-sm opacity-60">
                                        @else
                                            <input type="checkbox"
                                                   value="{{ $applicant->id }}"
                                                   wire:model.live="selectedApplicants"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-mono font-medium text-gray-800">
                                        {{ $applicant->user->document_number ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                        {{ trim(($applicant->user->lastname ?? '') . ' ' . ($applicant->user->name ?? '')) }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        {{ $applicant->admissionModality->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        {{ $applicant->admissionOffering->career->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($applicant->admissionOffering?->shift)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700">
                                                {{ $applicant->admissionOffering->shift->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                        </svg>
                                        <p class="font-medium">No hay postulantes pendientes</p>
                                        <p class="text-xs mt-1">Ajusta los filtros o todos ya fueron asignados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Mostrando {{ $applicants->firstItem() ?? 0 }}–{{ $applicants->lastItem() ?? 0 }}
                        de {{ $totalFiltered }} pendientes
                    </div>
                    <div>{{ $applicants->links() }}</div>
                </div>

            </div>{{-- /panel principal --}}

            {{-- ══════════════════════════════════════════════════════
                 ESTADO DE AULAS
            ══════════════════════════════════════════════════════ --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex justify-between items-center">
                    <span>Estado de Aulas</span>
                    <span class="text-sm font-normal text-gray-500">
                        Ocupación Total:
                        <strong class="{{ $usedCapacity >= $totalCapacity && $totalCapacity > 0 ? 'text-red-600' : 'text-gray-700' }}">
                            {{ $usedCapacity }}
                        </strong> / {{ $totalCapacity }}
                    </span>
                </h3>

                @if($classroomsStatus->isEmpty())
                    <p class="text-center text-gray-400 py-8 text-sm">No hay aulas configuradas. Ve a <a href="{{ route('admission.exam.infrastructure') }}" class="text-indigo-600 underline">Infraestructura</a> para crearlas.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($classroomsStatus as $room)
                            @php
                                $ocupados  = $room->assignments_count;
                                $capacidad = $room->capacity;
                                $pct       = $capacidad > 0 ? ($ocupados / $capacidad) * 100 : 0;
                                $barColor  = $pct >= 100 ? 'bg-red-500' : ($pct >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                $textColor = $pct >= 100 ? 'text-red-600' : 'text-gray-700';
                            @endphp
                            <div class="border rounded-lg p-4 hover:shadow-md transition bg-white">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="text-xs text-gray-400 uppercase font-semibold">{{ $room->pavilion->name }}</div>
                                        <div class="text-lg font-bold text-gray-800">Aula {{ $room->room_number }}</div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold {{ $textColor }}">{{ $ocupados }}</span>
                                        <span class="text-xs text-gray-400 block">/ {{ $capacidad }}</span>
                                    </div>
                                </div>

                                <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                                    <div class="{{ $barColor }} h-2 rounded-full transition-all" style="width: {{ min($pct, 100) }}%"></div>
                                </div>

                                @if($ocupados > 0)
                                    <div class="grid grid-cols-1 gap-2">
                                        <a href="{{ route('admission.exam.door-list', $room->id) }}" target="_blank"
                                           class="flex items-center justify-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded text-xs hover:bg-gray-200 transition">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                            </svg>
                                            Lista de Puerta
                                        </a>
                                        <a href="{{ route('admission.exam.answer-sheets', $room->id) }}" target="_blank"
                                           class="flex items-center justify-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded text-xs hover:bg-indigo-100 transition">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            Hoja de Respuestas
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-1 text-xs text-gray-400 italic">Sin asignaciones</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Mis Sílabos (Periodo {{ $activePeriod?->name ?? 'N/A' }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso Asignado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sección</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avance</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($assignments as $assignment)
                                        @php
                                            $syllabus = $assignment->syllabus;
                                            $status = $syllabus?->status ?? 'draft';
                                            // Lógica simple para calcular avance (opcional)
                                            $hasContent = $syllabus && $syllabus->sumilla; 
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900">{{ $assignment->didacticUnit->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $assignment->didacticUnit->module->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $assignment->section }} - {{ $assignment->shift->name ?? '' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span @class([
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                    'bg-gray-100 text-gray-800' => $status == 'draft',
                                                    'bg-yellow-100 text-yellow-800' => $status == 'submitted',
                                                    'bg-green-100 text-green-800' => $status == 'approved',
                                                    'bg-red-100 text-red-800' => $status == 'observed',
                                                ])>
                                                    @switch($status)
                                                        @case('draft') Borrador @break
                                                        @case('submitted') En Revisión @break
                                                        @case('approved') Aprobado @break
                                                        @case('observed') Observado @break
                                                        @default Sin Iniciar
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @if($hasContent)
                                                    <span class="text-green-600">En progreso</span>
                                                @else
                                                    <span class="text-gray-400">--</span>
                                                @endif
                                            </td>
                                            {{-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('teacher.my-syllabi.edit', $assignment->id) }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    Gestionar
                                                </a>
                                            </td> --}}
                                            {{-- REEMPLAZAR solo el bloque <td> de Acciones --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">

                                                {{-- Botón: Gestionar Sílabo --}}
                                                <a href="{{ route('teacher.my-syllabi.edit', $assignment->id) }}"
                                                class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent
                                                        rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                                        hover:bg-indigo-700 transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                    Sílabo
                                                </a>

                                                {{-- Botón: Sesiones de Aprendizaje (solo si el sílabo está aprobado) --}}
                                                @if($syllabus && $status === 'approved')
                                                    <a href="{{ route('teacher.sessions.list', $syllabus->id) }}"
                                                    class="inline-flex items-center px-3 py-2 bg-teal-600 border border-transparent
                                                            rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                                            hover:bg-teal-700 transition">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                                                    M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                        </svg>
                                                        Sesiones
                                                    </a>
                                                @else
                                                    {{-- Deshabilitado si el sílabo no está aprobado --}}
                                                    <span title="El sílabo debe estar aprobado para gestionar sesiones"
                                                        class="inline-flex items-center px-3 py-2 bg-gray-200 border border-transparent
                                                                rounded-md font-semibold text-xs text-gray-400 uppercase tracking-widest
                                                                cursor-not-allowed">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                                                    M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                        </svg>
                                                        Sesiones
                                                    </span>
                                                @endif

                                            </td>
                                        </tr>
                                        
                                        {{-- Mostrar observación si existe --}}
                                        @if($status == 'observed' && $syllabus->observation_notes)
                                            <tr class="bg-red-50">
                                                <td colspan="5" class="px-6 py-3 text-sm text-red-700">
                                                    <strong>Observación del Coordinador:</strong> {{ $syllabus->observation_notes }}
                                                </td>
                                            </tr>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                No tienes cursos asignados en este periodo académico.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay un Periodo Académico Activo</h3>
                            <p class="mt-1 text-sm text-gray-500">Contacte al administrador del sistema.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ELIMINAMOS EL MODAL DE SUBIDA DE PDF PORQUE YA NO SE USA --}}
</div>
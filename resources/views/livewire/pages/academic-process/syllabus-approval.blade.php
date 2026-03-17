<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Aprobación de Sílabos
                <span class="text-gray-500 font-normal text-base ml-2">
                    Periodo: {{ $activePeriod?->name ?? 'N/A' }}
                </span>
            </h2>

            {{-- Indicador de carrera del coordinador --}}
            @if($coordinatorCareerId && !auth()->user()->hasRole('Administrador'))
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-100
                             text-purple-800 text-sm font-medium rounded-full border border-purple-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                 C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                 C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                 C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Viendo sílabos de tu carrera asignada
                </span>
            @elseif(auth()->user()->hasRole('Administrador'))
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-100
                             text-red-800 text-sm font-medium rounded-full border border-red-200">
                    Administrador — Todas las carreras
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">

                    @if(!$activePeriod)
                        {{-- Sin periodo activo --}}
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-3 text-red-500 font-medium">No hay un periodo académico activo.</p>
                        </div>

                    @elseif(!$coordinatorCareerId && !auth()->user()->hasRole('Administrador'))
                        {{-- Coordinador sin carrera asignada --}}
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <p class="mt-3 text-yellow-700 font-medium">
                                No tienes una carrera asignada como coordinador.
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Contacta al administrador para que te asigne una carrera.
                            </p>
                        </div>

                    @else
                        {{-- CONTENIDO PRINCIPAL --}}

                        {{-- Barra de búsqueda --}}
                        <div class="flex justify-between items-center mb-6">
                            <x-input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Buscar por curso o docente..."
                                class="w-1/2" />

                            <span class="text-sm text-gray-500">
                                {{ $syllabi->total() }} sílabo(s) pendiente(s)
                            </span>
                        </div>

                        {{-- Tabla --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Curso
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Docente
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Carrera / Sección
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Enviado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Vista Previa
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($syllabi as $syllabus)
                                        @php
                                            $assignment = $syllabus->teacherAssignment;
                                            $career     = $assignment->didacticUnit
                                                            ?->module
                                                            ?->studyPlan
                                                            ?->career;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">

                                            {{-- Curso --}}
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $assignment->didacticUnit->name ?? '—' }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-0.5">
                                                    Sem. {{ $assignment->didacticUnit->semester ?? '—' }}
                                                </div>
                                            </td>

                                            {{-- Docente --}}
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    {{ trim(($assignment->teacher->user->name ?? '') . ' ' . ($assignment->teacher->user->lastname ?? '')) }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-0.5">
                                                    {{ $assignment->teacher->user->email ?? '—' }}
                                                </div>
                                            </td>

                                            {{-- Carrera / Sección --}}
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-700">
                                                    {{ $career->name ?? '—' }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-0.5">
                                                    Sección: {{ $assignment->section ?? '—' }}
                                                    @if($assignment->shift)
                                                        · {{ $assignment->shift->name }}
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Fecha de envío --}}
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                @if($syllabus->submitted_at)
                                                    {{ $syllabus->submitted_at->format('d/m/Y') }}
                                                    <div class="text-xs text-gray-400">
                                                        {{ $syllabus->submitted_at->diffForHumans() }}
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- Vista previa PDF --}}
                                            <td class="px-6 py-4">
                                                <a href="{{ route('teacher.syllabus.pdf', $syllabus->id) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center gap-1 text-indigo-600
                                                          hover:text-indigo-900 text-sm font-medium
                                                          hover:underline transition-colors">
                                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                              d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                    Ver PDF
                                                </a>
                                            </td>

                                            {{-- Acciones --}}
                                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                                {{-- Aprobar --}}
                                                <button
                                                    wire:click="approve({{ $syllabus->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="approve({{ $syllabus->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600
                                                           hover:bg-green-700 text-white text-xs font-semibold
                                                           rounded-md transition-colors disabled:opacity-50">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Aprobar
                                                </button>

                                                {{-- Observar --}}
                                                <button
                                                    wire:click="openObserveModal({{ $syllabus->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500
                                                           hover:bg-yellow-600 text-white text-xs font-semibold
                                                           rounded-md transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                                    </svg>
                                                    Observar
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-500">
                                                    No hay sílabos pendientes de aprobación.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">{{ $syllabi->links() }}</div>

                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         MODAL DE OBSERVACIÓN
         ============================================ --}}
    <x-dialog-modal wire:model.live="isObserveModalOpen">

        <x-slot name="title">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                Observar Sílabo
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">

                {{-- Info del sílabo observado --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-xs text-yellow-700 font-medium uppercase tracking-wide mb-1">
                        Curso
                    </p>
                    <p class="font-semibold text-gray-900">
                        {{ $syllabusToObserve?->teacherAssignment?->didacticUnit?->name ?? '—' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Docente:
                        {{ trim(
                            ($syllabusToObserve?->teacherAssignment?->teacher?->user?->name ?? '') . ' ' .
                            ($syllabusToObserve?->teacherAssignment?->teacher?->user?->lastname ?? '')
                        ) }}
                    </p>
                </div>

                {{-- Textarea de observación --}}
                <div>
                    <x-label for="observationNotes"
                             value="Motivo de la Observación (visible para el docente) *" />
                    <textarea
                        id="observationNotes"
                        wire:model.blur="observationNotes"
                        rows="5"
                        placeholder="Describe detalladamente qué debe corregir el docente..."
                        class="mt-1 block w-full border-gray-300 focus:border-yellow-500
                               focus:ring-yellow-500 rounded-md shadow-sm text-sm resize-none">
                    </textarea>
                    <x-input-error for="observationNotes" class="mt-1" />
                    <p class="text-xs text-gray-400 mt-1">
                        Mínimo 10 caracteres. El docente verá este mensaje en su editor de sílabo.
                    </p>
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <button
                wire:click="saveObservation"
                wire:loading.attr="disabled"
                wire:target="saveObservation"
                class="ml-3 inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600
                       text-white text-sm font-semibold rounded-md transition-colors
                       disabled:opacity-50 disabled:cursor-not-allowed">
                <svg wire:loading wire:target="saveObservation"
                     class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                     fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Guardar Observación
            </button>
        </x-slot>

    </x-dialog-modal>
</div>
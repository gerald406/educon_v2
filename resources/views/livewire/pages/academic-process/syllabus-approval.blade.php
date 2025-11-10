<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Aprobación de Sílabos (Periodo {{ $activePeriod?->name ?? 'N/A' }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="flex justify-between items-center mb-4">
                            <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por curso o docente..." class="w-1/2" />
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Curso</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Docente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Sección</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Archivo</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($syllabi as $syllabus)
                                        @php
                                            $assignment = $syllabus->teacherAssignment;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4">{{ $assignment->didacticUnit->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $assignment->teacher->user->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $assignment->section }}</td>
                                            <td class="px-6 py-4">
                                                <a href="{{ asset('storage/' . $syllabus->file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                    Ver PDF (v{{ $syllabus->version }})
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                <x-button wire:click="approve({{ $syllabus->id }})" class="bg-green-600 hover:bg-green-700">
                                                    Aprobar
                                                </x-button>
                                                <x-danger-button wire:click="openObserveModal({{ $syllabus->id }})"> Observar
                                                </x-danger-button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center">No hay sílabos pendientes de aprobación.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">{{ $syllabi->links() }}</div>

                    @else
                        <p class="text-center text-red-500">No hay un periodo académico activo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
        <x-dialog-modal wire:model.live="isObserveModalOpen">
            <x-slot name="title">
                Observar Sílabo
            </x-slot>

            <x-slot name="content">
                <p class="mb-2">Está observando el sílabo de:</p>
                <p class="font-semibold text-lg mb-4">
                    {{ $syllabusToObserve?->teacherAssignment->didacticUnit->name }}
                </p>
                
                <div class="col-span-1">
                    <x-label for="observationNotes" value="Motivo de la Observación (Visible para el docente)" />
                    <textarea id="observationNotes" wire:model.blur="observationNotes" rows="4" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    <x-input-error for="observationNotes" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="closeModal">
                    Cancelar
                </x-secondary-button>
                <x-danger-button class="ms-3" wire:click="saveObservation" wire:loading.attr="disabled">
                    Guardar Observación
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

</div>
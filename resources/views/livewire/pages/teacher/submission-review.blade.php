<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Revisión de Entregas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activePeriod)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-label for="selectedAssignmentId" value="1. Seleccione la Sección (Curso)" />
                                <select id="selectedAssignmentId" wire:model.live="selectedAssignmentId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Seleccione un curso --</option>
                                    @foreach($assignments as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <x-label for="selectedActivityId" value="2. Seleccione la Actividad" />
                                <select id="selectedActivityId" wire:model.live="selectedActivityId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    @if($activities->isEmpty()) disabled @endif>
                                    <option value="">-- Seleccione una actividad --</option>
                                    @foreach($activities as $id => $title)
                                        <option value="{{ $id }}">{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if($selectedActivityId)
                            <h3 class="text-xl font-medium text-gray-900 mb-4 mt-6">
                                Entregas Recibidas
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium">Estudiante</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium">Fecha Entrega</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium">Archivo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium">Nota</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($submissionsData as $submission)
                                            <tr>
                                                <td class="px-6 py-4">{{ $submission->registration->student->user->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ $submission->submission_date->format('d/m/Y h:i A') }}</td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ asset('storage/' . $submission->submission_file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                        Descargar Entrega
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($submission->status == 'reviewed')
                                                        <span class="font-bold text-lg">{{ $submission->grade }}</span>
                                                    @else
                                                        <span class="text-sm text-gray-500">Pendiente</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <x-button wire:click="openGradeModal({{ $submission->id }})">
                                                        {{ $submission->status == 'reviewed' ? 'Recalificar' : 'Calificar' }}
                                                    </x-button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center">No hay entregas para esta actividad.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">{{ $submissionsData->links() }}</div>
                        @else
                            <p class="text-center text-gray-500 mt-8">Seleccione un curso y una actividad para ver las entregas.</p>
                        @endif

                    @else
                        <p class="text-center text-red-500">No hay un periodo académico activo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            Calificar Entrega
        </x-slot>

        <x-slot name="content">
            @if($editingSubmission)
                <div class="mb-4">
                    <p><strong>Estudiante:</strong> {{ $editingSubmission->registration->student->user->name }}</p>
                    <p><strong>Actividad:</strong> {{ $editingSubmission->academicActivity->title }}</p>
                    <a href="{{ asset('storage/' . $editingSubmission->submission_file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                        Descargar Archivo del Estudiante
                    </a>
                </div>
            @endif
            
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-label for="grade" value="Nota (Sobre 20)" />
                    <x-input id="grade" type="number" step="0.5" min="0" max="20" class="mt-1 block w-full" wire:model.blur="grade" />
                    <x-input-error for="grade" class="mt-2" />
                </div>
                <div>
                    <x-label for="teacherComments" value="Comentarios / Retroalimentación (Opcional)" />
                    <textarea id="teacherComments" wire:model.blur="teacherComments" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <x-input-error for="teacherComments" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="saveGrade" wire:loading.attr="disabled">
                Guardar Calificación
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
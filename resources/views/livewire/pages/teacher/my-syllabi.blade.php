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
                                        <th class="px-6 py-3 text-left text-xs font-medium">Curso Asignado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Sección</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Estado del Sílabo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium">Archivo</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($assignments as $assignment)
                                        @php
                                            $syllabus = $assignment->syllabus;
                                            $status = $syllabus?->status ?? 'draft';
                                            $file_url = $syllabus?->file_url ?? null;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4">{{ $assignment->didacticUnit->name }}</td>
                                            <td class="px-6 py-4">{{ $assignment->section }}</td>
                                            <td class="px-6 py-4">
                                                <span @class([
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                    'bg-gray-100 text-gray-800' => $status == 'draft' && !$file_url,
                                                    'bg-yellow-100 text-yellow-800' => $status == 'pending_approval',
                                                    'bg-green-100 text-green-800' => $status == 'approved',
                                                    'bg-red-100 text-red-800' => $status == 'observed',
                                                ])>
                                                    @if(!$file_url) No subido @else {{ $status }} @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($file_url)
                                                    <a href="{{ asset('storage/' . $file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                        Ver PDF (v{{ $syllabus->version }})
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                @if($status != 'approved')
                                                    <x-button wire:click="openSyllabusModal({{ $assignment->id }})">
                                                        {{ $file_url ? 'Actualizar PDF' : 'Subir PDF' }}
                                                    </x-button>
                                                @else
                                                    <span class="text-sm text-gray-500">Aprobado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center">No tiene cursos asignados en este periodo.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-red-500">No hay un periodo académico activo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            Gestionar Sílabo para: {{ $selectedAssignment?->didacticUnit->name }} (Sec. {{ $selectedAssignment?->section }})
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                @if ($currentSyllabus?->file_url)
                    <div class="p-4 bg-gray-100 rounded-md">
                        <p class="font-semibold">Archivo actual:</p>
                        <a href="{{ asset('storage/' . $currentSyllabus->file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                            Ver PDF (v{{ $currentSyllabus->version }})
                        </a>
                        <x-danger-button wire:click="deleteSyllabus" class="ml-4">
                            Eliminar archivo
                        </x-danger-button>
                        <p class="text-sm text-gray-600 mt-2">Para reemplazar, simplemente suba uno nuevo.</p>
                    </div>
                @endif
                
                <div>
                    <x-label for="pdfUpload" value="{{ $currentSyllabus?->file_url ? 'Reemplazar Sílabo (PDF)' : 'Subir Sílabo (PDF)' }}" />
                    <x-input id="pdfUpload" type="file" class="mt-1 block w-full" wire:model="pdfUpload" accept=".pdf" />
                    <x-input-error for="pdfUpload" class="mt-2" />
                    
                    <div wire:loading wire:target="pdfUpload" class="mt-2 text-sm text-gray-500">
                        Cargando archivo...
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="saveSyllabus" wire:loading.attr="disabled">
                Subir y Enviar a Revisión
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
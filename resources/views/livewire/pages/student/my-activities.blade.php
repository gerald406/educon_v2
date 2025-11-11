<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Actividades
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($currentEnrollment)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Mis Cursos ({{ $activePeriod->name }})
                                </h3>
                                <div class="space-y-2">
                                    @forelse($enrolledCourses as $registration)
                                        <button 
                                            wire:click="$set('selectedRegistrationId', {{ $registration->id }})" 
                                            @class([
                                                'w-full text-left p-3 rounded-md border',
                                                'bg-indigo-50 border-indigo-300' => $selectedRegistrationId == $registration->id,
                                                'hover:bg-gray-50' => $selectedRegistrationId != $registration->id,
                                            ])>
                                            <span class="font-semibold">{{ $registration->teacherAssignment->didacticUnit->name }}</span>
                                            <span class="block text-sm text-gray-600">Sección {{ $registration->teacherAssignment->section }}</span>
                                        </button>
                                    @empty
                                        <p class="text-sm text-gray-500">No estás inscrito en ningún curso este periodo.</p>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                @if($selectedRegistrationId)
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        Actividades del Curso
                                    </h3>
                                    <div class="space-y-4">
                                        @forelse($activities as $activity)
                                            @php
                                                $submission = $submissions->get($activity->id);
                                            @endphp
                                            <div class="p-4 border rounded-md">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-semibold text-lg text-gray-900">{{ $activity->title }}</h4>
                                                        <p class="text-xs text-gray-500">
                                                            Fecha Límite: {{ $activity->due_date->format('d/m/Y h:i A') }}
                                                        </p>
                                                    </div>
                                                    @if($submission)
                                                        @if($submission->status == 'reviewed')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Calificado ({{ $submission->grade }})
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                Entregado
                                                            </span>
                                                        @endif
                                                    @elseif($activity->due_date < now())
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Vencido
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-700 mt-2">{{ $activity->description }}</p>
                                                <div class="mt-4">
                                                    <x-button wire:click="openSubmitModal({{ $activity->id }})">
                                                        {{ $submission ? 'Actualizar Entrega' : 'Entregar Actividad' }}
                                                    </x-button>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-gray-500 text-center">No hay actividades para este curso.</p>
                                        @endforelse
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 p-10">Selecciona un curso de la izquierda para ver las actividades.</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-center text-red-500">No se encontró una matrícula activa para este periodo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            Entregar Actividad: {{ $activityToSubmit?->title }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                @if ($activityToSubmit)
                    <p class="text-sm text-gray-600">
                        Fecha límite: {{ $activityToSubmit->due_date->format('d/m/Y h:i A') }}
                    </p>
                @endif
                
                <div>
                    <x-label for="fileUpload" value="Adjuntar Archivo (PDF, Word, ZIP, etc.)" />
                    <x-input id="fileUpload" type="file" class="mt-1 block w-full" wire:model="fileUpload" />
                    <x-input-error for="fileUpload" class="mt-2" />
                    <div wire:loading wire:target="fileUpload" class="mt-2 text-sm text-gray-500">Cargando...</div>
                </div>
                
                <div>
                    <x-label for="studentComments" value="Comentarios (Opcional)" />
                    <textarea id="studentComments" wire:model.blur="studentComments" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <x-input-error for="studentComments" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="saveSubmission" wire:loading.attr="disabled">
                Confirmar Entrega
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
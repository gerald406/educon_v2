<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Postulantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, email o código..." class="w-1/2" />
                        <x-button wire:click="openCreateModal">
                            Registrar Nuevo Postulante
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Cód. Postulante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre Completo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Programa al que Postula</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nota Examen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4">{{ $user->applicant->code }}</td>
                                        <td class="px-6 py-4">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->applicant->career->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $user->applicant->exam_score ?? '--' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $user->applicant->application_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-1">
                                            <x-button wire:click="confirmApprove({{ $user->id }})" class="bg-green-600 hover:bg-green-700">
                                                Aprobar
                                            </x-button>
                                            
                                            <x-secondary-button wire:click="openEditModal({{ $user->id }})">
                                                Editar
                                            </x-secondary-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron postulantes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingUser ? 'Editar Postulante' : 'Registrar Nuevo Postulante' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="user.name" value="Nombre Completo" />
                    <x-input id="user.name" type="text" class="mt-1 block w-full" wire:model.blur="user.name" />
                    <x-input-error for="user.name" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="user.email" value="Email" />
                    <x-input id="user.email" type="email" class="mt-1 block w-full" wire:model.blur="user.email" />
                    <x-input-error for="user.email" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="applicant.code" value="Código de Postulante" />
                    <x-input id="applicant.code" type="text" class="mt-1 block w-full" wire:model.blur="applicant.code" />
                    <x-input-error for="applicant.code" class="mt-2" />
                </div>

                <div class="col-span-2 border-t mt-2"></div>
                
                <div class="col-span-1">
                    <x-label for="selectedCareerId" value="Programa al que Postula" />
                    <select id="selectedCareerId" wire:model.live="selectedCareerId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @foreach($careers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="selectedCareerId" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="selectedStudyPlanId" value="Plan de Estudio" />
                    <select id="selectedStudyPlanId" wire:model="selectedStudyPlanId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        @if($availableStudyPlans->isEmpty()) disabled @endif>
                        <option value="">-- Seleccione plan --</option>
                        @foreach($availableStudyPlans as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="selectedStudyPlanId" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="applicant.admission_type" value="Tipo de Admisión" />
                    <select id="applicant.admission_type" wire:model="applicant.admission_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="regular">Regular</option>
                        <option value="extraordinary">Extraordinario</option>
                        <option value="external_transfer">Traslado Externo</option>
                        <option value="internal_transfer">Traslado Interno</option>
                    </select>
                    <x-input-error for="applicant.admission_type" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="applicant.exam_score" value="Nota Examen (Opcional)" />
                    <x-input id="applicant.exam_score" type="number" step="0.5" class="mt-1 block w-full" wire:model.blur="applicant.exam_score" />
                    <x-input-error for="applicant.exam_score" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                Guardar
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
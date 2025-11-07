<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Estudiantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, email o código..." />
                        <x-button wire:click="openCreateModal">
                            Registrar Nuevo Estudiante
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Cód. Estudiante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre Completo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Programa (Carrera)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4">{{ $user->student->code ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">{{ $user->student->career->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $user->student->academic_status == 'regular',
                                                'bg-yellow-100 text-yellow-800' => $user->student->academic_status == 'irregular',
                                                'bg-blue-100 text-blue-800' => $user->student->academic_status == 'graduated',
                                                'bg-red-100 text-red-800' => $user->student->academic_status == 'withdrawn',
                                            ])>
                                                {{ ucfirst($user->student->academic_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $user->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $user->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron estudiantes.</td>
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
            {{ $editingUser ? 'Editar Estudiante' : 'Registrar Nuevo Estudiante' }}
        </x-slot>

        <x-slot name="content">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Datos de Acceso (Usuario)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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
                    <x-label for="user.password" value="Contraseña" />
                    <x-input id="user.password" type="password" class="mt-1 block w-full" wire:model.blur="user.password" 
                             placeholder="{{ $editingUser ? 'Dejar en blanco para no cambiar' : '' }}" />
                    <x-input-error for="user.password" class="mt-2" />
                </div>
            </div>

            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Datos Académicos (Estudiante)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1">
                    <x-label for="selectedCareerId" value="Programa (Carrera)" />
                    <select id="selectedCareerId" wire:model.live="selectedCareerId" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccione un programa --</option>
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
                        <option value="">-- Seleccione un plan --</option>
                        @foreach($availableStudyPlans as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="selectedStudyPlanId" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="student.code" value="Código de Estudiante" />
                    <x-input id="student.code" type="text" class="mt-1 block w-full" wire:model.blur="student.code" />
                    <x-input-error for="student.code" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="student.admission_date" value="Fecha de Admisión" />
                    <x-input id="student.admission_date" type="date" class="mt-1 block w-full" wire:model.blur="student.admission_date" />
                    <x-input-error for="student.admission_date" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="student.current_semester" value="Semestre Actual" />
                    <x-input id="student.current_semester" type="number" class="mt-1 block w-full" wire:model.blur="student.current_semester" />
                    <x-input-error for="student.current_semester" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="student.academic_status" value="Estado Académico" />
                    <select id="student.academic_status" wire:model="student.academic_status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="regular">Regular</option>
                        <option value="irregular">Irregular</option>
                        <option value="enrollment_reserved">Reserva de Matrícula</option>
                        <option value="withdrawn">Retirado</option>
                        <option value="graduated">Egresado</option>
                    </select>
                    <x-input-error for="student.academic_status" class="mt-2" />
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
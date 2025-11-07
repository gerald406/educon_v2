<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Docentes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, email o código..." />
                        <x-button wire:click="openCreateModal">
                            Registrar Nuevo Docente
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Cód. Docente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Nombre Completo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Contrato</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4">{{ $user->teacher->code }}</td>
                                        <td class="px-6 py-4">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">{{ $user->teacher->contract_type }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $user->teacher->status == 'active',
                                                'bg-yellow-100 text-yellow-800' => $user->teacher->status == 'leave',
                                                'bg-red-100 text-red-800' => $user->teacher->status == 'terminated',
                                            ])>
                                                {{ ucfirst($user->teacher->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $user->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $user->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron docentes.</td>
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
            {{ $editingUser ? 'Editar Docente' : 'Registrar Nuevo Docente' }}
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

            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Datos Profesionales (Docente)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1">
                    <x-label for="teacher.code" value="Código de Docente" />
                    <x-input id="teacher.code" type="text" class="mt-1 block w-full" wire:model.blur="teacher.code" />
                    <x-input-error for="teacher.code" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="teacher.academic_degree" value="Grado Académico" />
                    <x-input id="teacher.academic_degree" type="text" class="mt-1 block w-full" wire:model.blur="teacher.academic_degree" placeholder="Ej. Magister en..." />
                    <x-input-error for="teacher.academic_degree" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-label for="teacher.specialty" value="Especialidad" />
                    <x-input id="teacher.specialty" type="text" class="mt-1 block w-full" wire:model.blur="teacher.specialty" placeholder="Ej. Desarrollo de Software" />
                    <x-input-error for="teacher.specialty" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="teacher.contract_type" value="Tipo de Contrato" />
                    <select id="teacher.contract_type" wire:model="teacher.contract_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="contracted">Contratado</option>
                        <option value="permanent">Nombrado</option>
                        <option value="hourly">Por Horas</option>
                    </select>
                    <x-input-error for="teacher.contract_type" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="teacher.status" value="Estado" />
                    <select id="teacher.status" wire:model="teacher.status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="active">Activo</option>
                        <option value="leave">Licencia</option>
                        <option value="terminated">Cesado</option>
                    </select>
                    <x-input-error for="teacher.status" class="mt-2" />
                </div>
                <div class="col-span-1">
                    <x-label for="teacher.preparation_day" value="Día de Preparación (Opcional)" />
                    <select id="teacher.preparation_day" wire:model="teacher.preparation_day" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Ninguno --</option>
                        <option value="monday">Lunes</option>
                        <option value="tuesday">Martes</option>
                        <option value="wednesday">Miércoles</option>
                        <option value="thursday">Jueves</option>
                        <option value="friday">Viernes</option>
                        <option value="saturday">Sábado</option>
                    </select>
                    <x-input-error for="teacher.preparation_day" class="mt-2" />
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
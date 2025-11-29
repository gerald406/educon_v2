<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Usuarios del Sistema (Staff)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-4">
                    <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar usuario..." class="w-1/2" />
                    <x-button wire:click="openCreateModal">Nuevo Usuario</x-button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Roles Asignados</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->roles as $role)
                                                <span class="px-2 py-1 text-xs rounded-full {{ $role->name == 'Administrador' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <x-button wire:click="openEditModal({{ $user->id }})">Editar</x-button>
                                        @if($user->id !== auth()->id())
                                            <x-danger-button wire:click="deleteUser({{ $user->id }})">Eliminar</x-danger-button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-gray-500">No se encontraron usuarios administrativos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">{{ $editingUser ? 'Editar Usuario' : 'Crear Usuario' }}</x-slot>
        
        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label>Nombre Completo</x-label>
                    <x-input type="text" class="w-full" wire:model="name" />
                    <x-input-error for="name" class="mt-1" />
                </div>
                <div>
                    <x-label>Correo Electrónico</x-label>
                    <x-input type="email" class="w-full" wire:model="email" />
                    <x-input-error for="email" class="mt-1" />
                </div>
                <div>
                    <x-label>Contraseña {{ $editingUser ? '(Dejar en blanco para mantener)' : '' }}</x-label>
                    <x-input type="password" class="w-full" wire:model="password" />
                    <x-input-error for="password" class="mt-1" />
                </div>
                
                <div class="border-t pt-4">
                    <x-label class="mb-2">Asignar Roles</x-label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($roles as $role)
                            <label class="flex items-center space-x-2 border p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" value="{{ $role->name }}" wire:model="selectedRoles" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error for="selectedRoles" class="mt-1" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="save">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
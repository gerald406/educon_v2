<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Roles y Permisos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-4">
                    <p class="text-sm text-gray-600">Administre los perfiles de acceso al sistema.</p>
                    <x-button wire:click="openCreateModal">Crear Nuevo Rol</x-button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                        <div class="border rounded-lg p-4 hover:shadow-md transition flex flex-col justify-between {{ $role->name == 'Administrador' ? 'bg-blue-50 border-blue-200' : 'bg-white' }}">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-lg text-gray-800">{{ $role->name }}</h3>
                                    @if($role->name == 'Administrador')
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Sistema</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    {{ $role->users_count }} usuarios asignados
                                </p>
                            </div>
                            
                            <div class="mt-4 flex justify-end space-x-2 border-t pt-2">
                                @if($role->name !== 'Administrador')
                                    <button wire:click="openEditModal({{ $role->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Configurar Permisos
                                    </button>
                                    <button wire:click="deleteRole({{ $role->id }})" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                        Eliminar
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">Acceso Total</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="2xl">
        <x-slot name="title">
            {{ $editingRole ? 'Configurar Rol: ' . $editingRole->name : 'Crear Nuevo Rol' }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-6">
                <x-label value="Nombre del Rol" />
                <x-input type="text" class="w-full mt-1" wire:model="name" placeholder="Ej. Asistente de Biblioteca" />
                <x-input-error for="name" class="mt-1" />
            </div>

            <div class="border-t pt-4">
                <h4 class="font-bold text-gray-700 mb-3">Asignar Permisos de Acceso</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-96 overflow-y-auto p-2">
                    @foreach($allPermissionsGrouped as $groupName => $permissions)
                        @if(count($permissions) > 0)
                            <div class="bg-gray-50 p-3 rounded-md border">
                                <h5 class="font-bold text-sm text-blue-700 mb-2 uppercase">{{ $groupName }}</h5>
                                <div class="space-y-2">
                                    @foreach($permissions as $perm)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" 
                                                   value="{{ $perm->name }}" 
                                                   wire:model="selectedPermissions"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700">{{ $perm->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="save">Guardar Cambios</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
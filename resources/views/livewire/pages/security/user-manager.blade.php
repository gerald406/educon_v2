<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Usuarios del Sistema (Staff)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- BARRA SUPERIOR --}}
                <div class="flex justify-between items-center mb-4">
                    <x-input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por nombre, apellido o email..."
                        class="w-1/2" />
                    <x-button wire:click="openCreateModal">
                        + Nuevo Usuario
                    </x-button>
                </div>
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg
                            flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <span class="font-semibold">Módulo de Staff Administrativo.</span>
                        Para registrar <strong>Docentes y Coordinadores</strong> use
                        <a href="{{ route('people.teachers') }}"
                        class="underline font-semibold hover:text-blue-900">
                            Gestión de Docentes
                        </a>.
                    </div>
                </div>

                {{-- TABLA --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Roles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carrera Asignada</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr>
                                    {{-- Nombre completo --}}
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ trim($user->name . ' ' . $user->lastname) }}
                                        </div>
                                    </td>

                                    {{-- Email --}}
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $user->email }}
                                    </td>

                                    {{-- Roles --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->roles as $role)
                                                <span class="px-2 py-1 text-xs rounded-full font-semibold
                                                    {{ $role->name === 'Administrador' ? 'bg-red-100 text-red-800' :
                                                      ($role->name === 'Coordinador'   ? 'bg-purple-100 text-purple-800' :
                                                      ($role->name === 'Docente'        ? 'bg-blue-100 text-blue-800' :
                                                       'bg-gray-100 text-gray-700')) }}">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>

                                    {{-- Carrera coordinada (solo si es Coordinador) --}}
                                    <td class="px-6 py-4 text-sm">
                                        @if($user->hasRole('Coordinador') && $user->careerCoordinator?->is_active)
                                            <span class="inline-flex items-center gap-1 text-purple-700 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                {{ $user->careerCoordinator->career->name ?? '—' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                        <x-button wire:click="openEditModal({{ $user->id }})">
                                            Editar
                                        </x-button>
                                        @if($user->id !== auth()->id())
                                            <x-danger-button
                                                wire:click="$dispatch('swal:confirm', {
                                                    title: '¿Eliminar usuario?',
                                                    text: 'Esta acción no se puede revertir.',
                                                    icon: 'warning',
                                                    onConfirmed: 'deleteUser',
                                                    id: {{ $user->id }}
                                                })">
                                                Eliminar
                                            </x-danger-button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-500">
                                        No se encontraron usuarios administrativos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
         MODAL CREAR / EDITAR USUARIO
         ============================================ --}}
    <x-dialog-modal wire:model="isModalOpen">

        <x-slot name="title">
            {{ $editingUser ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">

                {{-- Nombres --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label value="Nombres *" />
                        <x-input type="text" class="w-full mt-1" wire:model="name"
                                 placeholder="Nombres" />
                        <x-input-error for="name" class="mt-1" />
                    </div>
                    <div>
                        <x-label value="Apellidos" />
                        <x-input type="text" class="w-full mt-1" wire:model="lastname"
                                 placeholder="Apellidos" />
                        <x-input-error for="lastname" class="mt-1" />
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <x-label value="Correo Electrónico *" />
                    <x-input type="email" class="w-full mt-1" wire:model="email"
                             placeholder="correo@institucion.edu.pe" />
                    <x-input-error for="email" class="mt-1" />
                </div>

                {{-- Contraseña --}}
                <div>
                    <x-label value="{{ $editingUser ? 'Contraseña (dejar en blanco para mantener)' : 'Contraseña *' }}" />
                    <x-input type="password" class="w-full mt-1" wire:model="password"
                             placeholder="Mínimo 8 caracteres" />
                    <x-input-error for="password" class="mt-1" />
                </div>

                {{-- Roles --}}
                <div class="border-t pt-4">
                    <x-label value="Asignar Roles *" class="mb-2" />
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($roles as $role)
                            <label class="flex items-center space-x-2 border p-2 rounded
                                          hover:bg-gray-50 cursor-pointer
                                          {{ in_array($role->name, $selectedRoles) ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200' }}">
                                <input
                                    type="checkbox"
                                    value="{{ $role->name }}"
                                    wire:model.live="selectedRoles"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700 font-medium">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error for="selectedRoles" class="mt-1" />
                </div>

                {{-- SELECTOR DE CARRERA (solo visible si rol Coordinador está seleccionado) --}}
                @if($this->isCoordinatorSelected)
                    <div class="border border-purple-200 bg-purple-50 rounded-lg p-4 mt-2">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <x-label value="Carrera que Coordinará *" class="text-purple-800 font-bold" />
                        </div>

                        <select
                            wire:model="selectedCareerId"
                            class="w-full border-purple-300 focus:border-purple-500
                                   focus:ring-purple-500 rounded-md shadow-sm text-sm">
                            <option value="">-- Seleccionar Carrera --</option>
                            @foreach($careers as $career)
                                <option value="{{ $career->id }}">
                                    {{ $career->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="selectedCareerId" class="mt-1" />

                        <p class="text-xs text-purple-600 mt-2">
                            ⚠️ Si la carrera ya tiene un coordinador activo, será reemplazado automáticamente.
                        </p>
                    </div>
                @endif

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                <svg class="w-4 h-4 mr-1 animate-spin hidden" wire:loading wire:target="save"
                     fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Guardar
            </x-button>
        </x-slot>

    </x-dialog-modal>

    {{-- Listener para confirmar eliminación --}}
    @script
    <script>
        Livewire.on('deleteUser', (event) => {
            $wire.deleteUser(event.id);
        });
    </script>
    @endscript
</div>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Programas de Estudio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div class="w-full md:w-1/3 relative">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por código o nombre..." class="w-full pl-10" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        @if($institutions->count() > 1)
                            <select wire:model.live="institution_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($institutions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        @endif

                        <x-button wire:click="create">
                            {{ __('Nuevo Programa') }}
                        </x-button>
                    </div>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Programa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($careers as $career)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $career->code }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="font-medium text-gray-900">{{ $career->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $career->institution->name ?? 'Sin Institución' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $career->duration_semesters }} Semestres
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $career->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $career->status === 'active' ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="edit({{ $career->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Editar
                                        </button>
                                        <button wire:click="confirmDelete({{ $career->id }})" class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        No se encontraron programas de estudio registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $careers->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            {{ $editingCareer ? 'Editar Programa de Estudio' : 'Nuevo Programa de Estudio' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-1 md:col-span-2">
                    <x-label for="institution_id" value="Institución" />
                    <select id="institution_id" wire:model="institution_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Seleccione...</option>
                        @foreach($institutions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="institution_id" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="code" value="Código (Siglas)" />
                    <x-input id="code" type="text" class="mt-1 block w-full uppercase" wire:model="code" placeholder="EJ. APSTI" />
                    <x-input-error for="code" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="duration_semesters" value="Duración (Semestres)" />
                    <x-input id="duration_semesters" type="number" class="mt-1 block w-full" wire:model="duration_semesters" min="2" max="10" />
                    <x-input-error for="duration_semesters" class="mt-2" />
                </div>

                <div class="col-span-1 md:col-span-2">
                    <x-label for="name" value="Nombre del Programa" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Ej. Arquitectura de Plataformas..." />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-1 md:col-span-2">
                    <x-label for="degree_awarded" value="Título Otorgado (Opcional)" />
                    <x-input id="degree_awarded" type="text" class="mt-1 block w-full" wire:model="degree_awarded" />
                    <x-input-error for="degree_awarded" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="authorization_resolution" value="Resolución (Opcional)" />
                    <x-input id="authorization_resolution" type="text" class="mt-1 block w-full" wire:model="authorization_resolution" />
                    <x-input-error for="authorization_resolution" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-3" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">
                    {{ $editingCareer ? 'Actualizar' : 'Guardar' }}
                </span>
                <span wire:loading wire:target="save">
                    Guardando...
                </span>
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
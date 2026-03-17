<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Colegios de Procedencia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div class="w-full md:w-1/2">
                        <x-input type="text" wire:model.live.debounce.300ms="search" 
                                 placeholder="Buscar por nombre o código modular..." class="w-full" />
                    </div>
                    <div>
                        <x-button wire:click="create">
                            + Nuevo Colegio
                        </x-button>
                    </div>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cód. Modular</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nivel / Gestión</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($schools as $school)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono text-sm font-bold text-gray-700">
                                        {{ $school->modular_code }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $school->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div>{{ $school->d_niv_mod }}</div>
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ $school->management_type == 'Pública' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $school->management_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if(isset($school->location)) {{ $school->location->nombdep }} / {{ $school->location->nombprov }}
                                        @else
                                            <span class="text-gray-400">Cod: {{ $school->ubigeo_code }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button wire:click="edit({{ $school->id }})" class="text-indigo-600 hover:text-indigo-900 font-medium">Editar</button>
                                        <button wire:click="confirmDelete({{ $school->id }})" class="text-red-600 hover:text-red-900 font-medium">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        No se encontraron colegios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $schools->links() }}</div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            {{ $editingSchool ? 'Editar Colegio' : 'Registrar Nuevo Colegio' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-1">
                    <x-label value="Código Modular" />
                    <x-input type="text" wire:model="modular_code" class="w-full mt-1" maxlength="20" placeholder="Ej. 0541234" />
                    <x-input-error for="modular_code" />
                </div>

                <div class="col-span-1">
                    <x-label value="Gestión" />
                    <select wire:model="management_type" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="Pública">Pública</option>
                        <option value="Privada">Privada</option>
                    </select>
                    <x-input-error for="management_type" />
                </div>

                <div class="col-span-2">
                    <x-label value="Nombre del Colegio" />
                    <x-input type="text" wire:model="name" class="w-full mt-1" placeholder="Ej. IE. SAN JUAN BOSCO" />
                    <x-input-error for="name" />
                </div>

                <div class="col-span-2">
                    <x-label value="Nivel / Modalidad" />
                    <select wire:model="d_niv_mod" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="Secundaria">Secundaria (EBR)</option>
                        <option value="Secundaria de Adultos">Secundaria de Adultos</option>
                        <option value="Básica Alternativa-Avanzado">Básica Alternativa - Avanzado (EBA)</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <x-input-error for="d_niv_mod" />
                </div>

                <div class="col-span-2 relative">
                    <x-label value="Ubicación (Distrito)" />
                    
                    @if($selectedUbigeoName)
                        <div class="flex items-center mt-1 p-2 bg-indigo-50 border border-indigo-200 rounded text-indigo-700">
                            <span class="flex-1 font-bold">{{ $selectedUbigeoName }}</span>
                            <button wire:click="$set('selectedUbigeoName', '')" class="text-red-500 hover:text-red-700 ml-2">
                                Cambiar
                            </button>
                        </div>
                    @else
                        <x-input type="text" wire:model.live.debounce.400ms="ubigeoSearch" 
                                 class="w-full mt-1" placeholder="Buscar distrito o provincia..." />
                        
                        @if(!empty($ubigeoResults))
                            <div class="absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto mt-1">
                                @foreach($ubigeoResults as $ubigeo)
                                    <div wire:click="selectUbigeo('{{ $ubigeo['iddist'] }}', '{{ $ubigeo['nombdep'] }} - {{ $ubigeo['nombprov'] }} - {{ $ubigeo['nombdist'] }}')"
                                         class="p-2 hover:bg-gray-100 cursor-pointer text-sm">
                                        <div class="font-bold text-gray-700">{{ $ubigeo['nombdist'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $ubigeo['nombdep'] }} / {{ $ubigeo['nombprov'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                    <x-input-error for="ubigeo_code" />
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="save">Guardar Colegio</x-button>
        </x-slot>
    </x-dialog-modal>

    @script
    <script>
        Livewire.on('swal:confirm', (data) => {
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(data.onConfirmed, { id: data.id });
                }
            });
        });
    </script>
    @endscript
</div>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Préstamos de Biblioteca
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-medium text-gray-900 mb-4">Registrar Nuevo Préstamo</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative z-20">
                            <x-label for="userSearch" value="Buscar Usuario (Estudiante/Docente)" />
                            <x-input id="userSearch" type="text" class="mt-2 block w-full" 
                                     wire:model.live.debounce.300ms="userSearch" 
                                     placeholder="Buscar por nombre o email..." 
                                     autocomplete="off" />
                            @if($usersFound->count() > 0)
                                <div class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                    @foreach($usersFound as $user)
                                        <div class="p-2 hover:bg-gray-100 cursor-pointer"
                                             wire:click="selectUser({{ $user->id }})">
                                            {{ $user->name }} ({{ $user->email }})
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <x-input-error for="selectedUser" class="mt-2" />
                        </div>
                        
                        <div class="relative z-10">
                            <x-label for="resourceSearch" value="Buscar Recurso (Libro, Tesis, etc.)" />
                            <x-input id="resourceSearch" type="text" class="mt-1 block w-full" 
                                     wire:model.live.debounce.300ms="resourceSearch" 
                                     placeholder="Buscar por título, autor o código..." 
                                     autocomplete="off" />
                            @if($resourcesFound->count() > 0)
                                <div class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                    @foreach($resourcesFound as $resource)
                                        <div class="p-2 hover:bg-gray-100 cursor-pointer"
                                             wire:click="selectResource({{ $resource->id }})">
                                            {{ $resource->title }} ({{ $resource->copies_available }} disp.)
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <x-input-error for="selectedResource" class="mt-2" />
                        </div>
                    </div>

                    @if($selectedUser && $selectedResource)
                        <div class="mt-6 p-4 bg-gray-50 rounded-md grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Usuario:</span>
                                <p class="font-semibold">{{ $selectedUser->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Recurso:</span>
                                <p class="font-semibold">{{ $selectedResource->title }}</p>
                            </div>
                            <div>
                                <x-label for="due_date" value="Fecha de Devolución" />
                                <x-input id="due_date" type="date" class="mt-1 block w-full" wire:model="due_date" />
                                <x-input-error for="due_date" class="mt-2" />
                            </div>
                        </div>
                        <div class="flex justify-end gap-4 mt-4">
                            <x-secondary-button wire:click="resetSelection">Cancelar</x-secondary-button>
                            <x-button wire:click="saveLoan">Confirmar Préstamo</x-button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-medium text-gray-900 mb-4">Préstamos Activos y Vencidos</h3>
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar préstamo..." class="w-1/2" />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Recurso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Préstamo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Devolución</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($activeLoans as $loan)
                                    <tr>
                                        <td class="px-6 py-4">{{ $loan->libraryResource->title }}</td>
                                        <td class="px-6 py-4">{{ $loan->user->name }}</td>
                                        <td class="px-6 py-4">{{ $loan->loan_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $loan->due_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $loan->status == 'active',
                                                'bg-red-100 text-red-800' => $loan->status == 'overdue',
                                            ])>
                                                {{ $loan->status == 'active' ? 'Activo' : 'Vencido' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button disabled>Registrar Devolución</x-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No hay préstamos activos o vencidos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $activeLoans->links() }}</div>
                </div>
            </div>

        </div>
    </div>
</div>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Infraestructura de Examen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button wire:click="switchTab('pavilions')" 
                                class="{{ $activeTab === 'pavilions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Pabellones
                        </button>

                        <button wire:click="switchTab('classrooms')"
                                class="{{ $activeTab === 'classrooms' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Aulas
                        </button>
                    </nav>
                </div>

                @if($activeTab === 'pavilions')
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Listado de Pabellones</h3>
                            <x-button wire:click="createPavilion">
                                + Nuevo Pabellón
                            </x-button>
                        </div>

                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Aulas</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($pavilions as $pav)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 font-bold text-gray-700">{{ $pav->name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $pav->location ?? '-' }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">
                                                    {{ $pav->classrooms_count }} Aulas
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                <button wire:click="editPavilion({{ $pav->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Editar</button>
                                                <button wire:click="deletePavilion({{ $pav->id }})" class="text-red-600 hover:text-red-900 text-sm font-medium">Eliminar</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay pabellones registrados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $pavilions->links() }}</div>
                    </div>
                @endif

                @if($activeTab === 'classrooms')
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Listado de Aulas</h3>
                            <x-button wire:click="createClassroom">
                                + Nueva Aula
                            </x-button>
                        </div>

                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pabellón</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Aula</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Capacidad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($classrooms as $room)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $room->pavilion->name }}</td>
                                            <td class="px-6 py-4 font-bold text-gray-800 text-lg">{{ $room->room_number }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full">
                                                    {{ $room->capacity }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $room->description ?? '-' }}</td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                <button wire:click="editClassroom({{ $room->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Editar</button>
                                                <button wire:click="deleteClassroom({{ $room->id }})" class="text-red-600 hover:text-red-900 text-sm font-medium">Eliminar</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay aulas registradas.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $classrooms->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isPavilionModalOpen">
        <x-slot name="title">{{ $pav_id ? 'Editar Pabellón' : 'Nuevo Pabellón' }}</x-slot>
        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label value="Nombre del Pabellón" />
                    <x-input type="text" wire:model="pav_name" class="w-full mt-1" placeholder="Ej. Pabellón A" />
                    <x-input-error for="pav_name" />
                </div>
                <div>
                    <x-label value="Ubicación / Referencia" />
                    <x-input type="text" wire:model="pav_location" class="w-full mt-1" placeholder="Ej. Frente a la biblioteca" />
                    <x-input-error for="pav_location" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isPavilionModalOpen', false)">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="savePavilion">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="isClassroomModalOpen">
        <x-slot name="title">{{ $room_id ? 'Editar Aula' : 'Nueva Aula' }}</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-label value="Pabellón" />
                    <select wire:model="room_pavilion_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Seleccionar --</option>
                        @foreach($allPavilions as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="room_pavilion_id" />
                </div>
                
                <div>
                    <x-label value="Número / Nombre" />
                    <x-input type="text" wire:model="room_number" class="w-full mt-1" placeholder="Ej. 101" />
                    <x-input-error for="room_number" />
                </div>
                
                <div>
                    <x-label value="Capacidad (Alumnos)" />
                    <x-input type="number" wire:model="room_capacity" class="w-full mt-1" min="1" />
                    <x-input-error for="room_capacity" />
                </div>
                
                <div class="col-span-2">
                    <x-label value="Descripción Adicional" />
                    <x-input type="text" wire:model="room_description" class="w-full mt-1" placeholder="Ej. Aula con proyector" />
                    <x-input-error for="room_description" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isClassroomModalOpen', false)">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="saveClassroom">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
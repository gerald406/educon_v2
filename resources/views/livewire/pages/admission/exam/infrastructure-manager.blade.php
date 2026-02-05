<div>
    <x-slot name="header"><h2 class="text-xl font-semibold">Infraestructura de Examen</h2></x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-6" x-data="{ tab: 'pavilions' }">
            
            <div class="flex border-b mb-4">
                <button @click="tab = 'pavilions'" :class="{'border-b-2 border-indigo-500 text-indigo-600': tab === 'pavilions'}" class="px-4 py-2 font-medium">Pabellones</button>
                <button @click="tab = 'classrooms'" :class="{'border-b-2 border-indigo-500 text-indigo-600': tab === 'classrooms'}" class="px-4 py-2 font-medium">Aulas</button>
            </div>

            <div x-show="tab === 'pavilions'">
                <div class="mb-4 text-right">
                    <x-button wire:click="$set('isPavilionModalOpen', true)">+ Nuevo Pabellón</x-button>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th>Nombre</th><th>Ubicación</th><th>Aulas</th><th>Acciones</th></tr></thead>
                    <tbody>
                        @foreach($pavilions as $pav)
                            <tr>
                                <td class="px-6 py-4">{{ $pav->name }}</td>
                                <td class="px-6 py-4">{{ $pav->location }}</td>
                                <td class="px-6 py-4">{{ $pav->classrooms_count }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="editPavilion({{ $pav->id }})" class="text-blue-600">Editar</button>
                                    <button wire:click="deletePavilion({{ $pav->id }})" class="text-red-600 ml-2">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $pavilions->links() }}
            </div>

            <div x-show="tab === 'classrooms'" style="display: none;">
                <div class="mb-4 text-right">
                    <x-button wire:click="$set('isClassroomModalOpen', true)">+ Nueva Aula</x-button>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th>Pabellón</th><th>Número</th><th>Capacidad</th><th>Acciones</th></tr></thead>
                    <tbody>
                        @foreach($classrooms as $room)
                            <tr>
                                <td class="px-6 py-4">{{ $room->pavilion->name }}</td>
                                <td class="px-6 py-4">{{ $room->room_number }}</td>
                                <td class="px-6 py-4">{{ $room->capacity }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="editClassroom({{ $room->id }})" class="text-blue-600">Editar</button>
                                    <button wire:click="deleteClassroom({{ $room->id }})" class="text-red-600 ml-2">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $classrooms->links() }}
            </div>
        </div>
    </div>

    </div>
<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Distribución de Participantes
            </h2>
            <div class="flex space-x-2">
                @if($distributedCount > 0)
                    <button wire:click="resetDistribution"
                            wire:confirm="¿Estás seguro? Esto eliminará TODAS las asignaciones actuales."
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Resetear Todo
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-indigo-500">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        1. Participantes Pendientes
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        <div>
                            <x-label value="Modalidad" />
                            <select wire:model.live="filterModality" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Todas</option>
                                @foreach($modalities as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label value="Programa" />
                                <select wire:model.live="filterCareer" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">Todos</option>
                                    @foreach($careers as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label value="Turno" />
                                <select wire:model.live="filterShift" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">Todos</option>
                                    @foreach($shifts as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-50 rounded-lg p-4 flex justify-between items-center mb-4 border border-indigo-100">
                        <div>
                            <div class="text-xs text-indigo-600 font-bold uppercase">Pendientes de Aula</div>
                            <div class="text-2xl font-black text-indigo-800">{{ $unassignedCount }}</div>
                        </div>
                        <button wire:click="refreshStats" class="text-indigo-500 hover:text-indigo-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </div>

                    <button wire:click="autoDistribute" 
                            wire:loading.attr="disabled"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow transition transform hover:scale-[1.02] flex justify-center items-center">
                        <svg wire:loading.remove class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        <span wire:loading.remove>EJECUTAR DISTRIBUCIÓN AUTOMÁTICA</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-green-500">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        2. Capacidad de Infraestructura
                    </h3>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded text-center border">
                            <div class="text-xs text-gray-500 uppercase">Capacidad Total</div>
                            <div class="text-xl font-bold text-gray-800">{{ $totalCapacity }}</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded text-center border border-green-100">
                            <div class="text-xs text-green-600 uppercase">Ocupados</div>
                            <div class="text-xl font-bold text-green-800">{{ $usedCapacity }}</div>
                        </div>
                    </div>

                    <div class="h-64 overflow-y-auto border rounded-lg p-2 space-y-2">
                        @foreach($classrooms as $room)
                            @php
                                $percent = $room->capacity > 0 ? ($room->assignments_count / $room->capacity) * 100 : 0;
                                $color = $percent >= 100 ? 'bg-red-500' : ($percent > 50 ? 'bg-yellow-500' : 'bg-green-500');
                            @endphp
                            <div class="flex items-center text-sm p-2 hover:bg-gray-50 rounded">
                                <div class="w-1/3 font-bold text-gray-700">{{ $room->pavilion->name }} - {{ $room->room_number }}</div>
                                <div class="w-2/3 pl-4">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span>{{ $room->assignments_count }} / {{ $room->capacity }}</span>
                                        <span>{{ round($percent) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6 pb-2 border-b">
                    Aulas Asignadas (Listas de Asistencia)
                </h3>

                @if($distributedCount == 0)
                    <div class="text-center py-10 text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        Aún no se ha realizado ninguna distribución.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($classrooms as $room)
                            @if($room->assignments_count > 0)
                                <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition p-4 relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 p-2 opacity-50 group-hover:opacity-100 transition">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-bold">
                                            {{ $room->assignments_count }} alum.
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="text-xs text-gray-500 uppercase tracking-wider">{{ $room->pavilion->name }}</div>
                                        <div class="text-xl font-bold text-gray-800">Aula {{ $room->room_number }}</div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-2 mt-4">
                                        <a href="{{ route('admission.exam.door-list', $room->id) }}" target="_blank" 
                                           class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm py-2 rounded flex justify-center items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            Lista de Puerta
                                        </a>
                                        </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
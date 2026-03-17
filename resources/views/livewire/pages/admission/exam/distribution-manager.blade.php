<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Distribución de Aulas - Examen de Admisión
            </h2>
            @if($distributedCount > 0)
                <button wire:click="resetDistribution"
                        wire:confirm="¿Estás seguro? Esto eliminará TODAS las asignaciones actuales."
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Resetear Todo
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <x-label value="Modalidad" />
                        <select wire:model.live="filterModality" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Todas --</option>
                            @foreach($modalities as $m) <option value="{{ $m->id }}">{{ $m->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <x-label value="Programa de Estudios" />
                        <select wire:model.live="filterCareer" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Todos --</option>
                            @foreach($careers as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <x-label value="Turno" />
                        <select wire:model.live="filterShift" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Todos --</option>
                            @foreach($shifts as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-end bg-gray-50 p-4 rounded-lg border border-gray-200 gap-4">
                    
                    <div class="w-full md:w-2/3 flex items-end gap-2">
                        <div class="flex-grow">
                            <x-label value="Asignar seleccionados al aula:" />
                            <select wire:model="targetClassroom" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Seleccionar Aula Disponible --</option>
                                @foreach($availableClassrooms as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->pavilion->name }} - {{ $room->room_number }} 
                                        (Libres: {{ $room->capacity - $room->assignments_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button wire:click="assignManual" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            Asignar Manualmente
                        </button>
                    </div>

                    <div class="hidden md:block h-10 w-px bg-gray-300 mx-2"></div>

                    <div class="w-full md:w-1/3 text-right">
                        <button wire:click="autoDistribute" wire:confirm="¿Distribuir aleatoriamente a todos los postulantes listados?" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Distribución Automática
                        </button>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Postulante</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modalidad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($applicants as $applicant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" value="{{ $applicant->id }}" wire:model.live="selectedApplicants" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    </td>
                                    <td class="px-4 py-3 text-sm font-mono">{{ $applicant->user->document_number }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $applicant->user->lastname }} {{ $applicant->user->name }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $applicant->admissionModality->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-500">
                                        {{ $applicant->admissionOffering->career->name ?? '-' }}
                                        <span class="block text-indigo-500">{{ $applicant->admissionOffering->shift->name ?? '' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        No hay postulantes pendientes de asignación con estos filtros.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $applicants->links() }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex justify-between items-center">
                    <span>Estado de Aulas</span>
                    <span class="text-sm font-normal text-gray-500">Ocupación Total: {{ $usedCapacity }} / {{ $totalCapacity }}</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($classroomsStatus as $room)
                        @php
                            $ocupados = $room->assignments_count;
                            $capacidad = $room->capacity;
                            $porcentaje = $capacidad > 0 ? ($ocupados / $capacidad) * 100 : 0;
                            $colorBarra = $porcentaje >= 100 ? 'bg-red-500' : ($porcentaje > 50 ? 'bg-yellow-500' : 'bg-green-500');
                        @endphp
                        
                        <div class="border rounded-lg p-4 relative overflow-hidden group hover:shadow-md transition bg-white">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="text-xs text-gray-500 uppercase font-bold">{{ $room->pavilion->name }}</div>
                                    <div class="text-xl font-bold text-gray-800">Aula {{ $room->room_number }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold {{ $ocupados >= $capacidad ? 'text-red-600' : 'text-gray-700' }}">
                                        {{ $ocupados }}
                                    </span>
                                    <span class="text-xs text-gray-400 block">/ {{ $capacidad }}</span>
                                </div>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                <div class="{{ $colorBarra }} h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                            </div>

                            @if($ocupados > 0)
                                <div class="grid grid-cols-1 gap-2">
                                    <a href="{{ route('admission.exam.door-list', $room->id) }}" target="_blank" 
                                       class="flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 rounded text-xs hover:bg-gray-200 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        Lista de Puerta
                                    </a>
                                    <a href="{{ route('admission.exam.answer-sheets', $room->id) }}"
                                    target="_blank"
                                    class="flex items-center justify-center px-3 py-2 bg-indigo-50
                                            text-indigo-700 rounded text-xs hover:bg-indigo-100 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0
                                                    00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2
                                                    2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                        Hoja de Respuestas
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-2 text-xs text-gray-400 italic">Sin asignaciones</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
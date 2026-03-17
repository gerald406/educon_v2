<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Carga Académica (Secciones)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded-lg border">
                    <div>
                        <x-label value="Periodo Académico" />
                        <select wire:model.live="filterPeriodId" class="w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->status == 'active' ? '(Activo)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-label value="Filtrar por Carrera" />
                        <select wire:model.live="filterCareerId" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Todas las carreras</option>
                            @foreach($careers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <x-button wire:click="create" class="w-full justify-center bg-indigo-600 hover:bg-indigo-700">
                            + Nueva Asignación
                        </x-button>
                    </div>
                </div>

                <div class="mb-4 w-full md:w-1/3">
                    <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar curso o docente..." class="w-full" />
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unidad Didáctica</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sección / Turno</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aforo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assignments as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $item->didacticUnit->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $item->didacticUnit->module->studyPlan->career->name ?? '-' }} | Sem {{ $item->didacticUnit->semester }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">{{ $item->teacher->user->name }} {{ $item->teacher->user->lastname }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Sección {{ $item->section }}
                                        </span>
                                        <div class="mt-1 text-xs">{{ $item->shift->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center">
                                            <span class="font-bold mr-1">{{ $item->current_enrolled }}</span> / {{ $item->max_capacity }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button wire:click="edit({{ $item->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                        <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-6 text-center text-gray-500">No hay asignaciones registradas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $assignments->links() }}</div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="2xl">
        <x-slot name="title">{{ $editingAssignment ? 'Editar Carga Académica' : 'Nueva Carga Académica' }}</x-slot>
        
        <x-slot name="content">
            <div class="grid grid-cols-1 gap-6">
                
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-700 border-b pb-1">1. Selección del Curso</h3>
                    
                    <div>
                        <x-label value="Programa de Estudios (Filtro)" />
                        <select wire:model.live="selectedCareerId" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                            <option value="">-- Todos los programas --</option>
                            @foreach($careers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <x-label value="Buscar Unidad Didáctica (Curso)" />
                        <x-input type="text" 
                                 wire:model.live.debounce.300ms="searchUnit" 
                                 placeholder="Escriba nombre del curso..." 
                                 class="w-full mt-1 {{ $didactic_unit_id ? 'border-green-500 ring-1 ring-green-500' : '' }}" />
                        
                        @if(!empty($unitResults))
                            <div class="absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto mt-1">
                                @foreach($unitResults as $unit)
                                    <div wire:click="selectUnit({{ $unit->id }}, '{{ $unit->name }}', '{{ $unit->module->name ?? 'M?' }}', '{{ $unit->module->studyPlan->code ?? '?' }}')"
                                         class="p-2 hover:bg-indigo-50 cursor-pointer text-sm border-b last:border-b-0">
                                        <div class="font-bold text-gray-800">{{ $unit->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            Sem {{ $unit->semester }} | {{ $unit->module->studyPlan->code ?? '' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <x-input-error for="didactic_unit_id" />
                        
                        @if($didactic_unit_id && empty($unitResults))
                            <div class="mt-1 flex items-center text-xs text-green-600 font-semibold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Curso seleccionado: {{ $selectedUnitName ?? 'Correcto' }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-700 border-b pb-1">2. Configuración</h3>

                    <div class="relative">
                        <x-label value="Docente Responsable" />
                        <x-input type="text" 
                                 wire:model.live.debounce.300ms="searchTeacher" 
                                 placeholder="Buscar por nombre o DNI..." 
                                 class="w-full mt-1 {{ $teacher_id ? 'border-green-500 ring-1 ring-green-500 bg-green-50' : '' }}" />
                        
                        @if(!empty($teacherResults))
                            <div class="absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto mt-1">
                                @foreach($teacherResults as $teacher)
                                    <div wire:click="selectTeacher({{ $teacher->id }}, '{{ $teacher->user->name }} {{ $teacher->user->lastname }}')"
                                         class="p-2 hover:bg-indigo-50 cursor-pointer text-sm border-b last:border-b-0">
                                        <div class="font-bold">{{ $teacher->user->name }} {{ $teacher->user->lastname }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ $teacher->user->document_number }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <x-input-error for="teacher_id" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-label value="Turno" />
                            <select wire:model="shift_id" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                                <option value="">Seleccione...</option>
                                @foreach($shifts as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="shift_id" />
                        </div>
                        <div>
                            <x-label value="Sección" />
                            <x-input wire:model="section" class="w-full uppercase mt-1" placeholder="A" maxlength="5" />
                            <x-input-error for="section" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-label value="Vacantes" />
                            <x-input type="number" wire:model="max_capacity" class="w-full mt-1" min="1" />
                            <x-input-error for="max_capacity" />
                        </div>
                        <div>
                            <x-label value="Estado" />
                            <select wire:model="status" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                                <option value="active">Activo</option>
                                <option value="suspended">Suspendido</option>
                                <option value="completed">Finalizado</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-3" wire:click="save">Guardar Asignación</x-button>
        </x-slot>
    </x-dialog-modal>

</div>
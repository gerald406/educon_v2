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
                        <select wire:model.live="filterPeriodId" class="w-full border-gray-300 rounded-md">
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->status == 'active' ? '(Activo)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-label value="Filtrar por Carrera" />
                        <select wire:model.live="filterCareerId" class="w-full border-gray-300 rounded-md">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unidad Didáctica (Curso)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detalle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aforo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assignments as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $item->didacticUnit->name }}</div>
                                        <div class="text-xs text-gray-500">Semestre {{ $item->didacticUnit->semester }} | {{ $item->didacticUnit->code }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($item->teacher->user->profile_photo_url)
                                                <img class="h-8 w-8 rounded-full mr-2" src="{{ $item->teacher->user->profile_photo_url }}" />
                                            @endif
                                            <div class="text-sm">{{ $item->teacher->user->name }} {{ $item->teacher->user->lastname }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div>Turno: <span class="font-medium">{{ $item->shift->name }}</span></div>
                                        <div>Sección: <span class="font-bold text-indigo-600">{{ $item->section }}</span></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 max-w-[100px]">
                                                @php $percent = ($item->current_enrolled / $item->max_capacity) * 100; @endphp
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium">{{ $item->current_enrolled }}/{{ $item->max_capacity }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button wire:click="edit({{ $item->id }})" class="text-indigo-600 mr-3">Editar</button>
                                        <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-6 text-center text-gray-500">No se encontraron asignaciones en este periodo.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $assignments->links() }}</div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="2xl">
        <x-slot name="title">{{ $editingAssignment ? 'Editar Carga Académica' : 'Nueva Asignación' }}</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2 md:col-span-1 bg-gray-50 p-3 rounded-md border">
                    <h3 class="font-bold text-xs uppercase text-gray-500 mb-2">Selección del Curso</h3>
                    
                    <div class="mb-2">
                        <x-label value="1. Carrera" />
                        <select wire:model.live="selectedCareerId" class="w-full border-gray-300 rounded-md text-sm">
                            <option value="">Seleccione...</option>
                            @foreach($careers as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <x-label value="2. Plan de Estudio" />
                        <select wire:model.live="selectedStudyPlanId" class="w-full border-gray-300 rounded-md text-sm" {{ $studyPlans->isEmpty() ? 'disabled' : '' }}>
                            <option value="">Seleccione...</option>
                            @foreach($studyPlans as $p) <option value="{{ $p->id }}">{{ $p->code }}</option> @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <x-label value="3. Módulo" />
                        <select wire:model.live="selectedModuleId" class="w-full border-gray-300 rounded-md text-sm" {{ $modules->isEmpty() ? 'disabled' : '' }}>
                            <option value="">Seleccione...</option>
                            @foreach($modules as $m) <option value="{{ $m->id }}">Mód {{ $m->module_number }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <x-label value="4. Unidad Didáctica (Curso)" />
                        <select wire:model="didactic_unit_id" class="w-full border-gray-300 rounded-md font-bold text-indigo-700" {{ $units->isEmpty() ? 'disabled' : '' }}>
                            <option value="">Seleccione Curso...</option>
                            @foreach($units as $u) <option value="{{ $u->id }}">Sem {{ $u->semester }} | {{ $u->name }}</option> @endforeach
                        </select>
                        <x-input-error for="didactic_unit_id" />
                    </div>
                </div>

                <div class="col-span-2 md:col-span-1 p-3">
                    <h3 class="font-bold text-xs uppercase text-gray-500 mb-2">Configuración</h3>
                    
                    <div class="mb-3">
                        <x-label value="Periodo Académico" />
                        <select wire:model="academic_period_id" class="w-full border-gray-300 rounded-md bg-gray-100" disabled>
                            @foreach($periods as $p) <option value="{{ $p->id }}">{{ $p->code }}</option> @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <x-label value="Docente a cargo" />
                        <select wire:model="teacher_id" class="w-full border-gray-300 rounded-md">
                            <option value="">Seleccione Docente...</option>
                            @foreach($teachers as $t) <option value="{{ $t->id }}">{{ $t->user->name }} {{ $t->user->lastname }}</option> @endforeach
                        </select>
                        <x-input-error for="teacher_id" />
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <x-label value="Turno" />
                            <select wire:model="shift_id" class="w-full border-gray-300 rounded-md">
                                <option value="">Seleccione...</option>
                                @foreach($shifts as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                            </select>
                            <x-input-error for="shift_id" />
                        </div>
                        <div>
                            <x-label value="Sección" />
                            <x-input wire:model="section" class="w-full uppercase" placeholder="Ej. A" />
                            <x-input-error for="section" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <x-label value="Vacantes (Aforo)" />
                            <x-input type="number" wire:model="max_capacity" class="w-full" />
                            <x-input-error for="max_capacity" />
                        </div>
                        <div>
                            <x-label value="Estado" />
                            <select wire:model="status" class="w-full border-gray-300 rounded-md">
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
            <x-button class="ml-3" wire:click="save">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
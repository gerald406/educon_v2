<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Unidades Didácticas (Cursos)
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
                    
                    <x-button wire:click="create">
                        {{ __('Nueva Unidad Didáctica') }}
                    </x-button>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan / Módulo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semestre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad Didáctica</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créditos/Horas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($units as $unit)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="font-bold text-xs text-indigo-600">{{ $unit->module->studyPlan->code ?? '--' }}</div>
                                        <div class="text-xs">Mód. {{ $unit->module->module_number ?? '?' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        {{ $unit->semester }}° Semestre
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-bold">{{ $unit->code }}</div>
                                        <div>{{ $unit->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span>{{ $unit->credits }} Créditos</span>
                                            <span class="text-xs">{{ $unit->weekly_hours }} hrs/sem - {{ $unit->total_hours }} total</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $unit->unit_type === 'career' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $unit->unit_type === 'career' ? 'Especialidad' : 'Transversal' }}
                                        </span>
                                        @if($unit->status === 'inactive')
                                            <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="edit({{ $unit->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">
                                            Editar
                                        </button>
                                        <button wire:click="confirmDelete({{ $unit->id }})" class="text-red-600 hover:text-red-900 font-semibold">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No se encontraron unidades didácticas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $units->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="2xl">
        <x-slot name="title">
            {{ $editingUnit ? 'Editar Unidad Didáctica' : 'Nueva Unidad Didáctica' }}
        </x-slot>

        <x-slot name="content">
            <div class="bg-gray-50 p-4 rounded-md mb-6 border border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-3 uppercase tracking-wider">Ubicación Curricular</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <div>
                        <x-label value="1. Carrera Profesional" />
                        <select wire:model.live="selectedCareerId" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Seleccione Carrera...</option>
                            @foreach($careers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label value="2. Plan de Estudio" />
                        <select wire:model.live="selectedStudyPlanId" {{ $studyPlans->isEmpty() ? 'disabled' : '' }} class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm disabled:bg-gray-100 disabled:text-gray-400">
                            <option value="">
                                {{ $selectedCareerId ? ($studyPlans->isEmpty() ? 'Sin planes activos' : 'Seleccione Plan...') : 'Seleccione Carrera primero' }}
                            </option>
                            @foreach($studyPlans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->code }} - {{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="module_id" value="3. Módulo Formativo" />
                        <select id="module_id" wire:model="module_id" {{ $modules->isEmpty() ? 'disabled' : '' }} class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm disabled:bg-gray-100 disabled:text-gray-400">
                            <option value="">
                                {{ $selectedStudyPlanId ? ($modules->isEmpty() ? 'Sin módulos' : 'Seleccione Módulo...') : 'Seleccione Plan primero' }}
                            </option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}">Módulo {{ $module->module_number }}: {{ Str::limit($module->name, 30) }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="module_id" class="mt-2" />
                    </div>
                </div>
            </div>

            <h3 class="text-sm font-medium text-gray-700 mb-3 uppercase tracking-wider">Detalles del Curso</h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                
                <div class="md:col-span-3">
                    <x-label for="code" value="Código" />
                    <x-input id="code" type="text" class="mt-1 block w-full uppercase" wire:model="code" placeholder="Ej. DS-101" />
                    <x-input-error for="code" class="mt-2" />
                </div>

                <div class="md:col-span-9">
                    <x-label for="name" value="Nombre de la Unidad Didáctica" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Ej. Desarrollo de Software I" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="md:col-span-3">
                    <x-label for="semester" value="Semestre (Ciclo)" />
                    <select id="semester" wire:model="semester" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach(range(1, 8) as $i)
                            <option value="{{ $i }}">Semestre {{ $i }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="semester" class="mt-2" />
                </div>

                <div class="md:col-span-3">
                    <x-label for="credits" value="Créditos" />
                    <x-input id="credits" type="number" class="mt-1 block w-full" wire:model="credits" min="1" />
                    <x-input-error for="credits" class="mt-2" />
                </div>

                <div class="md:col-span-3">
                    <x-label for="weekly_hours" value="Horas Semanales" />
                    <x-input id="weekly_hours" type="number" class="mt-1 block w-full" wire:model="weekly_hours" min="1" />
                    <x-input-error for="weekly_hours" class="mt-2" />
                </div>

                <div class="md:col-span-3">
                    <x-label for="total_hours" value="Horas Semestrales" />
                    <x-input id="total_hours" type="number" class="mt-1 block w-full" wire:model="total_hours" min="1" />
                    <x-input-error for="total_hours" class="mt-2" />
                </div>

                <div class="md:col-span-4">
                    <x-label for="unit_type" value="Tipo de Unidad" />
                    <select id="unit_type" wire:model="unit_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="career">Específica (Carrera)</option>
                        <option value="transversal">Transversal (Empleabilidad)</option>
                    </select>
                    <x-input-error for="unit_type" class="mt-2" />
                </div>

                <div class="md:col-span-4">
                    <x-label for="semester_order" value="Orden en Boleta" />
                    <x-input id="semester_order" type="number" class="mt-1 block w-full" wire:model="semester_order" />
                    <x-input-error for="semester_order" class="mt-2" />
                </div>

                <div class="md:col-span-4">
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
                    {{ $editingUnit ? 'Actualizar Unidad' : 'Guardar Unidad' }}
                </span>
                <span wire:loading wire:target="save">
                    Guardando...
                </span>
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
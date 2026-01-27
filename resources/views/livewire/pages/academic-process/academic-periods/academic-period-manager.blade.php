<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Periodos Académicos</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="w-1/3">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar periodo..." class="w-full" />
                    </div>
                    <x-button wire:click="create">Nuevo Periodo</x-button>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración Semestre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($periods as $period)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-indigo-600">{{ $period->code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $period->name }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $period->start_date->format('d/m/Y') }} <br> al {{ $period->end_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $period->enrollment_start_date->format('d/m') }} - {{ $period->enrollment_end_date->format('d/m') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $clase = match($period->status) {
                                                'active' => 'bg-green-100 text-green-800',
                                                'planned' => 'bg-blue-100 text-blue-800',
                                                'closed' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-gray-100'
                                            };
                                            $texto = match($period->status) {
                                                'active' => 'Activo',
                                                'planned' => 'Planificado',
                                                'closed' => 'Cerrado',
                                                default => $period->status
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $clase }}">
                                            {{ $texto }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button wire:click="edit({{ $period->id }})" class="text-indigo-600 mr-3">Editar</button>
                                        <button wire:click="confirmDelete({{ $period->id }})" class="text-red-600">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-4 text-center text-gray-500">No hay periodos registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $periods->links() }}</div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="2xl">
        <x-slot name="title">{{ $editingPeriod ? 'Editar Periodo' : 'Nuevo Periodo' }}</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div>
                    <x-label value="Año Académico Base" />
                    <select wire:model="academic_year_id" class="w-full border-gray-300 rounded-md">
                        <option value="">Seleccione...</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="academic_year_id" />
                </div>
                <div>
                    <x-label value="Código (Siglas)" />
                    <x-input wire:model="code" class="w-full uppercase" placeholder="Ej. 2025-I" />
                    <x-input-error for="code" />
                </div>
                <div class="col-span-2">
                    <x-label value="Nombre del Periodo" />
                    <x-input wire:model="name" class="w-full" placeholder="Ej. Periodo Académico 2025-I" />
                    <x-input-error for="name" />
                </div>

                <div class="col-span-2 border-t mt-2 pt-2"><h4 class="font-bold text-gray-700">Duración del Semestre</h4></div>
                <div>
                    <x-label value="Fecha Inicio" />
                    <x-input type="date" wire:model="start_date" class="w-full" />
                    <x-input-error for="start_date" />
                </div>
                <div>
                    <x-label value="Fecha Fin" />
                    <x-input type="date" wire:model="end_date" class="w-full" />
                    <x-input-error for="end_date" />
                </div>

                <div class="col-span-2 border-t mt-2 pt-2"><h4 class="font-bold text-gray-700">Cronograma de Matrícula</h4></div>
                <div>
                    <x-label value="Inicio Matrícula" />
                    <x-input type="date" wire:model="enrollment_start_date" class="w-full" />
                    <x-input-error for="enrollment_start_date" />
                </div>
                <div>
                    <x-label value="Fin Matrícula" />
                    <x-input type="date" wire:model="enrollment_end_date" class="w-full" />
                    <x-input-error for="enrollment_end_date" />
                </div>

                <div class="col-span-2 border-t mt-2 pt-2"><h4 class="font-bold text-gray-700">Dictado de Clases</h4></div>
                <div>
                    <x-label value="Inicio Clases" />
                    <x-input type="date" wire:model="classes_start_date" class="w-full" />
                    <x-input-error for="classes_start_date" />
                </div>
                <div>
                    <x-label value="Fin Clases" />
                    <x-input type="date" wire:model="classes_end_date" class="w-full" />
                    <x-input-error for="classes_end_date" />
                </div>

                <div class="col-span-2 border-t mt-2 pt-2"><h4 class="font-bold text-gray-700">Registro de Notas (Actas)</h4></div>
                <div>
                    <x-label value="Apertura Sistema Notas" />
                    <x-input type="date" wire:model="grade_entry_start_date" class="w-full" />
                </div>
                <div>
                    <x-label value="Cierre Sistema Notas" />
                    <x-input type="date" wire:model="grade_entry_end_date" class="w-full" />
                </div>

                <div class="col-span-2 mt-4">
                    <x-label value="Estado del Periodo" />
                    <select wire:model="status" class="w-full border-gray-300 rounded-md">
                        <option value="planned">Planificado (Borrador)</option>
                        <option value="active">Activo (En curso)</option>
                        <option value="closed">Cerrado (Histórico)</option>
                    </select>
                </div>

            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-3" wire:click="save">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
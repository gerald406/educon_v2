<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Planes de Estudio</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div class="w-full md:w-1/3 relative">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar plan o carrera..." class="w-full" />
                    </div>
                    <x-button wire:click="create">Nuevo Plan</x-button>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carrera</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vigencia</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($plans as $plan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $plan->code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $plan->name }} <span class="text-xs text-gray-400">({{ $plan->version }})</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $plan->career->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $plan->start_date->format('d/m/Y') }} 
                                        @if($plan->end_date) - {{ $plan->end_date->format('d/m/Y') }} @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button wire:click="edit({{ $plan->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                        <button wire:click="confirmDelete({{ $plan->id }})" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay planes registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $plans->links() }}</div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">{{ $editingStudyPlan ? 'Editar Plan' : 'Nuevo Plan' }}</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-label for="career_id" value="Carrera Profesional" />
                    <select wire:model="career_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Seleccione...</option>
                        @foreach($careers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="career_id" />
                </div>
                <div>
                    <x-label for="code" value="Código Interno" />
                    <x-input wire:model="code" class="w-full uppercase" placeholder="Ej. APSTI-2021" />
                    <x-input-error for="code" />
                </div>
                <div>
                    <x-label for="version" value="Versión" />
                    <x-input wire:model="version" class="w-full" placeholder="Ej. 2021" />
                    <x-input-error for="version" />
                </div>
                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Plan" />
                    <x-input wire:model="name" class="w-full" />
                    <x-input-error for="name" />
                </div>
                <div>
                    <x-label for="start_date" value="Fecha Inicio" />
                    <x-input type="date" wire:model="start_date" class="w-full" />
                    <x-input-error for="start_date" />
                </div>
                <div>
                    <x-label for="end_date" value="Fecha Fin (Opcional)" />
                    <x-input type="date" wire:model="end_date" class="w-full" />
                    <x-input-error for="end_date" />
                </div>
                <div>
                    <x-label for="total_credits" value="Créditos Totales" />
                    <x-input type="number" wire:model="total_credits" class="w-full" />
                    <x-input-error for="total_credits" />
                </div>
                <div>
                    <x-label for="total_hours" value="Horas Totales" />
                    <x-input type="number" wire:model="total_hours" class="w-full" />
                    <x-input-error for="total_hours" />
                </div>
                <div class="col-span-2">
                    <x-label for="approval_resolution" value="Resolución de Aprobación" />
                    <x-input wire:model="approval_resolution" class="w-full" />
                </div>
                <div class="col-span-2">
                    <x-label for="status" value="Estado" />
                    <select wire:model="status" class="w-full border-gray-300 rounded-md">
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                        <option value="obsolete">Obsoleto</option>
                    </select>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-3" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $editingStudyPlan ? 'Actualizar' : 'Guardar' }}</span>
                <span wire:loading>Procesando...</span>
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
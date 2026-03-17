<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Módulos Formativos</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between mb-4">
                    <div class="w-1/3">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar módulo..." class="w-full" />
                    </div>
                    <x-button wire:click="create">Nuevo Módulo</x-button>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carrera / Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Módulo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créditos/Horas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($modules as $module)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="font-bold">{{ $module->studyPlan->career->code }}</div>
                                        <div class="text-xs">{{ $module->studyPlan->code }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-medium">Módulo {{ $module->module_number }}</div>
                                        <div class="text-gray-500">{{ $module->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $module->minimum_credits_approval }} Créditos / {{ $module->total_hours }} Horas
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 text-xs font-semibold rounded-full {{ $module->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $module->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button wire:click="edit({{ $module->id }})" class="text-indigo-600 mr-3">Editar</button>
                                        <button wire:click="confirmDelete({{ $module->id }})" class="text-red-600">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-4 text-center text-gray-500">No hay módulos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $modules->links() }}</div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">{{ $editingModule ? 'Editar Módulo' : 'Nuevo Módulo' }}</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2 md:col-span-1">
                    <x-label value="1. Seleccione Carrera (Filtro)" />
                    <select wire:model.live="selectedCareerId" class="w-full border-gray-300 rounded-md">
                        <option value="">Seleccione Carrera...</option>
                        @foreach($careers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <x-label value="2. Seleccione Plan de Estudio" />
                    <select wire:model="study_plan_id" class="w-full border-gray-300 rounded-md" {{ $availablePlans->isEmpty() ? 'disabled' : '' }}>
                        <option value="">Seleccione Plan...</option>
                        @foreach($availablePlans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->code }} - {{ $plan->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="study_plan_id" />
                </div>

                <div class="col-span-2 md:col-span-1">
                    <x-label for="module_number" value="Número de Módulo" />
                    <x-input type="number" wire:model="module_number" class="w-full" min="1" />
                    <x-input-error for="module_number" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="name" value="Nombre del Módulo Formativo" />
                    <x-input type="text" wire:model="name" class="w-full" placeholder="Ej. Gestión de Soporte Técnico..." />
                    <x-input-error for="name" />
                </div>

                <div>
                    <x-label for="minimum_credits_approval" value="Créditos Mínimos" />
                    <x-input type="number" wire:model="minimum_credits_approval" class="w-full" />
                    <x-input-error for="minimum_credits_approval" />
                </div>
                <div>
                    <x-label for="total_hours" value="Horas Totales" />
                    <x-input type="number" wire:model="total_hours" class="w-full" />
                    <x-input-error for="total_hours" />
                </div>
                
                <div class="col-span-2">
                    <x-label for="description" value="Descripción (Opcional)" />
                    <textarea wire:model="description" class="w-full border-gray-300 rounded-md"></textarea>
                </div>
                
                <div>
                    <x-label for="status" value="Estado" />
                    <select wire:model="status" class="w-full border-gray-300 rounded-md">
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                    </select>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-3" wire:click="save" wire:loading.attr="disabled">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
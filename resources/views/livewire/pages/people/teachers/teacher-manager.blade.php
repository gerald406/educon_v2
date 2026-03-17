<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Docentes</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div class="w-full md:w-1/3 relative">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por DNI o Nombre..." class="w-full" />
                    </div>
                    <x-button wire:click="create">Nuevo Docente</x-button>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datos Académicos</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contrato</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($teachers as $teacher)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $teacher->user->profile_photo_url }}" alt="" />
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $teacher->user->full_name ?? $teacher->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $teacher->user->email }}</div>
                                                <div class="text-xs text-gray-500 font-bold">DNI: {{ $teacher->user->document_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="font-medium text-gray-900">{{ $teacher->specialty ?: 'Sin especialidad' }}</div>
                                        <div class="text-xs">{{ $teacher->academic_degree ?: 'Sin grado' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @php
                                            $tipoContrato = match($teacher->contract_type) {
                                                'contracted' => 'Contratado',
                                                'permanent' => 'Nombrado',
                                                'hourly' => 'Por Horas',
                                                default => $teacher->contract_type
                                            };
                                            
                                            $diaPrep = match($teacher->preparation_day) {
                                                'monday' => 'Lunes',
                                                'tuesday' => 'Martes',
                                                'wednesday' => 'Miércoles',
                                                'thursday' => 'Jueves',
                                                'friday' => 'Viernes',
                                                'saturday' => 'Sábado',
                                                default => 'Ninguno'
                                            };
                                        @endphp
                                        
                                        <div class="font-medium">{{ $tipoContrato }}</div>
                                        <div class="text-xs text-gray-400">Prep: {{ $diaPrep }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $badgeClass = match($teacher->status) {
                                                'active' => 'bg-green-100 text-green-800',
                                                'leave' => 'bg-yellow-100 text-yellow-800',
                                                'terminated' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                            $statusLabel = match($teacher->status) {
                                                'active' => 'Activo',
                                                'leave' => 'Licencia',
                                                'terminated' => 'Cesado',
                                                default => $teacher->status
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button wire:click="edit({{ $teacher->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">Editar</button>
                                        <button wire:click="confirmDelete({{ $teacher->id }})" class="text-red-600 hover:text-red-900 font-semibold">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay docentes registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $teachers->links() }}</div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="2xl">
        <x-slot name="title">{{ $editingTeacher ? 'Editar Docente' : 'Nuevo Docente' }}</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-1">Datos Personales</h3>
                    
                    <div>
                        <x-label value="Buscar por DNI" />
                        <div class="flex gap-2">
                            <x-input wire:model="searchDni" type="text" class="w-full" maxlength="8" placeholder="Ingrese DNI" />
                            <x-button type="button" wire:click="searchPersonByDni" wire:loading.attr="disabled">
                                <svg wire:loading.remove wire:target="searchPersonByDni" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <span wire:loading wire:target="searchPersonByDni">...</span>
                            </x-button>
                        </div>
                        <x-input-error for="searchDni" />
                        <x-input-error for="document_number" />
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <x-label value="Apellido Paterno" />
                            <x-input wire:model="paternal_surname" type="text" class="w-full" />
                            <x-input-error for="paternal_surname" />
                        </div>
                        <div>
                            <x-label value="Apellido Materno" />
                            <x-input wire:model="maternal_surname" type="text" class="w-full" />
                            <x-input-error for="maternal_surname" />
                        </div>
                    </div>
                    <div>
                        <x-label value="Nombres" />
                        <x-input wire:model="name" type="text" class="w-full" />
                        <x-input-error for="name" />
                    </div>
                    <div>
                        <x-label value="Correo Electrónico" />
                        <x-input wire:model="email" type="email" class="w-full" />
                        <x-input-error for="email" />
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-1">Perfil Profesional</h3>
                    <div>
                        <x-label value="Código Docente" />
                        <x-input wire:model="code" type="text" class="w-full uppercase" />
                        <x-input-error for="code" />
                    </div>
                    <div>
                        <x-label value="Grado Académico" />
                        <x-input wire:model="academic_degree" type="text" class="w-full" placeholder="Ej. Bachiller" />
                    </div>
                    <div>
                        <x-label value="Especialidad" />
                        <x-input wire:model="specialty" type="text" class="w-full" placeholder="Ej. Ing. de Sistemas" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <x-label value="Tipo Contrato" />
                            <select wire:model="contract_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="contracted">Contratado</option>
                                <option value="permanent">Nombrado</option>
                                <option value="hourly">Por Horas</option>
                            </select>
                        </div>
                        <div>
                            <x-label value="Día Prep. (No Lectivo)" />
                            <select wire:model="preparation_day" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Ninguno</option>
                                <option value="monday">Lunes</option>
                                <option value="tuesday">Martes</option>
                                <option value="wednesday">Miércoles</option>
                                <option value="thursday">Jueves</option>
                                <option value="friday">Viernes</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <x-label value="Fecha Contratación" />
                        <x-input wire:model="hire_date" type="date" class="w-full" />
                    </div>
                    <div>
                        <x-label value="Estado" />
                        <select wire:model="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active">Activo</option>
                            <option value="leave">Licencia</option>
                            <option value="terminated">Cesado</option>
                        </select>
                    </div>
                </div>

                {{-- SECCIÓN COORDINADOR --}}
                <div class="md:col-span-2 border-t pt-4 mt-2">
                    <div class="flex items-center gap-3 mb-3">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                wire:model.live="is_coordinator"
                                class="rounded border-gray-300 text-purple-600
                                    focus:ring-purple-500 w-4 h-4">
                            <span class="font-semibold text-gray-800">
                                Este docente también es Coordinador de Programa
                            </span>
                        </label>
                    </div>

                    @if($is_coordinator)
                        <div class="border border-purple-200 bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5
                                            S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18
                                            7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477
                                            14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                            C19.832 18.477 18.247 18 16.5 18c-1.746
                                            0-3.332.477-4.5 1.253"/>
                                </svg>
                                <x-label value="Carrera que Coordinará *"
                                        class="text-purple-800 font-bold" />
                            </div>

                            <select wire:model="selectedCareerId"
                                    class="w-full border-purple-300 focus:border-purple-500
                                        focus:ring-purple-500 rounded-md shadow-sm text-sm">
                                <option value="">-- Seleccionar Carrera --</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}">
                                        {{ $career->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="selectedCareerId" class="mt-1" />

                            <p class="text-xs text-purple-600 mt-2">
                                ⚠️ Se asignarán automáticamente los roles
                                <strong>Docente</strong> y <strong>Coordinador</strong>.
                                Si la carrera ya tiene coordinador, será reemplazado.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-3" wire:click="save" wire:loading.attr="disabled">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>

    @script
<script>
    // Listener para confirmación de eliminación de docente
    Livewire.on('confirm-delete-teacher', (event) => {
        Swal.fire({
            title: '¿Eliminar Docente?',
            text: 'Se eliminará el perfil docente y el acceso al sistema.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.deleteTeacher(event.id);
            }
        });
    });
</script>
@endscript
</div>
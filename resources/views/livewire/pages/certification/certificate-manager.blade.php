<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Emisión de Certificados
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por estudiante o código..." />
                        <x-button wire:click="openCreateModal">
                            Registrar Nuevo Certificado
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Estudiante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha Emisión</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium">Emitido por</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($certificates as $cert)
                                    <tr>
                                        <td class="px-6 py-4">{{ $cert->code }}</td>
                                        <td class="px-6 py-4">{{ $cert->student->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $cert->certificate_type }} {{ $cert->module ? ' ('.$cert->module->name.')' : '' }}</td>
                                        <td class="px-6 py-4">{{ $cert->issue_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $cert->issuedBy->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button wire:click="openEditModal({{ $cert->id }})">Editar</x-button>
                                            <x-danger-button wire:click="confirmDelete({{ $cert->id }})">Eliminar</x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No se encontraron certificados registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $certificates->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            {{ $editingCertificate ? 'Editar Registro de Certificado' : 'Registrar Nuevo Certificado' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="col-span-2">
                    <x-label for="studentSearch" value="Buscar Estudiante" />
                    <x-input id="studentSearch" type="text" class="mt-1 block w-full" 
                             wire:model.live.debounce.300ms="studentSearch" 
                             placeholder="Buscar por nombre o código..." />
                    @if($students->count() > 0)
                        <div class="mt-1 border rounded-md max-h-32 overflow-y-auto">
                            @foreach($students as $student)
                                <div class="p-2 hover:bg-gray-100 cursor-pointer"
                                     wire:click="selectStudent({{ $student->id }})">
                                    {{ $student->user->name }} ({{ $student->code }})
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input type="hidden" wire:model="student_id">
                    <x-input-error for="student_id" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="code" value="Código de Certificado" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model.blur="code" placeholder="Ej. CERT-001" />
                    <x-input-error for="code" class="mt-2" />
                </div>

                <div class="col-span-1">
                    <x-label for="issue_date" value="Fecha de Emisión" />
                    <x-input id="issue_date" type="date" class="mt-1 block w-full" wire:model.blur="issue_date" />
                    <x-input-error for="issue_date" class="mt-2" />
                </div>
                
                <div class="col-span-1">
                    <x-label for="certificate_type" value="Tipo de Certificado" />
                    <select id="certificate_type" wire:model.live="certificate_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="studies">Constancia de Estudios</option>
                        <option value="grades">Certificado de Notas</option>
                        <option value="modular">Certificado Modular</option>
                        <option value="graduation">Diploma/Título</option>
                    </select>
                    <x-input-error for="certificate_type" class="mt-2" />
                </div>
                
                @if($certificate_type == 'modular')
                    <div class="col-span-1">
                        <x-label for="module_id" value="Módulo" />
                        <select id="module_id" wire:model="module_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                @if($modules->isEmpty()) disabled @endif>
                            <option value="">-- Seleccione un módulo --</option>
                            @foreach($modules as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="module_id" class="mt-2" />
                    </div>
                @endif
                
                <div class="col-span-1">
                    <x-label for="status" value="Estado" />
                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="valid">Válido</option>
                        <option value="cancelled">Cancelado</option>
                        <option value="expired">Expirado</option>
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                Guardar
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
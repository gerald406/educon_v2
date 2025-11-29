<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Reservas de Matrícula (Licencias)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                    <h3 class="text-md font-bold text-gray-700 mb-2">Buscar Estudiante</h3>
                    <div class="flex gap-4">
                        <div class="relative flex-1">
                            <x-input type="text" class="w-full" wire:model.live.debounce.300ms="search" placeholder="Nombre o DNI del estudiante..." />
                            
                            @if($searchResults->count() > 0)
                                <div class="absolute z-50 w-full bg-white border rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto">
                                    @foreach($searchResults as $student)
                                        <div class="p-2 hover:bg-gray-100 cursor-pointer" wire:click="selectStudent({{ $student->id }})">
                                            <div class="font-bold">{{ $student->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $student->code }} - {{ $student->career->name }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <x-button wire:click="openCreateModal" :disabled="!$selectedStudent">
                            Registrar Reserva
                        </x-button>
                    </div>
                    @if($selectedStudent)
                        <div class="mt-2 text-sm text-green-600">
                            <strong>Seleccionado:</strong> {{ $selectedStudent->user->name }} ({{ $selectedStudent->code }})
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Resolución</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vigencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reservations as $res)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $res->resolution_code }}</td>
                                    <td class="px-6 py-4">
                                        {{ $res->student->user->name }}<br>
                                        <span class="text-xs text-gray-500">{{ $res->student->code }}</span>
                                    </td>
                                    <td class="px-6 py-4">{{ $res->academicPeriod->name }}</td>
                                    <td class="px-6 py-4 truncate max-w-xs" title="{{ $res->reason }}">{{ $res->reason }}</td>
                                    <td class="px-6 py-4 text-xs">
                                        Del {{ $res->start_date->format('d/m/Y') }}<br>
                                        Al {{ $res->end_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $res->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($res->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No hay reservas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $reservations->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            Registrar Reserva de Matrícula
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div class="bg-blue-50 p-3 rounded border border-blue-200">
                    <p class="text-sm text-blue-800">Estudiante: <strong>{{ $selectedStudent?->user->name }}</strong></p>
                    <p class="text-sm text-blue-800">Programa: {{ $selectedStudent?->career->name }}</p>
                </div>

                <div>
                    <x-label value="Nro. de Resolución (Obligatorio)" />
                    <x-input type="text" class="w-full" wire:model="resolution_code" placeholder="Ej. R.D. N° 045-2025-IESTP" />
                    <x-input-error for="resolution_code" class="mt-1" />
                </div>

                <div>
                    <x-label value="Periodo Académico" />
                    <select wire:model="academic_period_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        @foreach($periods as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="academic_period_id" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label value="Fecha Inicio" />
                        <x-input type="date" class="w-full" wire:model="start_date" />
                        <x-input-error for="start_date" class="mt-1" />
                    </div>
                    <div>
                        <x-label value="Fecha Fin" />
                        <x-input type="date" class="w-full" wire:model="end_date" />
                        <x-input-error for="end_date" class="mt-1" />
                    </div>
                </div>

                <div>
                    <x-label value="Motivo de la Reserva" />
                    <textarea wire:model="reason" class="w-full border-gray-300 rounded-md shadow-sm" rows="3" placeholder="Ej. Motivos de salud, trabajo, viaje..."></textarea>
                    <x-input-error for="reason" class="mt-1" />
                </div>

               
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="save">Guardar Reserva</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
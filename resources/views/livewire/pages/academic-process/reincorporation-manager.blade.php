<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Reincorporaciones
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="mb-6 p-6 bg-blue-50 rounded-lg border border-blue-100 text-center">
                    <h3 class="text-lg font-bold text-blue-800 mb-4">Buscar Estudiante con Reserva (Licencia)</h3>
                    
                    <div class="max-w-xl mx-auto relative">
                        <x-input type="text" class="w-full text-center text-lg" wire:model.live.debounce.300ms="search" placeholder="Ingrese nombre o DNI..." />
                        
                        @if($searchResults->count() > 0)
                            <div class="absolute z-50 w-full bg-white border rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto text-left">
                                @foreach($searchResults as $student)
                                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b" wire:click="selectStudent({{ $student->id }})">
                                        <div class="font-bold">{{ $student->user->name }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ $student->code }} - {{ $student->career->name }}
                                            <span class="float-right text-xs bg-yellow-100 text-yellow-800 px-2 rounded">Reservado</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    @if($selectedStudent)
                        <div class="mt-6 bg-white p-4 rounded border text-left max-w-2xl mx-auto shadow-sm">
                            <h4 class="font-bold text-gray-800 border-b pb-2 mb-2">Datos del Estudiante</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <p><strong>Nombre:</strong> {{ $selectedStudent->user->name }}</p>
                                <p><strong>Código:</strong> {{ $selectedStudent->code }}</p>
                                <p><strong>Carrera:</strong> {{ $selectedStudent->career->name }}</p>
                                <p><strong>Semestre Anterior:</strong> {{ $selectedStudent->current_semester }}</p>
                            </div>
                            
                            @if($lastReservation)
                                <div class="mt-4 bg-yellow-50 p-3 rounded text-xs text-yellow-800">
                                    <strong>Última Reserva:</strong> 
                                    Del {{ $lastReservation->start_date->format('d/m/Y') }} al {{ $lastReservation->end_date->format('d/m/Y') }}.
                                    <br>Motivo: {{ $lastReservation->reason }}
                                </div>
                            @endif

                            <div class="mt-4 text-center">
                                <x-button wire:click="openReincorporationModal" class="bg-blue-600 hover:bg-blue-700">
                                    Procesar Reincorporación
                                </x-button>
                            </div>
                        </div>
                    @elseif(strlen($search) > 2 && $searchResults->isEmpty())
                        <p class="mt-4 text-gray-500 text-sm">No se encontraron estudiantes con estado "Reserva de Matrícula".</p>
                    @endif
                </div>
                
                <div class="text-center text-gray-400 text-sm">
                    Solo se pueden reincorporar estudiantes que tengan una reserva o licencia activa en el sistema.
                </div>

            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            Registrar Matrícula por Reincorporación
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div class="bg-green-50 p-4 rounded-md border border-green-200">
                    <h4 class="font-bold text-green-900 text-sm mb-2">Validación de Pago (Caja)</h4>
                    <x-label value="Número de Recibo / Voucher" class="mb-1" />
                    <div class="flex gap-2">
                        <x-input type="text" class="w-full" wire:model="voucherNumber" placeholder="Ingrese el Nro. de Recibo" />
                    </div>
                    <p class="text-xs text-green-700 mt-2">
                        Ingrese el comprobante de pago por derecho de matrícula para validar.
                    </p>
                </div>

                <div>
                    <x-label value="Semestre de Reincorporación (Automático)" />
                    <div class="relative">
                        <x-input type="text" class="w-full bg-gray-100 text-gray-600 font-bold" 
                                value="Semestre {{ $semesterEnrolled }}" 
                                readonly />
                        <p class="text-xs text-gray-500 mt-1">
                            El estudiante se reincorpora al mismo semestre que cursaba antes de la reserva.
                        </p>
                    </div>
                </div>

                <div>
                    <x-label value="Observaciones" />
                    <textarea wire:model="notes" class="w-full border-gray-300 rounded-md shadow-sm" rows="2"></textarea>
                </div>
                
                <div class="text-xs text-gray-500">
                    * Al confirmar, el estado del estudiante pasará a "Regular" y se creará su registro de matrícula.
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ml-2 bg-blue-600 hover:bg-blue-700" wire:click="processReincorporation">
                Confirmar Reincorporación
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
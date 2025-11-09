<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Pagos (Caja)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="mb-4 relative">
                        <x-label for="search" value="Buscar Estudiante (por Nombre, Código o Email)" />
                        <x-input id="search" type="text" class="mt-1 block w-full" 
                                 wire:model.live.debounce.300ms="search" 
                                 placeholder="Escriba al menos 3 caracteres..." />
                        
                        @if($searchResults->count() > 0)
                            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                @foreach($searchResults as $student)
                                    <div class="p-2 hover:bg-gray-100 cursor-pointer" 
                                         wire:click="selectStudent({{ $student->id }})">
                                        <p class="font-semibold">{{ $student->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $student->code }} - {{ $student->user->email }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($selectedStudent)
                        <div class="mt-8">
                            <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-md mb-6">
                                <h3 class="text-lg font-semibold text-indigo-800">
                                    {{ $selectedStudent->user->name }}
                                </h3>
                                <p class="text-sm text-indigo-700">
                                    Código: {{ $selectedStudent->code }} | Carrera: {{ $selectedStudent->career->name }}
                                </p>
                            </div>

                            <h4 class="text-xl font-semibold text-gray-900 mb-2">Deudas Pendientes</h4>
                            <div class="overflow-x-auto border rounded-md mb-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Concepto</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Monto (S/.)</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Vencimiento</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Estado</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($pendingPayments as $payment)
                                            <tr>
                                                <td class="px-4 py-3">{{ $payment->paymentConcept->description }}</td>
                                                <td class="px-4 py-3">{{ number_format($payment->final_amount, 2) }}</td>
                                                <td class="px-4 py-3">{{ $payment->due_date->format('d/m/Y') }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <x-button wire:click="openPaymentModal({{ $payment->id }})">
                                                        Registrar Pago
                                                    </x-button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                                    El estudiante no tiene deudas pendientes.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <h4 class="text-xl font-semibold text-gray-900 mb-2">Últimos Pagos Realizados</h4>
                            <div class="overflow-x-auto border rounded-md">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Concepto</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Monto (S/.)</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Fecha de Pago</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Método</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Nro. Trans.</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($paidPayments as $payment)
                                            <tr>
                                                <td class="px-4 py-3">{{ $payment->paymentConcept->description }}</td>
                                                <td class="px-4 py-3">{{ number_format($payment->final_amount, 2) }}</td>
                                                <td class="px-4 py-3">{{ $payment->payment_date->format('d/m/Y h:i A') }}</td>
                                                <td class="px-4 py-3">{{ $payment->payment_method }}</td>
                                                <td class="px-4 py-3">{{ $payment->transaction_number }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                                    El estudiante no tiene pagos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500 pt-8">Busque y seleccione un estudiante para ver su estado de cuenta.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            Registrar Pago
        </x-slot>

        <x-slot name="content">
            @if ($paymentToRegister)
                <div class="space-y-4">
                    <p>Estás registrando el pago para:</p>
                    <div class="p-2 bg-gray-100 rounded">
                        <strong>Concepto:</strong> {{ $paymentToRegister->paymentConcept->description }}<br>
                        <strong>Monto:</strong> S/ {{ number_format($paymentToRegister->final_amount, 2) }}
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-label for="payment_date" value="Fecha y Hora de Pago" />
                            <x-input id="payment_date" type="datetime-local" class="mt-1 block w-full" wire:model="payment_date" />
                            <x-input-error for="payment_date" class="mt-2" />
                        </div>
                        <div>
                            <x-label for="payment_method" value="Método de Pago" />
                            <select id="payment_method" wire:model="payment_method" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="cash">Efectivo</option>
                                <option value="bank_transfer">Transferencia</option>
                                <option value="credit_card">Tarjeta de Crédito</option>
                                <option value="debit_card">Tarjeta de Débito</option>
                            </select>
                            <x-input-error for="payment_method" class="mt-2" />
                        </div>
                        <div class="col-span-2">
                            <x-label for="transaction_number" value="Nro. de Transacción (Opcional)" />
                            <x-input id="transaction_number" type="text" class="mt-1 block w-full" wire:model.blur="transaction_number" />
                            <x-input-error for="transaction_number" class="mt-2" />
                        </div>
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="registerPayment" wire:loading.attr="disabled">
                Confirmar Pago
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
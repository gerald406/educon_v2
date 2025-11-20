<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Pagos de Estudiantes (Deudas)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="mb-4 relative">
                        <x-label for="search" value="Buscar Estudiante (por Nombre, Código, DNI o Email)" />
                        <x-input id="search" type="text" class="mt-1 block w-full" 
                                 wire:model.live.debounce.300ms="search" 
                                 placeholder="Escriba al menos 3 caracteres..." />
                        
                        @if($searchResults->count() > 0)
                            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                @foreach($searchResults as $student)
                                    <div class="p-2 hover:bg-gray-100 cursor-pointer" 
                                         wire:click="selectStudent({{ $student->id }})">
                                        <p class="font-semibold">{{ $student->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $student->code }} - {{ $student->user->document_number }}</p>
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
                                    Código: {{ $selectedStudent->code }} | DNI: {{ $selectedStudent->user->document_number }}
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
                                            <th class="px-4 py-2 text-right text-xs font-medium">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($pendingPayments as $payment)
                                            <tr>
                                                <td class="px-4 py-3">{{ $payment->paymentConcept->description }}</td>
                                                <td class="px-4 py-3 font-bold text-red-600">{{ number_format($payment->final_amount, 2) }}</td>
                                                <td class="px-4 py-3">{{ $payment->due_date->format('d/m/Y') }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <x-button wire:click="openPaymentModal({{ $payment->id }})">
                                                        Pagar
                                                    </x-button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-4 py-3 text-center text-gray-500">
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
                                            <th class="px-4 py-2 text-left text-xs font-medium">Monto</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Fecha</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium">Comprobante</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($paidPayments as $payment)
                                            <tr>
                                                <td class="px-4 py-3">{{ $payment->paymentConcept->description }}</td>
                                                <td class="px-4 py-3">{{ number_format($payment->final_amount, 2) }}</td>
                                                <td class="px-4 py-3">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                                <td class="px-4 py-3">
                                                    @if($payment->voucher)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            {{ $payment->voucher->series }}-{{ $payment->voucher->number }}
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-gray-500">Sin Voucher</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    @if($payment->voucher)
                                                        <a href="{{ route('treasury.voucher.download', $payment->voucher->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">
                                                            Imprimir
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                                    No hay pagos registrados.
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
        <x-slot name="title">Registrar Pago de Deuda</x-slot>

        <x-slot name="content">
            @if ($paymentToRegister)
                <div class="space-y-4">
                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded text-sm">
                        <p><strong>Concepto:</strong> {{ $paymentToRegister->paymentConcept->description }}</p>
                        <p><strong>Monto a Pagar:</strong> S/ {{ number_format($paymentToRegister->final_amount, 2) }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <x-label for="voucher_type" value="Tipo de Comprobante" />
                            <select id="voucher_type" wire:model="voucher_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Seleccione --</option>
                                @foreach($availableVoucherTypes as $type)
                                    <option value="{{ $type }}">
                                        @if($type == 'boleta') Boleta
                                        @elseif($type == 'factura') Factura
                                        @elseif($type == 'recibo') Recibo Interno
                                        @else {{ ucfirst($type) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @if(empty($availableVoucherTypes))
                                <p class="text-xs text-red-500 mt-1">
                                    No hay series configuradas. Contacte al administrador.
                                </p>
                            @endif
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <x-label for="payment_method" value="Método de Pago" />
                            <select id="payment_method" wire:model="payment_method" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="cash">Efectivo</option>
                                <option value="bank_transfer">Transferencia Bancaria</option>
                                <option value="credit_card">Tarjeta de Crédito</option>
                                <option value="debit_card">Tarjeta de Débito</option>
                                <option value="check">Cheque</option>
                            </select>
                        </div>
                        
                        <div class="col-span-2">
                            <x-label for="transaction_number" value="Nro. Operación (Opcional)" />
                            <x-input id="transaction_number" type="text" class="mt-1 block w-full" wire:model.blur="transaction_number" />
                        </div>
                        
                        <div class="col-span-2">
                            <x-label for="observations" value="Observaciones" />
                            <textarea id="observations" wire:model="observations" class="form-textarea mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-button class="ms-3" wire:click="registerPayment" wire:loading.attr="disabled">
                Confirmar Pago
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-pdf', (event) => {
                window.open(event.url, '_blank');
            });
        });
    </script>
</div>
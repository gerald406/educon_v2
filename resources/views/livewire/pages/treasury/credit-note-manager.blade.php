<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Notas de Crédito (Anulaciones)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(!$activeSession)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Advertencia</p>
                    <p>No tienes una sesión de caja abierta. No podrás registrar anulaciones hasta que abras caja.</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-4">
                        <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar comprobante (Serie, Número o Cliente)..." class="w-1/2" />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Comprobante</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($vouchers as $voucher)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <span class="font-bold">{{ $voucher->voucher_type }}</span><br>
                                            {{ $voucher->series }}-{{ str_pad($voucher->number, 6, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-6 py-4">{{ $voucher->issued_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            {{ $voucher->client->name }}<br>
                                            <span class="text-xs text-gray-500">{{ $voucher->client->document_number }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-mono">S/ {{ number_format($voucher->total_amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $voucher->status == 'issued',
                                                'bg-red-100 text-red-800' => $voucher->status == 'annulled',
                                            ])>
                                                {{ $voucher->status == 'issued' ? 'Emitido' : 'Anulado' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('treasury.voucher.download', $voucher->id) }}" target="_blank" class="text-gray-500 hover:text-gray-700 text-xs underline">
                                                Voucher
                                            </a>
                                            
                                            @if($voucher->status == 'annulled' && $voucher->creditNote)
                                                <a href="{{ route('treasury.credit-note.download', $voucher->creditNote->id) }}" target="_blank" class="text-red-600 hover:text-red-800 text-sm font-bold underline ml-2">
                                                    Nota Crédito
                                                </a>
                                            @endif
                                            
                                            @if($voucher->status == 'issued' && $activeSession)
                                                <x-danger-button wire:click="openAnnulmentModal({{ $voucher->id }})">
                                                    Anular
                                                </x-danger-button>
                                            @endif
                                        </td>

                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No se encontraron comprobantes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">{{ $vouchers->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="isModalOpen">
        <x-slot name="title">
            Anular Comprobante: {{ $voucherToAnnul ? $voucherToAnnul->series . '-' . $voucherToAnnul->number : '' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div class="p-4 bg-red-50 border border-red-200 rounded text-red-800 text-sm">
                    <p class="font-bold">¡Advertencia!</p>
                    <p>Esta acción es irreversible. Se generará una Nota de Crédito y las deudas asociadas volverán a estado "Pendiente".</p>
                </div>

                <div>
                    <x-label for="reason" value="Motivo de la Anulación" />
                    <textarea id="reason" wire:model="reason" class="form-textarea mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3" placeholder="Ej. Error en el monto, datos incorrectos..."></textarea>
                    <x-input-error for="reason" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal">Cancelar</x-secondary-button>
            <x-danger-button class="ms-3" wire:click="processAnnulment" wire:loading.attr="disabled">
                Confirmar Anulación
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
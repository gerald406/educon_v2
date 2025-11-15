<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Sesión de Caja
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($activeSession)
                        <h3 class="text-2xl font-medium text-gray-900 mb-2">
                            Cierre de Caja (Sesión #{{ $activeSession->id }})
                        </h3>
                        <p class="text-gray-600">
                            Iniciada por: <strong>{{ $activeSession->user->name }}</strong> 
                            el <strong>{{ $activeSession->opening_time->format('d/m/Y h:i A') }}</strong>
                        </p>
                        
                        <div class="mt-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 rounded-md border">
                                    <p class="text-sm font-medium text-gray-500">Monto de Apertura (S/)</p>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($activeSession->opening_balance, 2) }}</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-md border">
                                    <p class="text-sm font-medium text-gray-500">Total de Pagos Registrados (S/)</p>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($total_payments, 2) }}</p>
                                </div>
                                <div class="p-4 bg-blue-50 rounded-md border border-blue-200 col-span-2">
                                    <p class="text-sm font-medium text-blue-600">Total Calculado en Caja (S/)</p>
                                    <p class="mt-1 text-3xl font-bold text-blue-800">
                                        {{ number_format($calculated_balance, 2) }}
                                    </p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div>
                                <x-label for="closing_balance" value="Monto Final Contado en Caja (Efectivo)" class="font-bold" />
                                <x-input id="closing_balance" type="number" step="0.10" class="mt-1 block w-full text-lg" 
                                         wire:model.live.debounce.300ms="closing_balance" />
                                <x-input-error for="closing_balance" class="mt-2" />
                            </div>
                            
                            <div>
                                <h4 class="text-lg font-medium">Diferencia (Sobrante/Faltante)</h4>
                                <p @class([
                                    'mt-1 text-3xl font-bold',
                                    'text-green-600' => $difference > 0,
                                    'text-red-600' => $difference < 0,
                                    'text-gray-900' => $difference == 0,
                                ])>
                                    {{ number_format($difference, 2) }}
                                </p>
                                <span class="text-sm text-gray-500">
                                    @if($difference > 0) (Sobrante) @elseif($difference < 0) (Faltante) @else (Cuadre Perfecto) @endif
                                </span>
                            </div>

                            <div class="flex justify-end pt-4">
                                <x-danger-button wire:click="closeSession" wire:loading.attr="disabled">
                                    Cerrar Caja
                                </x-danger-button>
                            </div>
                        </div>

                    @else
                        <h3 class="text-2xl font-medium text-gray-900 mb-4">
                            Apertura de Caja
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Debe abrir una sesión de caja antes de poder registrar pagos.
                        </p>
                        
                        <div class="space-y-4">
                            <div>
                                <x-label for="opening_balance" value="Monto Inicial en Caja (S/)" />
                                <x-input id="opening_balance" type="number" step="0.10" class="mt-1 block w-full" wire:model.blur="opening_balance" />
                                <x-input-error for="opening_balance" class="mt-2" />
                            </div>
                            
                            <x-button wire:click="openSession" wire:loading.attr="disabled">
                                Iniciar Sesión de Caja
                            </x-button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
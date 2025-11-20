<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Punto de Venta - Trámites TUPA
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (!$activeSession)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">¡Caja Cerrada!</strong>
                    <span class="block sm:inline">Debe abrir una sesión de caja para poder realizar ventas.</span>
                    <a href="{{ route('treasury.cash-sessions') }}" class="underline font-bold ml-2">Ir a Caja</a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="lg:col-span-2 space-y-6">
                        
                        <div class="bg-white shadow sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">1. Datos del Cliente</h3>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <x-input type="text" class="w-full" placeholder="Buscar por Nombre o DNI..." 
                                             wire:model.live.debounce.300ms="userSearch" />
                                    
                                    @if($usersFound->count() > 0)
                                        <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                            @foreach($usersFound as $user)
                                                <div class="p-2 hover:bg-gray-100 cursor-pointer" wire:click="selectUser({{ $user->id }})">
                                                    <div class="font-bold">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user->document_number }} - {{ $user->email }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <x-secondary-button wire:click="openExternalUserModal">
                                    + Nuevo
                                </x-secondary-button>
                            </div>

                            @if($selectedUser)
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded flex justify-between items-center">
                                    <div>
                                        <span class="block font-bold text-blue-800">{{ $selectedUser->name }}</span>
                                        <span class="block text-sm text-blue-600">DOC: {{ $selectedUser->document_number }}</span>
                                    </div>
                                    <button wire:click="$set('selectedUser', null)" class="text-red-500 hover:text-red-700 text-sm">Cambiar</button>
                                </div>
                            @endif
                            <x-input-error for="selectedUser" class="mt-1" />
                        </div>

                        <div class="bg-white shadow sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">2. Conceptos a Pagar</h3>
                            <div class="relative mb-4">
                                <x-input type="text" class="w-full" placeholder="Buscar concepto TUPA..." 
                                         wire:model.live.debounce.300ms="conceptSearch" />
                                
                                @if($conceptsFound->count() > 0)
                                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                        @foreach($conceptsFound as $concept)
                                            <div class="p-2 hover:bg-gray-100 cursor-pointer border-b flex justify-between items-center" 
                                                 wire:click="addToCart({{ $concept->id }})">
                                                <div>
                                                    <div class="font-bold">{{ $concept->description }}</div>
                                                    <div class="text-xs text-gray-500">Cód: {{ $concept->code }}</div>
                                                </div>
                                                <div class="font-bold text-green-600">S/ {{ number_format($concept->amount, 2) }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if(count($cart) > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Descripción</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Cant.</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Subtotal</th>
                                            <th class="px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($cart as $index => $item)
                                            <tr>
                                                <td class="px-4 py-2">{{ $item['description'] }}</td>
                                                <td class="px-4 py-2 text-center">{{ $item['quantity'] }}</td>
                                                <td class="px-4 py-2 text-right font-mono">S/ {{ number_format($item['subtotal'], 2) }}</td>
                                                <td class="px-4 py-2 text-right">
                                                    <button wire:click="removeFromCart({{ $index }})" class="text-red-500 hover:text-red-700">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-100">
                                            <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-900">TOTAL A PAGAR:</td>
                                            <td class="px-4 py-3 text-right font-bold text-xl text-gray-900">S/ {{ number_format($totalAmount, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            @else
                                <p class="text-center text-gray-500 py-4">El carrito está vacío.</p>
                            @endif
                            <x-input-error for="cart" class="mt-1" />
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white shadow sm:rounded-lg p-6 sticky top-24">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">3. Procesar Pago</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <x-label for="voucher_type" value="Tipo de Comprobante" />
                                    <select id="voucher_type" wire:model="voucher_type" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="recibo">Recibo de Caja</option>
                                        <option value="boleta">Boleta</option>
                                        <option value="factura">Factura</option>
                                    </select>
                                    <x-input-error for="voucher_type" class="mt-1" />
                                </div>

                                <div>
                                    <x-label for="payment_method" value="Método de Pago" />
                                    <select id="payment_method" wire:model="payment_method" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Yape/Plin">Yape / Plin</option>
                                        <option value="Tarjeta">Tarjeta de Crédito/Débito</option>
                                        <option value="Deposito">Depósito Bancario</option>
                                    </select>
                                    <x-input-error for="payment_method" class="mt-1" />
                                </div>

                                @if($payment_method != 'Efectivo')
                                    <div>
                                        <x-label for="transaction_code" value="Nro. Operación / Referencia" />
                                        <x-input id="transaction_code" type="text" class="mt-1 block w-full" wire:model="transaction_code" />
                                        <x-input-error for="transaction_code" class="mt-1" />
                                    </div>
                                @endif
                                
                                <div>
                                    <x-label for="observations" value="Observaciones (Opcional)" />
                                    <textarea id="observations" wire:model="observations" class="form-textarea mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="2"></textarea>
                                </div>

                                <div class="pt-4">
                                    <x-button wire:click="processPayment" class="w-full justify-center h-12 text-lg bg-green-600 hover:bg-green-700" 
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove>COBRAR S/ {{ number_format($totalAmount, 2) }}</span>
                                        <span wire:loading>Procesando...</span>
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>

    <x-dialog-modal wire:model.live="isExternalUserModalOpen">
        <x-slot name="title">Registrar Cliente Externo</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-label for="new_user_dni" value="DNI / Documento" />
                    <x-input id="new_user_dni" type="text" class="mt-1 block w-full" wire:model="new_user_dni" />
                    <x-input-error for="new_user_dni" class="mt-1" />
                </div>
                <div>
                    <x-label for="new_user_name" value="Nombres y Apellidos" />
                    <x-input id="new_user_name" type="text" class="mt-1 block w-full" wire:model="new_user_name" />
                    <x-input-error for="new_user_name" class="mt-1" />
                </div>
                <div>
                    <x-label for="new_user_email" value="Email (Opcional)" />
                    <x-input id="new_user_email" type="email" class="mt-1 block w-full" wire:model="new_user_email" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isExternalUserModalOpen', false)">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="saveExternalUser">Guardar Cliente</x-button>
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
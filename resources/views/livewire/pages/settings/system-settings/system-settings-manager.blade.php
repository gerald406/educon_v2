<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Opciones del Sistema
            </h2>
            <x-button wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Guardar Cambios</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </x-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    <form wire:submit.prevent="save">
                        <div class="space-y-6">
                            @php $currentModule = ''; @endphp

                            @forelse ($settings as $setting)
                                @if ($setting->module != $currentModule)
                                    @if ($currentModule != '')
                                        </div> @endif
                                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 capitalize">
                                        Módulo: {{ $setting->module ?? 'General' }}
                                    </h3>
                                    <div class="space-y-4 pl-4"> @php $currentModule = $setting->module; @endphp
                                @endif

                                <div>
                                    <x-label for="values.{{ $setting->key_name }}" value="{{ $setting->description }}" />
                                    
                                    @if ($setting->data_type == 'boolean')
                                        <x-checkbox id="values.{{ $setting->key_name }}" class="mt-1" wire:model="values.{{ $setting->key_name }}" />
                                    @elseif ($setting->data_type == 'integer' || $setting->data_type == 'decimal')
                                        <x-input id="values.{{ $setting->key_name }}" type="number" class="mt-1 block w-full md:w-1/2" 
                                                 wire:model.blur="values.{{ $setting->key_name }}" 
                                                 step="{{ $setting->data_type == 'decimal' ? '0.01' : '1' }}" />
                                    @else
                                        <x-input id="values.{{ $setting->key_name }}" type="text" class="mt-1 block w-full md:w-1/2" 
                                                 wire:model.blur="values.{{ $setting->key_name }}" />
                                    @endif
                                    
                                    <x-input-error for="values.{{ $setting->key_name }}" class="mt-1" />
                                </div>

                            @empty
                                <p class="text-gray-500">No hay configuraciones editables disponibles.</p>
                            @endforelse
                            
                            @if ($settings->count() > 0)
                                </div> @endif
                        </div>

                        <div class="flex justify-end mt-8 border-t pt-6">
                            <x-button wire:click="save" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">Guardar Cambios</span>
                                <span wire:loading wire:target="save">Guardando...</span>
                            </x-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
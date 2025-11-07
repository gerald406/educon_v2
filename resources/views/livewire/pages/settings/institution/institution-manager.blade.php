<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Datos de la Institución
            </h2>
            <x-button wire:click="save" wire:loading.attr="disabled">
                Guardar Cambios
            </x-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="save" class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($institution)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium text-gray-900">Logo Institucional</h3>
                                <p class="mt-1 text-sm text-gray-600">Actualice el logo de la institución.</p>
                                
                                <div class="mt-4">
                                    @if ($logoUpload)
                                        <img src="{{ $logoUpload->temporaryUrl() }}" alt="Vista previa del logo" class="w-48 h-48 object-contain rounded-md border bg-gray-100">
                                    @elseif ($logo_url)
                                        <img src="{{ asset('storage/' . $logo_url) }}" alt="Logo actual" class="w-48 h-48 object-contain rounded-md border bg-gray-100">
                                    @else
                                        <div class="w-48 h-48 bg-gray-100 rounded-md flex items-center justify-center text-gray-500">
                                            Sin logo
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-4">
                                    <x-input id="logoUpload" type="file" class="mt-1 block w-full" wire:model="logoUpload" />
                                    <x-input-error for="logoUpload" class="mt-2" />
                                    <div wire:loading wire:target="logoUpload" class="text-sm text-gray-500 mt-1">Cargando...</div>
                                </div>
                            </div>

                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <h3 class="col-span-2 text-lg font-medium text-gray-900">Información General</h3>

                                <div class="col-span-2">
                                    <x-label for="name" value="Nombre de la Institución" />
                                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model.blur="name" />
                                    <x-input-error for="name" class="mt-2" />
                                </div>
                                
                                <div class="col-span-1">
                                    <x-label for="code" value="Código Modular" />
                                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model.blur="code" />
                                    <x-input-error for="code" class="mt-2" />
                                </div>
                                
                                <div class="col-span-1">
                                    <x-label for="tax_id" value="RUC" />
                                    <x-input id="tax_id" type="text" class="mt-1 block w-full" wire:model.blur="tax_id" />
                                    <x-input-error for="tax_id" class="mt-2" />
                                </div>

                                <div class="col-span-1">
                                    <x-label for="email" value="Email" />
                                    <x-input id="email" type="email" class="mt-1 block w-full" wire:model.blur="email" />
                                    <x-input-error for="email" class="mt-2" />
                                </div>

                                <div class="col-span-1">
                                    <x-label for="phone" value="Teléfono" />
                                    <x-input id="phone" type="text" class="mt-1 block w-full" wire:model.blur="phone" />
                                    <x-input-error for="phone" class="mt-2" />
                                </div>
                                
                                <div class="col-span-1">
                                    <x-label for="website" value="Sitio Web" />
                                    <x-input id="website" type="url" class="mt-1 block w-full" wire:model.blur="website" placeholder="https://..." />
                                    <x-input-error for="website" class="mt-2" />
                                </div>

                                <div class="col-span-1">
                                    <x-label for="status" value="Estado" />
                                    <select id="status" wire:model="status" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="active">Activa</option>
                                        <option value="inactive">Inactiva</option>
                                    </select>
                                    <x-input-error for="status" class="mt-2" />
                                </div>

                                <div class="col-span-2">
                                    <x-label for="address" value="Dirección" />
                                    <textarea id="address" wire:model.blur="address" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                    <x-input-error for="address" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 border-t pt-6">
                            <x-button wire:click="save" wire:loading.attr="disabled">
                                Guardar Cambios
                            </x-button>
                        </div>
                    @else
                        <p class="text-center text-red-500 font-semibold">Error: No se pudo cargar la información de la institución.</p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
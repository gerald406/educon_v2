<div class="max-w-4xl mx-auto py-10">
    
    <div class="flex justify-between mb-8">
        <div class="{{ $currentStep >= 1 ? 'text-blue-600 font-bold' : 'text-gray-400' }}">1. Identificación</div>
        <div class="{{ $currentStep >= 2 ? 'text-blue-600 font-bold' : 'text-gray-400' }}">2. Datos Personales</div>
        <div class="{{ $currentStep >= 3 ? 'text-blue-600 font-bold' : 'text-gray-400' }}">3. Colegio</div>
        <div class="{{ $currentStep >= 4 ? 'text-blue-600 font-bold' : 'text-gray-400' }}">4. Postulación</div>
    </div>

    @if($currentStep == 1)
        <div class="bg-white p-6 shadow rounded-lg">
            <h3 class="text-lg font-medium mb-4">Ingrese su DNI</h3>
            <div class="flex gap-2">
                <input type="text" wire:model="dni" class="form-input w-full border-gray-300 rounded-md" placeholder="8 dígitos" maxlength="8">
                <button wire:click="searchDni" class="bg-blue-600 text-white px-4 py-2 rounded-md">Buscar</button>
            </div>
            @error('dni') <span class="text-red-500">{{ $message }}</span> @enderror
            @if($message) <div class="mt-2 text-blue-600">{{ $message }}</div> @endif
        </div>
    @endif

    @if($currentStep == 2)
        <div class="bg-white p-6 shadow rounded-lg grid grid-cols-2 gap-4">
            <div class="col-span-2"><h3 class="text-lg font-medium">Datos Personales</h3></div>
            
            <input type="text" wire:model="name" class="form-input border-gray-300 rounded-md" placeholder="Nombres">
            <input type="text" wire:model="lastname" class="form-input border-gray-300 rounded-md" placeholder="Apellidos">
            
            <select wire:model.live="selectedDep" class="form-select border-gray-300 rounded-md">
                <option value="">Departamento...</option>
                @foreach($departments as $dep) <option value="{{ $dep }}">{{ $dep }}</option> @endforeach
            </select>
            
            <select wire:model.live="selectedProv" class="form-select border-gray-300 rounded-md">
                <option value="">Provincia...</option>
                @foreach($provinces as $prov) <option value="{{ $prov }}">{{ $prov }}</option> @endforeach
            </select>
            
            <select wire:model="selectedDist" class="form-select border-gray-300 rounded-md">
                <option value="">Distrito...</option>
                @foreach($districts as $id => $dist) <option value="{{ $id }}">{{ $dist }}</option> @endforeach
            </select>

            <div class="col-span-2 flex justify-end">
                <button wire:click="submitStep2" class="bg-blue-600 text-white px-6 py-2 rounded-md">Siguiente</button>
            </div>
        </div>
    @endif

    @if($currentStep == 3)
        <div class="bg-white p-6 shadow rounded-lg">
            <h3 class="text-lg font-medium mb-4">Colegio de Procedencia</h3>
            
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="schoolSearch" class="w-full border-gray-300 rounded-md" placeholder="Escriba el nombre del colegio...">
                @if(count($schoolResults) > 0)
                    <ul class="absolute z-10 w-full bg-white border shadow-lg mt-1 max-h-40 overflow-y-auto">
                        @foreach($schoolResults as $school)
                            <li class="p-2 hover:bg-gray-100 cursor-pointer" wire:click="selectSchool({{ $school->id }}, '{{ $school->name }}')">
                                {{ $school->name }} ({{ $school->modular_code }})
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            
            @if($selectedSchoolName)
                <div class="mt-2 p-2 bg-green-100 text-green-800 rounded">Seleccionado: {{ $selectedSchoolName }}</div>
            @endif

            <input type="number" wire:model="schoolYear" class="mt-4 w-full border-gray-300 rounded-md" placeholder="Año de Egreso">

            <div class="mt-6 flex justify-end">
                <button wire:click="submitStep3" class="bg-blue-600 text-white px-6 py-2 rounded-md">Siguiente</button>
            </div>
        </div>
    @endif

    @if($currentStep == 4)
        <div class="bg-white p-6 shadow rounded-lg space-y-4">
            <h3 class="text-lg font-medium">Datos de Postulación</h3>
            
            <select wire:model="selectedOfferingId" class="w-full border-gray-300 rounded-md">
                <option value="">Seleccione Carrera y Turno...</option>
                @foreach($offerings as $offer)
                    <option value="{{ $offer->id }}">{{ $offer->career->name }} - {{ $offer->shift->name }}</option>
                @endforeach
            </select>

            <select wire:model="selectedModalityId" class="w-full border-gray-300 rounded-md">
                <option value="">Seleccione Modalidad...</option>
                @foreach($modalities as $mode)
                    <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                @endforeach
            </select>
            
            <div class="grid grid-cols-2 gap-4">
                <select wire:model="selectedFinancialEntityId" class="border-gray-300 rounded-md">
                    <option value="">Banco/Caja...</option>
                    @foreach($financialEntities as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
                <input type="text" wire:model="paymentCode" class="border-gray-300 rounded-md" placeholder="Nro Operación">
            </div>

            <div class="mt-6 flex justify-end">
                <button wire:click="submitFinal" class="bg-green-600 text-white px-6 py-2 rounded-md font-bold">FINALIZAR INSCRIPCIÓN</button>
            </div>
        </div>
    @endif
</div>
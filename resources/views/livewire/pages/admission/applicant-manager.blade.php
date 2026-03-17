<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Postulantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-4">
                    <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o DNI en la lista..." class="w-1/2" />
                    <x-button wire:click="openCreateModal">Nuevo Postulante</x-button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Postulante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carrera</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modalidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nota</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($applicants as $applicant)
                                <tr>
                                    <td class="px-6 py-4">{{ $applicant->user->document_number }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold">{{ $applicant->user->lastname }}</div>
                                        <div class="text-sm">{{ $applicant->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $applicant->admissionOffering->career->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $applicant->admissionModality->name ?? '-' }}</td>
                                    <td class="px-6 py-4 font-bold">{{ $applicant->exam_score ?? '-' }}</td>
                                    
                                   {{--  <td class="px-6 py-4 text-right">
                                        <x-button wire:click="openEditModal({{ $applicant->id }})">Editar</x-button>
                                    </td> --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('admission.constancia', $applicant->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3 inline-flex items-center" title="Descargar Constancia">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                        <a href="{{ route('admission.ficha', $applicant->id) }}" target="_blank" class="text-green-600 hover:text-green-900 mr-3 inline-flex items-center" title="Descargar Ficha">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        </a>
                                        @if($applicant->application_status != 'approved' && $applicant->application_status != 'aprobado') 
                                            <button wire:click="openMigrationModal({{ $applicant->id }})" 
                                                    class="ml-2 text-purple-600 hover:text-purple-900" 
                                                    title="Registrar Ingresante">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                            </button>
                                        @endif

                                        
                                        @if($applicant->application_status === 'aprobado' && $applicant->user->student)
                                            <a href="{{ route('people.students.enrollment-form', $applicant->user->student->id) }}" 
                                            target="_blank" 
                                            class="text-green-600 hover:text-green-900 mr-3 inline-flex items-center" 
                                            title="Descargar Ficha de Matrícula">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                        @endif

                                        {{-- <x-button wire:click="openEditModal({{ $applicant->id }})">Editar</x-button> --}}
                                        <button wire:click="openEditModal({{ $applicant->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        <button wire:click="confirmDelete({{ $applicant->id }})" class="text-red-600 hover:text-red-900 ml-2" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-gray-500">No hay postulantes registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $applicants->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="2xl">
        <x-slot name="title">
            {{ $editingApplicant ? 'Editar' : 'Registrar' }} Postulante
        </x-slot>
        
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                @if(!$editingApplicant)
                <div class="md:col-span-3 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <label class="block text-sm font-medium text-gray-700">Ingrese DNI para buscar (RENIEC/Local)</label>
                    <div class="flex mt-1 gap-2">
                        <x-input type="text" wire:model="searchDni" class="flex-1" placeholder="8 dígitos" maxlength="8"/>
                        <x-button wire:click="searchPersonByDni" wire:loading.attr="disabled">
                            <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span wire:loading class="text-xs">Buscando...</span>
                        </x-button>
                    </div>
                    <x-input-error for="searchDni" class="mt-2" />
                </div>
                @endif

                <div class="md:col-span-3 border-b pb-1 mb-2 font-bold text-gray-700 mt-2">1. Datos Personales</div>

                <div>
                    <x-label>DNI</x-label>
                    <x-input type="text" class="w-full bg-gray-100" wire:model="dni" readonly />
                    <x-input-error for="dni" class="mt-2" />
                </div>
                
                <div>
                    <x-label>Apellido Paterno</x-label>
                    <x-input type="text" class="w-full" wire:model="paternal_surname" />
                    <x-input-error for="paternal_surname" class="mt-2" />
                </div>
                <div>
                    <x-label>Apellido Materno</x-label>
                    <x-input type="text" class="w-full" wire:model="maternal_surname" />
                    <x-input-error for="maternal_surname" class="mt-2" />
                </div>
                <div class="md:col-span-3">
                    <x-label>Nombres</x-label>
                    <x-input type="text" class="w-full" wire:model="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>
                
                <div>
                    <x-label>Email</x-label>
                    <x-input type="email" class="w-full" wire:model="email"/>
                    <x-input-error for="email" class="mt-2" />
                </div>
                <div>
                    <x-label>Celular</x-label>
                    <x-input type="text" class="w-full" wire:model="phone"/>
                    <x-input-error for="phone" class="mt-2" />
                </div>
                
                <div>
                    <x-label>Fecha Nacimiento</x-label>
                    <x-input type="date" class="w-full" wire:model="birthday"/>
                    <x-input-error for="birthday" class="mt-2" />
                </div>
                <div>
                    <x-label>Sexo</x-label>
                    <select wire:model="gender" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Seleccione...</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                    </select>
                    <x-input-error for="gender" class="mt-2" />
                </div>
                <div class="md:col-span-2"> 
                    <x-label>Dirección Domiciliaria</x-label>
                    <x-input type="text" class="w-full" wire:model="address" placeholder="Av. / Jr. / Calle..." />
                    <x-input-error for="address" class="mt-2" />
                </div>
                
                
                <div class="md:col-span-3">
                     <x-label>Foto (Tamaño Pasaporte)</x-label>
                     <input type="file" wire:model="photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                     @if ($photo)
                        <span class="text-green-600 text-xs">Imagen seleccionada</span>
                     @elseif ($photo_url_db)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$photo_url_db) }}" class="h-20 w-20 object-cover rounded">
                        </div>
                     @endif
                     <x-input-error for="photo" class="mt-2" />
                </div>

                <div class="md:col-span-3 relative">
                    <x-label>Lugar de Nacimiento (Distrito)</x-label>
                    <x-input type="text" class="w-full" wire:model.live.debounce.300ms="ubigeoSearch" placeholder="Escriba el nombre del distrito..." />
                    
                    @if(!empty($ubigeoResults))
                        <ul class="absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto">
                            @foreach($ubigeoResults as $loc)
                                <li class="p-2 hover:bg-gray-100 cursor-pointer text-sm border-b" 
                                    wire:click="selectUbigeo('{{ $loc['iddist'] }}', '{{ $loc['nombdep'] }} / {{ $loc['nombprov'] }} / {{ $loc['nombdist'] }}')">
                                    <span class="font-bold">{{ $loc['nombdist'] }}</span> 
                                    <span class="text-xs text-gray-500">({{ $loc['nombprov'] }}, {{ $loc['nombdep'] }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                    @if($selectedUbigeoName)
                        <div class="mt-1 p-2 bg-green-50 border border-green-200 rounded text-sm text-green-800">
                            <strong>Seleccionado:</strong> {{ $selectedUbigeoName }}
                        </div>
                    @endif
                    <x-input-error for="selectedDistId" class="mt-2" />
                </div>

                <div class="md:col-span-3 border-b pb-1 mb-2 mt-4 font-bold text-gray-700">2. Datos del Colegio</div>
                
                <div class="md:col-span-2 relative">
                    <x-label>Buscar Colegio</x-label>
                    <x-input type="text" class="w-full" wire:model.live.debounce.300ms="schoolSearch" placeholder="Escriba el nombre del colegio..." />
                    
                    @if(!empty($schoolResults))
                        <ul class="absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto">
                            @foreach($schoolResults as $school)
                                <li class="p-2 hover:bg-gray-100 cursor-pointer text-sm border-b transition-colors" 
                                    wire:click="selectSchool({{ $school->id }}, '{{ $school->name }}')">
                                    <span class="font-bold block">{{ $school->name }}</span>
                                    <span>
                                        <span class="font-semibold text-blue-600">{{ $school['d_niv_mod'] ?? 'N/A' }}</span>
                                        
                                        <span class="mx-1">|</span>
                                        
                                        {{ $school['location']['nombdist'] ?? '' }} - {{ $school['location']['nombdep'] ?? '' }}
                                    </span>
                                    
                                    <span>Cód: {{ $school['modular_code'] }}</span>

                                    {{-- <span class="text-xs text-gray-500">
                                        {{ $school->location->nombdist ?? '?' }} - {{ $school->location->nombdep ?? '?' }}
                                    </span> --}}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                    @if($selectedSchoolName)
                        <div class="mt-1 p-2 bg-green-50 border border-green-200 rounded text-sm text-green-800">
                            <strong>Seleccionado:</strong> {{ $selectedSchoolName }}
                        </div>
                    @endif
                    <x-input-error for="selectedSchoolId" class="mt-2" />
                </div>
                <div>
                    <x-label>Año Egreso</x-label>
                    <x-input type="number" class="w-full" wire:model="schoolYear"/>
                    <x-input-error for="schoolYear" class="mt-2" />
                </div>

                <div class="md:col-span-3 border-b pb-1 mb-2 mt-4 font-bold text-gray-700">3. Datos de Postulación</div>

                <div class="md:col-span-3">
                    <x-label>Programa y Turno</x-label>
                    <select wire:model="selectedOfferingId" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Seleccione...</option>
                        @foreach($offerings as $offer)
                            <option value="{{ $offer->id }}">
                                {{ $offer->career->name }} - {{ $offer->shift->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error for="selectedOfferingId" class="mt-2" />
                </div>
                
                <div class="md:col-span-3">
                    <x-label>Modalidad</x-label>
                    <select wire:model="selectedModalityId" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Seleccione...</option>
                        @foreach($modalities as $m) 
                            <option value="{{ $m->id }}">{{ $m->name }}</option> 
                        @endforeach
                    </select>
                    <x-input-error for="selectedModalityId" class="mt-2" />
                </div>
                
                <div class="md:col-span-2">
                    <x-label>Entidad Financiera</x-label>
                    <select wire:model="selectedFinancialEntityId" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Seleccione...</option>
                        @foreach($financialEntities as $fe) 
                            <option value="{{ $fe->id }}">{{ $fe->name }}</option> 
                        @endforeach
                    </select>
                    <x-input-error for="selectedFinancialEntityId" class="mt-2" />
                </div>
                <div>
                    <x-label>Cód. Operación</x-label>
                    <x-input type="text" class="w-full" wire:model="paymentCode"/>
                    <x-input-error for="paymentCode" class="mt-2" />
                </div>
                
                @if($editingApplicant)
                <div class="md:col-span-3 bg-yellow-50 p-2 rounded border border-yellow-200 mt-2">
                    <x-label>Nota Examen</x-label>
                    <x-input type="number" step="0.01" class="w-full" wire:model="examScore"/>
                </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isModalOpen', false)">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="save">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="isMigrationModalOpen" maxWidth="lg">
        <x-slot name="title">
            Registrar Ingresante
        </x-slot>

        <x-slot name="content">
            @if($migratingApplicant)
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                        <h4 class="font-bold text-blue-900 text-lg">{{ $migratingApplicant->user->lastname }} {{ $migratingApplicant->user->name }}</h4>
                        <p class="text-sm text-blue-700">DNI: {{ $migratingApplicant->user->document_number }}</p>
                        <p class="text-sm text-blue-700 mt-1">Carrera: <strong>{{ $migratingApplicant->admissionOffering->career->name }}</strong></p>
                    </div>

                    <div>
                        <x-label value="1. Seleccione el Plan de Estudios" class="font-bold mb-1" />
                        <select wire:model="selectedMigrationStudyPlanId" class="w-full border-gray-300 rounded-md shadow-sm">
                            @forelse($migrationStudyPlans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->code }})</option>
                            @empty
                                <option value="">No hay planes activos para esta carrera</option>
                            @endforelse
                        </select>
                        @if(empty($migrationStudyPlans))
                            <p class="text-red-500 text-xs mt-1">¡Error! No se puede matricular sin un plan de estudios activo.</p>
                        @endif
                    </div>

                    <div>
                        <x-label value="2. Código de Estudiante Asignado" class="font-bold mb-1" />
                        <x-input type="text" class="w-full bg-gray-100" wire:model="migrationStudentCode" readonly />
                        <p class="text-xs text-gray-500 mt-1">Este código se ha generado automáticamente.</p>
                    </div>

                    <div class="bg-green-50 p-4 rounded-md border border-green-200">
                        <h4 class="font-bold text-green-900 text-sm mb-2">3. Validación de Pago (Caja)</h4>
                        <x-label value="Número de Recibo / Voucher" class="mb-1" />
                        <div class="flex gap-2">
                            <x-input type="text" class="w-full" wire:model="migrationVoucherNumber" placeholder="Ingrese el Nro. de Recibo (ej. 123)" />
                        </div>
                        <p class="text-xs text-green-700 mt-2">
                            El estudiante debe haber pagado su derecho de matrícula en Caja previamente. 
                            Ingrese el número del comprobante para validar y procesar la matrícula automática.
                        </p>
                    </div>

                    <div class="border-t pt-4 mt-4">
                        <p class="text-sm text-gray-600">
                            Al confirmar:
                            <ul class="list-disc list-inside text-xs mt-1 ml-2">
                                <li>Se creará el perfil de estudiante con código <strong>{{ $migrationStudentCode }}</strong>.</li>
                                <li>Se generará la <strong>Matrícula Automática</strong> para el Semestre 1.</li>
                                <li>Se inscribirá en todos los cursos del turno correspondiente.</li>
                                <li>No se generarán deudas pendientes.</li>
                            </ul>
                        </p>
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeMigrationModal">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2 bg-purple-600 hover:bg-purple-700" 
                    wire:click="processMigration" 
                    wire:loading.attr="disabled"
                    :disabled="empty($migrationStudyPlans)">
                Confirmar Ingreso
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
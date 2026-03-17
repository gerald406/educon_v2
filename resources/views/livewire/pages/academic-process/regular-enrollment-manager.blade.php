<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Proceso de Matrícula Regular
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-visible shadow-xl sm:rounded-lg p-6 min-h-[500px]"> 
                
                @if(!$selectedStudent)
                    <div class="max-w-xl mx-auto mt-10">
                        <div class="text-center mb-8">
                            <h3 class="text-xl font-bold text-gray-700">Nueva Matrícula</h3>
                            <p class="text-gray-500 mt-2">Busque al estudiante por DNI o Apellidos.</p>
                        </div>

                        <div class="relative">
                            <x-input type="text" 
                                     class="w-full text-lg p-4 pl-12 border-2 border-indigo-100 focus:border-indigo-500 rounded-xl" 
                                     wire:model.live.debounce.300ms="search" 
                                     placeholder="DNI o Nombre..." 
                                     autofocus />
                            
                            <div wire:loading.remove wire:target="search" class="absolute top-4 left-4 text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <div wire:loading wire:target="search" class="absolute top-4 left-4 text-indigo-500">
                                <svg class="animate-spin w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>

                            @if($searchResults->isNotEmpty())
                                <div class="absolute z-50 w-full bg-white border border-gray-200 rounded-xl shadow-2xl mt-2 overflow-hidden">
                                    @foreach($searchResults as $result)
                                        <div wire:click="selectStudent({{ $result->id }})" class="p-4 hover:bg-indigo-50 cursor-pointer border-b last:border-0 transition">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div class="font-bold text-gray-800 text-lg">{{ $result->user->lastname }}, {{ $result->user->name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        <span class="font-semibold text-indigo-600">{{ $result->career->code ?? 'N/A' }}</span> 
                                                        | Semestre: {{ $result->current_semester }}
                                                    </div>
                                                </div>
                                                <span class="bg-gray-100 text-gray-600 font-mono text-sm px-3 py-1 rounded-full border">
                                                    {{ $result->user->document_number }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(strlen($search) > 2)
                                <div class="absolute z-50 w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-2 p-6 text-center text-gray-500">
                                    No se encontraron resultados.
                                </div>
                            @endif
                        </div>
                    </div>

                @else
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <div class="lg:col-span-1 space-y-6">
                            <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                                <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $selectedStudent->user->name }} {{ $selectedStudent->user->lastname }}</h3>
                                <p class="text-sm text-indigo-600 font-mono mb-4">{{ $selectedStudent->code }}</p>
                                
                                <div class="space-y-2 text-sm text-gray-700">
                                    <p><span class="font-semibold">Carrera:</span> {{ $selectedStudent->career->name ?? 'N/A' }}</p>
                                    <p><span class="font-semibold">Semestre:</span> <span class="bg-white px-2 py-0.5 rounded border border-indigo-200">{{ $nextSemester }}°</span></p>
                                </div>
                                <button wire:click="cancelSelection" class="mt-4 text-sm text-red-600 hover:text-red-800 underline">Cambiar estudiante</button>
                            </div>

                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-green-500"></div>
                                <h4 class="font-bold text-gray-700 mb-4">Validación de Pago</h4>
                                <div class="space-y-4">
                                    
                                    <div>
                                        <x-label value="Serie" />
                                        <select wire:model="voucherSeries" class="w-full border-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @if($availableSeries && $availableSeries->isNotEmpty())
                                                @foreach($availableSeries as $s)
                                                    <option value="{{ $s->series }}">
                                                        {{ $s->series }} ({{ ucfirst(str_replace('_', ' ', $s->voucher_type)) }})
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Sin series configuradas</option>
                                            @endif
                                        </select>
                                        <x-input-error for="voucherSeries" />
                                    </div>

                                    <div>
                                        <x-label value="Número" />
                                        <x-input type="number" wire:model="voucherNumber" class="w-full" placeholder="Ej. 7" />
                                        <x-input-error for="voucherNumber" />
                                    </div>
                                    <div>
                                        <x-label value="Notas" />
                                        <textarea wire:model="notes" class="w-full border-gray-300 rounded-md text-sm h-20 placeholder-gray-400"></textarea>
                                    </div>
                                </div>
                            </div>

                            <button wire:click="confirmEnrollment" 
                                    wire:loading.attr="disabled"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition transform hover:scale-[1.02] flex justify-center items-center">
                                <span wire:loading.remove>CONFIRMAR MATRÍCULA</span>
                                <span wire:loading>Procesando...</span>
                            </button>
                        </div>

                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Carga Académica Automática</h3>

                            @if($proposalRegular->isEmpty() && $proposalRecovery->isEmpty())
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r shadow-sm">
                                    <p class="text-sm text-yellow-700">
                                        <strong>¡Atención!</strong> El estudiante es apto, pero no se encontraron <strong>Secciones</strong> programadas para el Semestre {{ $nextSemester }} en el periodo {{ $activePeriod->code }}.
                                    </p>
                                </div>
                            @endif

                            @if($proposalRecovery->isNotEmpty())
                                <div class="mb-6 border border-red-200 bg-red-50 rounded-lg overflow-hidden">
                                    <div class="bg-red-100 px-4 py-2 border-b border-red-200 font-bold text-red-800">
                                        Cursos a Cargo (Recuperación)
                                    </div>
                                    <div class="p-4 space-y-3">
                                        @foreach($proposalRecovery as $assign)
                                            <div class="bg-white p-3 rounded border border-red-200 shadow-sm flex justify-between items-center">
                                                <div>
                                                    <div class="font-bold text-gray-800">{{ $assign->didacticUnit->name }}</div>
                                                    <div class="text-xs text-gray-500">Semestre {{ $assign->didacticUnit->semester }}</div>
                                                </div>
                                                <div class="text-right text-xs">
                                                    <div class="font-bold">{{ $assign->shift->name }} - {{ $assign->section }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($proposalRegular->isNotEmpty())
                                <div class="border border-gray-200 bg-gray-50 rounded-lg overflow-hidden">
                                    <div class="bg-gray-100 px-4 py-2 border-b border-gray-200 font-bold text-gray-700">
                                        Cursos Regulares (Semestre {{ $nextSemester }})
                                    </div>
                                    <div class="p-4 space-y-3">
                                        @foreach($proposalRegular as $assign)
                                            <div class="bg-white p-3 rounded border border-gray-200 shadow-sm flex justify-between items-center hover:border-indigo-300">
                                                <div>
                                                    <div class="font-bold text-gray-800">{{ $assign->didacticUnit->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $assign->didacticUnit->code }} | {{ $assign->didacticUnit->credits }} Créditos</div>
                                                </div>
                                                <div class="text-right text-xs">
                                                    <div class="font-bold text-blue-600">{{ $assign->shift->name }} - {{ $assign->section }}</div>
                                                    <div class="text-gray-500">{{ $assign->teacher->user->lastname ?? 'Por asignar' }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @script
    <script>
        Livewire.on('open-pdf', (event) => {
            const url = event.url || (event[0] ? event[0].url : null);
            
            if(url) {
                setTimeout(() => {
                    window.open(url, '_blank');
                }, 1000);
            }
        });
    </script>
    @endscript
</div>
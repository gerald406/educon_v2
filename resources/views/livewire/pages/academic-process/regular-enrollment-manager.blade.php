<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Matrícula de Estudiantes Regulares
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border relative">
                    <h3 class="text-md font-bold text-gray-700 mb-2">Buscar Estudiante Regular</h3>
                    <x-input type="text" class="w-full text-lg" wire:model.live.debounce.300ms="search" placeholder="Ingrese nombre o DNI..." />
                    
                    @if($searchResults->count() > 0)
                        <div class="absolute z-50 w-full bg-white border rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto left-0">
                            @foreach($searchResults as $student)
                                <div class="p-3 hover:bg-gray-100 cursor-pointer border-b" wire:click="selectStudent({{ $student->id }})">
                                    <div class="font-bold">{{ $student->user->name }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $student->code }} - Semestre Actual: {{ $student->current_semester }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($selectedStudent)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <div class="lg:col-span-2">
                            <div class="bg-blue-50 p-4 rounded-md border border-blue-100 mb-4">
                                <h4 class="font-bold text-blue-900 text-lg">{{ $selectedStudent->user->name }}</h4>
                                <p class="text-sm text-blue-800">
                                    Código: {{ $selectedStudent->code }} <span class="mx-2">|</span> 
                                    DNI: {{ $selectedStudent->user->document_number }}
                                </p>
                                <p class="text-sm text-blue-800 mt-1">
                                    Programa: <strong>{{ $selectedStudent->career->name }}</strong>
                                </p>
                            </div>

                            <h4 class="font-semibold text-gray-800 mb-3">Rendimiento del Semestre Anterior ({{ $selectedStudent->current_semester }})</h4>
                            
                            @if($lastSemesterRecords->count() > 0)
                                <div class="overflow-x-auto border rounded-md">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left">Unidad Didáctica</th>
                                                <th class="px-4 py-2 text-center">Créditos</th>
                                                <th class="px-4 py-2 text-center">Nota Final</th>
                                                <th class="px-4 py-2 text-center">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($lastSemesterRecords as $record)
                                                <tr>
                                                    <td class="px-4 py-2">{{ $record->didacticUnit->name }}</td>
                                                    <td class="px-4 py-2 text-center">{{ $record->didacticUnit->credits }}</td>
                                                    <td class="px-4 py-2 text-center font-bold">{{ number_format($record->final_grade, 0) }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $record->course_status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $record->course_status == 'approved' ? 'Aprobado' : 'Desaprobado' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 italic text-sm border p-4 rounded bg-gray-50 text-center">
                                    No se encontraron notas registradas del semestre {{ $selectedStudent->current_semester }}.
                                </p>
                            @endif
                        </div>

                        <div class="lg:col-span-1">
                            <div class="bg-white border rounded-lg shadow-sm p-6 sticky top-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Procesar Matrícula</h3>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Semestre a Matricular</label>
                                    <div class="text-xl font-bold text-indigo-700">
                                        {{ $nextSemester }}° Semestre
                                    </div>
                                    <p class="text-xs text-gray-500">Calculado automáticamente</p>
                                </div>

                                <div class="mb-6">
                                    <x-label for="voucher" value="Número de Voucher (Pago)" />
                                    <x-input id="voucher" type="text" class="w-full mt-1" wire:model="voucherNumber" placeholder="Ingrese código de recibo" />
                                    @error('voucherNumber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <x-label for="notes" value="Observaciones" />
                                    <textarea id="notes" wire:model="notes" class="w-full border-gray-300 rounded-md shadow-sm text-sm" rows="2"></textarea>
                                </div>

                                <button wire:click="processEnrollment" 
                                        class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded hover:bg-indigo-700 transition duration-150 flex justify-center items-center"
                                        wire:loading.attr="disabled">
                                    <svg wire:loading.remove class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span wire:loading.remove>CONFIRMAR MATRÍCULA</span>
                                    <span wire:loading>Procesando...</span>
                                </button>

                                <p class="text-xs text-center text-gray-500 mt-4">
                                    Al confirmar, se generará la Ficha de Matrícula automáticamente.
                                </p>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="text-center py-10 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p>Busque un estudiante para iniciar el proceso de matrícula regular.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-pdf', (event) => {
                const url = event.url || (Array.isArray(event) && event[0].url);
                if(url) window.open(url, '_blank');
            });
        });
    </script>
</div>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro Rápido de Notas (Admisión)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 text-center">
                
                <h3 class="text-lg font-medium text-gray-900 mb-6">Ingrese DNI del Postulante</h3>

                <form wire:submit.prevent="search" class="flex flex-col items-center gap-4">
                    
                    <div class="w-full max-w-md">
                        <input type="text" 
                               id="searchDniInput"
                               wire:model="searchDni" 
                               class="text-center text-3xl font-bold tracking-widest w-full border-2 border-blue-300 rounded-lg p-4 focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all"
                               placeholder="________" 
                               maxlength="8"
                               autofocus 
                               autocomplete="off"
                        />
                        @error('searchDni') 
                            <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> 
                        @enderror
                        
                        @if($notFoundMessage)
                            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-md border border-red-200 animate-pulse">
                                {{ $notFoundMessage }}
                            </div>
                        @endif
                    </div>

                    <x-button class="w-full max-w-md justify-center h-12 text-lg">
                        BUSCAR POSTULANTE (ENTER)
                    </x-button>
                </form>

                <p class="text-gray-400 text-sm mt-8">
                    Tip: Presione ENTER después de escribir el DNI. El sistema buscará y abrirá la ventana de calificación.
                </p>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="md">
        <x-slot name="title">
            Información del Participante
        </x-slot>

        <x-slot name="content">
            @if($applicant && $applicantUser)
                <div class="text-center mb-6">
                    
                    <div class="text-left space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 uppercase block">Nombre Completo:</span>
                            <div class="text-lg font-bold text-gray-900 border p-2 rounded bg-gray-50">
                                {{ $applicantUser->lastname }} {{ $applicantUser->name }}
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase block">DNI:</span>
                                <span class="font-mono font-semibold">{{ $applicantUser->document_number }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase block">Carrera:</span>
                                <span class="font-semibold text-blue-600 text-xs">
                                    {{ $applicant->admissionOffering->career->name ?? 'Sin Asignar' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    <form wire:submit.prevent="saveGrade">
                        <div class="text-left">
                            <label for="scoreInput" class="block text-sm font-medium text-gray-700 mb-1">
                                Nota (0-60):
                            </label>
                            <input type="number" 
                                   id="scoreInput"
                                   wire:model="examScore" 
                                   step="0.01" 
                                   min="0" 
                                   max="60" 
                                   class="w-full text-center text-4xl font-bold text-blue-700 border-2 border-blue-500 rounded-lg p-3 focus:ring-4 focus:ring-blue-200"
                                   placeholder="00.00"
                            />
                            @error('examScore') 
                                <span class="text-red-500 text-sm block mt-1 font-bold">{{ $message }}</span> 
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">
                                Ingrese el número de respuestas correctas (máximo: 60). La nota ponderada se calculará automáticamente.
                            </p>
                        </div>
                    </form>

                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" class="mr-2">
                Cancelar
            </x-secondary-button>

            <x-button wire:click="saveGrade" class="bg-blue-600 hover:bg-blue-700 text-white">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Guardar Nota (ENTER)
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <script>
        document.addEventListener('livewire:initialized', () => {
            
            // Al abrir el modal, poner foco en el input de nota
            Livewire.on('focus-score', () => {
                setTimeout(() => {
                    const scoreInput = document.getElementById('scoreInput');
                    if(scoreInput) {
                        scoreInput.focus();
                        scoreInput.select(); // Seleccionar texto existente si hay
                    }
                }, 300); // Pequeño retardo para esperar la animación del modal
            });

            // Al cerrar modal o buscar, devolver foco al DNI y limpiarlo
            Livewire.on('focus-search', () => {
                setTimeout(() => {
                    const searchInput = document.getElementById('searchDniInput');
                    if(searchInput) {
                        searchInput.value = ''; // Limpiar visualmente (el modelo ya se reseteó en PHP)
                        searchInput.focus();
                    }
                }, 100);
            });
        });
    </script>
</div>
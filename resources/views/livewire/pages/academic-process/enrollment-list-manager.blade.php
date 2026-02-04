<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Matrículas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex flex-col md:flex-row gap-4 mb-6 justify-between items-end">
                    <div class="w-full md:w-1/3">
                        <x-label value="Periodo Académico" />
                        <select wire:model.live="academic_period_id" class="w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} ({{ $p->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/2">
                        <x-label value="Buscar Estudiante" />
                        <x-input type="text" wire:model.live.debounce.300ms="search" class="w-full" placeholder="DNI, Nombre o Código..." />
                    </div>
                    <div>
                        <a href="{{ route('academic-process.regular-enrollment') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            + Nueva Matrícula
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carrera</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Semestre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detalle Pago</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($enrollments as $enrollment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $enrollment->student->user->lastname }}, {{ $enrollment->student->user->name }}</div>
                                        <div class="text-xs text-gray-500">Cód: {{ $enrollment->student->code }}</div>
                                        <div class="text-xs text-gray-400">{{ $enrollment->created_at->format('d/m/Y h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $enrollment->student->career->code }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $enrollment->semester_enrolled }}°
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="text-xs text-gray-500 truncate w-48" title="{{ $enrollment->notes }}">
                                            {{ Str::limit($enrollment->notes, 40) }}
                                        </div>
                                        <div class="font-mono text-xs text-green-600 font-bold">
                                            S/ {{ number_format($enrollment->amount_paid, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                        <button wire:click="printEnrollment({{ $enrollment->student_id }})" class="text-gray-600 hover:text-indigo-600" title="Imprimir Ficha">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </button>
                                        <button wire:click="editEnrollment({{ $enrollment->id }})" class="text-gray-600 hover:text-blue-600" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $enrollment->id }})" class="text-gray-600 hover:text-red-600" title="Anular y Liberar Voucher">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No se encontraron matrículas en este periodo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $enrollments->links() }}</div>

            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="isEditModalOpen">
        <x-slot name="title">Editar Matrícula</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-label value="Semestre Académico" />
                    <x-input type="number" wire:model="edit_semester" class="w-full" min="1" max="10" />
                    @error('edit_semester') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <x-label value="Notas / Observaciones" />
                    <textarea wire:model="edit_notes" class="w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div class="p-3 bg-yellow-50 text-yellow-800 text-xs rounded border border-yellow-200">
                    Nota: La edición aquí es administrativa. Para cambios de cursos (rectificación), utilice el módulo de Rectificación de Matrícula.
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isEditModalOpen', false)">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="updateEnrollment">Guardar Cambios</x-button>
        </x-slot>
    </x-dialog-modal>

    @script
    <script>
        Livewire.on('open-pdf', (event) => {
            const url = event.url || (event[0] ? event[0].url : null);
            if(url) window.open(url, '_blank');
        });

        // Script para SweetAlert de confirmación
        Livewire.on('swal:confirm', (data) => {
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Emitir evento de vuelta a Livewire con el ID
                    Livewire.dispatch(data.onConfirmed, { id: data.id });
                }
            });
        });
    </script>
    @endscript
</div>
<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Estado de Matrícula ({{ $activePeriod?->name }})</p>
                @if($currentEnrollment)
                    <p class="mt-1 text-2xl font-semibold text-green-600">MATRICULADO</p>
                @else
                    <p class="mt-1 text-2xl font-semibold text-red-600">NO MATRICULADO</p>
                @endif
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <a href="{{ route('enrollment.process') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Ir a matrícula &rarr;
                </a>
            </div>
        </div>

        <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Semestre</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $student?->current_semester ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Promedio Ponderado</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($student?->weighted_average ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
    
    @if($currentEnrollment)
        <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-medium text-gray-900 mb-4">
                    Mi Horario ({{ $activePeriod?->name }})
                </h1>
                <div class="space-y-2">
                    @forelse($schedules as $schedule)
                        @if($schedule)
                            <div class="text-sm p-2 bg-gray-100 rounded">
                                <strong>{{ ucfirst($schedule->day_of_week) }}</strong>
                                {{ $schedule->start_time->format('h:i A') }} - {{ $schedule->end_time->format('h:i A') }}
                                <span class="text-gray-600">
                                    ({{ $schedule->teacherAssignment->didacticUnit->name }})
                                </span>
                            </div>
                        @endif
                    @empty
                        <p class="text-sm text-gray-500">No se encontraron horarios para tu matrícula.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
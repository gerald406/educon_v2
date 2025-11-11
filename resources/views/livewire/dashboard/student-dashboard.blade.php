<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            </div>

        <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            </div>

        <div class_ ="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            </div>
    </div>
    
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-medium text-gray-900 mb-4">
                    Anuncios y Novedades
                </h1>
                <div class="space-y-4">
                    @forelse($announcements as $announcement)
                        <div class="p-4 border-b border-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $announcement->announcement_type == 'urgent' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($announcement->announcement_type) }}
                            </span>
                            <h4 class="font-semibold text-lg text-gray-900 mt-2">{{ $announcement->title }}</h4>
                            <p class="text-xs text-gray-500 mb-2">
                                Publicado el {{ $announcement->publish_date->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-700">
                                {!! nl2br(e($announcement->content)) !!}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500">No hay anuncios recientes.</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        @if($currentEnrollment)
            <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h1 class="text-xl font-medium text-gray-900 mb-4">
                        Mi Horario ({{ $activePeriod?->name }})
                    </h1>
                    <div class="space-y-2">
                        @forelse($schedules as $schedule)
                            @if($schedule)
                                <div class="text-sm p-2 bg-gray-100 rounded">
                                    <strong>{{ ucfirst($schedule->day_of_week) }}</strong>
                                    {{ $schedule->start_time->format('h:i A') }} - {{ $schedule->end_time->format('h:i A') }}
                                    <span class="text-gray-600 block text-xs">
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
</div>
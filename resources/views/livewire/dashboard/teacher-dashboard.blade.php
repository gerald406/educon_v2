<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Cursos Asignados ({{ $activePeriod?->name }})</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $assignments->count() }}</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Carga Horaria Semanal</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalHours }} <span class="text-xl">horas</span></p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Sílabos Pendientes/Observados</p>
                <p class="mt-1 text-3xl font-semibold {{ $pendingSyllabiCount > 0 ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $pendingSyllabiCount }}
                </p>
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <a href="{{ route('teacher.my-syllabi') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Gestionar sílabos &rarr;
                </a>
            </div>
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
        
        <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <h1 class="text-xl font-medium text-gray-900 mb-4">
                    Mi Carga Académica
                </h1>
                <div class="space-y-3">
                    @forelse($assignments as $assignment)
                        <div class="p-3 border rounded-md">
                            <strong class="text-gray-900">{{ $assignment->didacticUnit->name }}</strong>
                            <p class="text-sm text-gray-600">
                                Turno: {{ $assignment->shift->name }} (Sec. {{ $assignment->section }})
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500">No tiene cursos asignados.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-medium text-gray-900 mb-4">
                Mi Carga Académica ({{ $activePeriod?->name }})
            </h1>
            <div class="space-y-3">
                @forelse($assignments as $assignment)
                    <div class="p-4 border rounded-md">
                        <strong class="text-gray-900">{{ $assignment->didacticUnit->name }}</strong>
                        <p class="text-sm text-gray-600">
                            Turno: {{ $assignment->shift->name }} (Sección {{ $assignment->section }})
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">No tiene cursos asignados para el periodo activo.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
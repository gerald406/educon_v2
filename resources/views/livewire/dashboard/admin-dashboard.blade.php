<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Estudiantes Activos</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $studentCount }}</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Docentes Activos</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $teacherCount }}</p>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Programas Activos</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $careerCount }}</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <p class="text-sm font-medium text-gray-500 truncate">Ingresos de Caja (Hoy)</p>
                <p class="mt-1 text-3xl font-semibold text-green-600">S/ {{ number_format($todayPayments, 2) }}</p>
            </div>
            @can('registrar-pagos')
            <div class="bg-gray-50 px-6 py-3">
                <a href="{{ route('treasury.payments') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Ir a Caja &rarr;
                </a>
            </div>
            @endcan
        </div>

    </div>
    
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8">
                <h3 class="text-xl font-medium text-gray-900">
                    Periodo Activo: {{ $activePeriod?->name ?? 'Ninguno' }}
                </h3>
                <p class="mt-4 text-gray-500">
                    Métricas principales para el periodo académico actual.
                </p>
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Secciones Creadas</span>
                        <span class="font-semibold text-gray-900">{{ $assignmentCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Sílabos Pendientes</span>
                        <span class="font-semibold {{ $pendingSyllabi > 0 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $pendingSyllabi }}
                        </span>
                    </div>
                    @can('aprobar-silabos')
                        <a href="{{ route('academic-process.syllabus-approval') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Revisar sílabos &rarr;
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="md:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8">
                <h3 class="text-xl font-medium text-gray-900 mb-4">
                    Resumen de Estudiantes por Programa
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium">Programa de Estudio</th>
                                <th class="px-4 py-2 text-left text-xs font-medium">Nro. Estudiantes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($studentsPerCareer as $career)
                                <tr>
                                    <td class="px-4 py-3">{{ $career->name }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $career->students_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-center">No hay datos de carreras.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
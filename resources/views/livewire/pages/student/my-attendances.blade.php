<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi Récord de Asistencia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if ($currentEnrollment)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Mis Cursos ({{ $activePeriod->name }})
                                </h3>
                                <div class="space-y-2">
                                    @forelse($enrolledCourses as $registration)
                                        <button 
                                            wire:click="$set('selectedRegistrationId', {{ $registration->id }})" 
                                            @class([
                                                'w-full text-left p-3 rounded-md border',
                                                'bg-indigo-50 border-indigo-300' => $selectedRegistrationId == $registration->id,
                                                'hover:bg-gray-50' => $selectedRegistrationId != $registration->id,
                                            ])>
                                            <span class="font-semibold">{{ $registration->teacherAssignment->didacticUnit->name }}</span>
                                        </button>
                                    @empty
                                        <p class="text-sm text-gray-500">No estás inscrito en ningún curso este periodo.</p>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                @if($selectedRegistrationId)
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        Resumen de Asistencia
                                    </h3>
                                    
                                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                                        <div class="p-4 bg-green-50 rounded-lg border border-green-200 text-center">
                                            <span class_ ="text-2xl font-bold text-green-700">{{ $summary['present'] }}</span>
                                            <span class="block text-sm text-green-600">Presente</span>
                                        </div>
                                        <div class="p-4 bg-orange-50 rounded-lg border border-orange-200 text-center">
                                            <span class="text-2xl font-bold text-orange-700">{{ $summary['late'] }}</span>
                                            <span class="block text-sm text-orange-600">Tardanzas</span>
                                        </div>
                                        <div class="p-4 bg-red-50 rounded-lg border border-red-200 text-center">
                                            <span class="text-2xl font-bold text-red-700">{{ $summary['absent'] }}</span>
                                            <span class="block text-sm text-red-600">Ausencias</span>
                                        </div>
                                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
                                            <span class="text-2xl font-bold text-blue-700">{{ $summary['justified'] }}</span>
                                            <span class="block text-sm text-blue-600">Justificado</span>
                                        </div>
                                        <div @class([
                                            'p-4 rounded-lg border',
                                            'bg-green-50 border-green-200' => $summary['percentage'] >= 70,
                                            'bg-red-50 border-red-200' => $summary['percentage'] < 70,
                                        ])>
                                            <p class="text-center">
                                                <span class="text-2xl font-bold {{ $summary['percentage'] < 70 ? 'text-red-700' : 'text-green-700' }}">
                                                    {{ number_format($summary['percentage'], 1) }}%
                                                </span>
                                                <span class="block text-sm {{ $summary['percentage'] < 70 ? 'text-red-600' : 'text-green-600' }}">
                                                    Asistencia Total
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detalle por Fecha</h3>
                                    <div class="overflow-x-auto border rounded-md max-h-96 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50 sticky top-0">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium">Fecha</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse($attendances as $attendance)
                                                    <tr>
                                                        <td class="px-6 py-4">{{ $attendance->class_date->format('d/m/Y') }}</td>
                                                        <td @class([
                                                            'px-6 py-4 font-semibold',
                                                            'text-green-600' => $attendance->attendance_type == 'present',
                                                            'text-red-600' => $attendance->attendance_type == 'absent',
                                                            'text-orange-600' => $attendance->attendance_type == 'late',
                                                            'text-blue-600' => $attendance->attendance_type == 'justified',
                                                        ])>
                                                            {{ ucfirst($attendance->attendance_type) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="px-6 py-4 text-center">Aún no se han registrado asistencias para este curso.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 p-10">Selecciona un curso de la izquierda para ver tu récord de asistencia.</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-center text-red-500">No se encontró una matrícula activa para este periodo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
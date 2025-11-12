<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\ClassroomResource;
use App\Models\DidacticUnit;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener el Año Académico "2025" (creado en DatabaseSeeder)
        $year2025 = AcademicYear::where('year', 2025)->first();
        if (!$year2025) {
            $this->command->error('No se encontró el Año Académico 2025.');
            return;
        }

        // 2. Crear el Periodo Académico "2025-I" y ponerlo "activo"
        $period2025_I = AcademicPeriod::firstOrCreate(
            ['academic_year_id' => $year2025->id, 'code' => '2025-I'],
            [
                'institution_id' => $year2025->institution_id,
                'name' => 'Periodo Académico 2025-I',
                'start_date' => '2025-03-01',
                'end_date' => '2025-07-31',
                'enrollment_start_date' => '2025-02-15',
                'enrollment_end_date' => '2025-03-10',
                'classes_start_date' => '2025-03-15',
                'classes_end_date' => '2025-07-15',
                // [NUEVAS LÍNEAS]
                'grade_entry_start_date' => '2025-07-01 08:00:00', // Inicio de registro de notas
                'grade_entry_end_date' => '2025-07-15 23:59:59', // Fin de registro
                'status' => 'active', // ¡Importante! Este es el periodo activo
                
            ]
        );

        // 3. Obtener recursos para la asignación
        $teachers = Teacher::where('status', 'active')->get();
        $shiftMorning = Shift::where('name', 'Turno Mañana')->first();
        $classroom = ClassroomResource::where('status', 'available')->first();
        
        // 4. Obtener cursos de APSTI (Semestre I)
        $unit_mei = DidacticUnit::where('code', 'MEI-I')->first(); // Mantenimiento
        $unit_iso = DidacticUnit::where('code', 'ISO-I')->first(); // Sist. Op.
        $unit_ce = DidacticUnit::where('code', 'CE-I')->first();  // Com. Efectiva

        if ($teachers->isEmpty() || !$shiftMorning || !$classroom || !$unit_mei) {
            $this->command->warn('No hay suficientes datos (docentes, turnos, aulas) para crear la carga académica.');
            return;
        }

        // 5. Crear Asignaciones (Carga Académica)
        
        // Asignación 1: Mantenimiento
        $assign1 = TeacherAssignment::firstOrCreate(
            [
                'teacher_id' => $teachers->get(0)->id,
                'didactic_unit_id' => $unit_mei->id,
                'academic_period_id' => $period2025_I->id,
                'section' => 'A',
            ],
            [
                'shift_id' => $shiftMorning->id,
                'max_capacity' => 30,
            ]
        );

        // Asignación 2: Sistemas Operativos
        $assign2 = TeacherAssignment::firstOrCreate(
            [
                'teacher_id' => $teachers->get(1)->id,
                'didactic_unit_id' => $unit_iso->id,
                'academic_period_id' => $period2025_I->id,
                'section' => 'A',
            ],
            [
                'shift_id' => $shiftMorning->id,
                'max_capacity' => 30,
            ]
        );

        // 6. Crear Horarios para esas asignaciones
        
        // Horario 1 (Lunes para Mantenimiento)
        Schedule::firstOrCreate([
            'teacher_assignment_id' => $assign1->id,
            'day_of_week' => 'monday',
        ], [
            'classroom_resource_id' => $classroom->id,
            'start_time' => '08:00:00',
            'end_time' => '10:15:00',
        ]);
        // Horario 2 (Miércoles para Mantenimiento)
        Schedule::firstOrCreate([
            'teacher_assignment_id' => $assign1->id,
            'day_of_week' => 'wednesday',
        ], [
            'classroom_resource_id' => $classroom->id,
            'start_time' => '08:00:00',
            'end_time' => '10:15:00',
        ]);

        // Horario 3 (Martes para Sist. Op.)
        Schedule::firstOrCreate([
            'teacher_assignment_id' => $assign2->id,
            'day_of_week' => 'tuesday',
        ], [
            'classroom_resource_id' => $classroom->id,
            'start_time' => '10:30:00',
            'end_time' => '12:45:00',
        ]);

        $this->command->info('Procesos académicos (Periodo 2025-I) creados exitosamente.');
    
    }
}

<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Registration;
use App\Models\Student;
use App\Models\TeacherAssignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener el Periodo Activo
        $activePeriod = AcademicPeriod::where('status', 'active')->first();
        if (!$activePeriod) {
            $this->command->error('No se encontró un periodo académico activo para la matrícula.');
            return;
        }

        // 2. Obtener las Secciones (Carga Académica) de SEMESTRE 1
        $assignments = TeacherAssignment::where('academic_period_id', $activePeriod->id)
            ->whereHas('didacticUnit', function ($query) {
                $query->where('semester', 1); // <-- FORZAR A SÓLO CURSOS DE SEMESTRE 1
            })
            ->get();
            
        if ($assignments->isEmpty()) {
            $this->command->warn('No hay carga académica (secciones) de Semestre 1 en el periodo activo. Omitiendo seeder de matrícula.');
            return;
        }

        // 3. Obtener Estudiantes (ej. los primeros 20)
        $students = Student::take(20)->get();

        DB::transaction(function () use ($activePeriod, $students, $assignments) {
            foreach ($students as $student) {
                // 4. Crear la Matrícula (Enrollment)
                $enrollment = Enrollment::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'academic_period_id' => $activePeriod->id,
                    ],
                    [
                        'semester_enrolled' => 1, // <-- FORZARLOS A SEMESTRE 1
                        'enrollment_type' => 'continuing',
                        'payment_status' => 'paid',
                        'status' => 'active',
                    ]
                );

                // 5. Inscribir al estudiante en TODOS los cursos de Semestre 1
                foreach ($assignments as $assignment) {
                    Registration::firstOrCreate(
                        [
                            'enrollment_id' => $enrollment->id,
                            'teacher_assignment_id' => $assignment->id,
                        ],
                        [
                            'registration_type' => 'mandatory',
                            'status' => 'enrolled',
                        ]
                    );
                    
                    // Actualizar el contador de matriculados en la sección
                    $assignment->increment('current_enrolled');
                }
            }
        });

        $this->command->info('Matrículas e inscripciones (Semestre 1) creadas exitosamente.');
    }
}

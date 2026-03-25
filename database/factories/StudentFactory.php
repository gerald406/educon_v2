<?php

namespace Database\Factories;

use App\Models\Career;
use App\Models\StudyPlan;
use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        // BUG-010 fix: usar factories dinámicos en vez de buscar por código hardcoded.
        // Career::where('code', 'APSTI') fallaba en bases de datos vacías (tests).
        return [
            'user_id'         => User::factory(),
            'career_id'       => Career::factory(),
            'study_plan_id'   => StudyPlan::factory(),
            'code'            => $this->faker->unique()->numerify('E' . date('Y') . '-#####'),
            'current_semester' => $this->faker->numberBetween(1, 6),
            'academic_status' => 'regular',
            'admission_date'  => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Student $student) {
            // Crear el rol si no existe para evitar RoleDoesNotExist en tests
            Role::firstOrCreate(['name' => 'Estudiante', 'guard_name' => 'web']);
            $student->user->assignRole('Estudiante');
        });
    }
}

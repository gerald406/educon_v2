<?php

namespace Database\Factories;

use App\Models\Career;
use App\Models\StudyPlan;
use App\Models\User;
use App\Models\Student; // <-- [NUEVO] Importar Student
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class; // <-- [NUEVO] Especificar el modelo

    public function definition(): array
    {
        $career = Career::where('code', 'APSTI')->first();
        $studyPlan = StudyPlan::where('code', 'APSTI-2021')->first();

        return [
            'user_id' => User::factory(), // <-- [MODIFICADO]
            
            'applicant_id' => null,
            'career_id' => $career->id,
            'study_plan_id' => $studyPlan->id,
            'code' => $this->faker->unique()->numerify('E' . date('Y') . '-#####'),
            'current_semester' => $this->faker->numberBetween(1, 6),
            'academic_status' => 'regular',
            'admission_date' => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
        ];
    }
    
    /**
     * [NUEVO] Hook para asignar el rol después de crear el estudiante.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Student $student) {
            $student->user->assignRole('Estudiante');
        });
    }
}
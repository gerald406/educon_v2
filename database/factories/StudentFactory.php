<?php

namespace Database\Factories;

use App\Models\Career;
use App\Models\StudyPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Busca nuestra carrera "APSTI"
        $career = Career::where('code', 'APSTI')->first();
        // Busca el plan "APSTI-2021"
        $studyPlan = StudyPlan::where('code', 'APSTI-2021')->first();

        return [
            // Crea un nuevo Usuario con el tipo 'student'
            'user_id' => User::factory()->create(['user_type' => 'student']),
            
            'applicant_id' => null,
            'career_id' => $career->id,
            'study_plan_id' => $studyPlan->id,
            'code' => $this->faker->unique()->numerify('E' . date('Y') . '-#####'),
            'current_semester' => $this->faker->numberBetween(1, 6),
            'academic_status' => 'regular',
            'admission_date' => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
        ];
    }
}

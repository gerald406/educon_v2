<?php

namespace Database\Factories;

use App\Models\Applicant;
use App\Models\Career;
use App\Models\StudyPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Applicant>
 */
class ApplicantFactory extends Factory
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
        $studyPlan = StudyPlan::where('code', 'APSTI-2021')->first();
        
        return [
            'user_id' => User::factory()->create(), // Crea un usuario base
            'career_id' => $career->id,
            'study_plan_id' => $studyPlan->id,
            'code' => $this->faker->unique()->numerify('P' . date('Y') . '-#####'),
            'admission_type' => 'regular',
            'exam_score' => $this->faker->optional(0.7)->randomFloat(2, 5, 20), // 70% chance de tener nota
            'application_status' => 'registered',
        ];
    }
    
    /**
     * Hook para asignar el rol de Postulante.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Applicant $applicant) {
            $applicant->user->assignRole('Estudiante'); // Temporalmente le damos rol estudiante para que pueda loguearse si quisiera
        });
    }
    
}

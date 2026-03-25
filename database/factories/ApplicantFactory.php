<?php

namespace Database\Factories;

use App\Models\Applicant;
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
        return [
            'user_id' => User::factory(),
            'gender' => $this->faker->randomElement(['masculino', 'femenino']),
            'birthday' => $this->faker->date('Y-m-d', '-18 years'),
            'code' => $this->faker->unique()->numerify('P' . date('Y') . '-#####'),
            'application_status' => 'registrado',
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

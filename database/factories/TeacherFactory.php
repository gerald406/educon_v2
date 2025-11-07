<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Crea un nuevo Usuario con el tipo 'teacher' y obtiene su ID
            'user_id' => User::factory()->create(['user_type' => 'teacher']),
            
            // Asigna a la primera institución
            'institution_id' => Institution::first()->id,
            
            'code' => $this->faker->unique()->bothify('T-#####'),
            'academic_degree' => $this->faker->randomElement(['Lic.', 'Mag.', 'Dr.']),
            'specialty' => $this->faker->jobTitle(),
            'contract_type' => $this->faker->randomElement(['permanent', 'contracted']),
            'hire_date' => $this->faker->date(),
            'status' => 'active',
        ];
    }
}

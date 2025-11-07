<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Career>
 */
class CareerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution_id' => Institution::first() ?? Institution::factory(),
            'code' => $this->faker->unique()->lexify('???-???'),
            'name' => 'Programa de ' . $this->faker->jobTitle(),
            'duration_semesters' => 6,
            'degree_awarded' => 'Profesional Técnico en ' . $this->faker->jobTitle(),
            'status' => 'active',
        ];
    }
}

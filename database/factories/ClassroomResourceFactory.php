<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassroomResource>
 */
class ClassroomResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isLab = $this->faker->boolean();
        return [
            'classroom_code' => $this->faker->randomElement(['A', 'B', 'C', 'LAB']) . '-' . $this->faker->unique()->numberBetween(101, 305),
            'name' => $isLab ? 'Laboratorio de Cómputo' : 'Aula Común',
            'building' => 'Pabellón ' . $this->faker->randomElement(['A', 'B', 'C']),
            'floor' => $this->faker->randomElement(['1', '2', '3']),
            'capacity' => $this->faker->randomElement([30, 40, 50]),
            'has_projector' => $this->faker->boolean(80),
            'has_computers' => $isLab,
            'computer_count' => $isLab ? 30 : 0,
            'has_air_conditioning' => $this->faker->boolean(20),
            'status' => 'available',
        ];
    }
}

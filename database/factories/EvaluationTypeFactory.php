<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EvaluationType>
 */
class EvaluationTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // BUG-011 fix: factory estaba vacío, causando violación NOT NULL en tests
    public function definition(): array
    {
        return [
            'name'             => fake()->randomElement(['Examen Parcial', 'Examen Final', 'Práctica', 'Tarea', 'Participación']),
            'weight_percentage' => fake()->randomFloat(2, 10, 40),
            'is_droppable'     => fake()->boolean(),
            'sort_order'       => fake()->numberBetween(1, 10),
            'status'           => 'active',
        ];
    }
}

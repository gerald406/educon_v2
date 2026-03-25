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
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'weight_percentage' => $this->faker->randomFloat(2, 5, 30),
            'is_droppable' => $this->faker->boolean(),
            'sort_order' => $this->faker->numberBetween(1, 10),
            'status' => 'active',
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DidacticUnit>
 */
class DidacticUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'module_id' => Module::factory(),
            'code' => $this->faker->unique()->lexify('???-###'),
            'name' => 'Curso de ' . $this->faker->word(),
            'semester' => $this->faker->numberBetween(1, 6),
            'weekly_hours' => $this->faker->randomElement([4, 6, 8]),
            'total_hours' => $this->faker->randomElement([64, 96, 128]),
            'credits' => $this->faker->randomElement([3, 4, 5]),
            'unit_type' => $this->faker->randomElement(['career', 'transversal']),
            'semester_order' => $this->faker->numberBetween(1, 8),
            'status' => 'active',
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
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
            'name'       => fake()->randomElement(['Mañana', 'Tarde', 'Noche']) . ' ' . fake()->numberBetween(1, 99),
            'start_time' => fake()->randomElement(['07:00:00', '13:00:00', '18:00:00']),
            'end_time'   => fake()->randomElement(['12:00:00', '18:00:00', '22:00:00']),
            'status'     => 'active',
        ];
    }
}

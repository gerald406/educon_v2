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
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Mañana', 'Tarde', 'Noche']) . '-' . $this->faker->unique()->numerify('##'),
            'description' => $this->faker->sentence(),
            'start_time' => $this->faker->time('H:i:s', '12:00:00'),
            'end_time' => $this->faker->time('H:i:s', '22:00:00'),
            'status' => 'active',
        ];
    }
}

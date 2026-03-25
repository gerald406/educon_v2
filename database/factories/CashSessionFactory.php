<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashSession>
 */
class CashSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'opening_time' => now()->subHours(2),
            'opening_balance' => $this->faker->randomFloat(2, 100, 500),
            'status' => 'open',
        ];
    }
}

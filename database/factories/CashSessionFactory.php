<?php

namespace Database\Factories;

use App\Models\CashSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashSession>
 */
class CashSessionFactory extends Factory
{
    protected $model = CashSession::class;

    public function definition(): array
    {
        return [
            'user_id'         => User::factory(),
            'opening_time'    => now(),
            'opening_balance' => 0.00,
            'status'          => 'open',
            'notes'           => null,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\CashSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cash_session_id' => CashSession::factory(),
            'issuer_id' => User::factory(),
            'client_id' => User::factory(),
            'voucher_type' => $this->faker->randomElement(['boleta', 'factura', 'recibo']),
            'series' => 'B' . $this->faker->numerify('###'),
            'number' => $this->faker->unique()->numberBetween(1, 99999),
            'total_amount' => $this->faker->randomFloat(2, 50, 2000),
            'payment_method' => $this->faker->randomElement(['Efectivo', 'Yape/Plin', 'Tarjeta']),
            'status' => 'issued',
            'issued_at' => now(),
        ];
    }
}

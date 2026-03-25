<?php

namespace Database\Factories;

use App\Models\CashSession;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        return [
            'cash_session_id' => CashSession::factory(),
            'issuer_id'       => User::factory(),
            'client_id'       => User::factory(),
            'voucher_type'    => fake()->randomElement(['receipt', 'invoice']),
            'series'          => 'B001',
            'number'          => fake()->unique()->numerify('######'),
            'total_amount'    => fake()->randomFloat(2, 10, 500),
            'payment_method'  => fake()->randomElement(['cash', 'transfer', 'yape']),
            'transaction_code' => null,
            'observations'    => null,
            'status'          => 'issued',
            'issued_at'       => now(),
        ];
    }
}

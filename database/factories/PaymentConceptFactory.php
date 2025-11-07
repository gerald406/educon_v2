<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentConcept>
 */
class PaymentConceptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('???-###'),
            'tupa_code' => $this->faker->optional()->numberBetween(10, 50) . '.' . $this->faker->numberBetween(1, 99),
            'description' => $this->faker->randomElement(['Certificado de Estudios', 'Examen de Subsanación']),
            'amount' => $this->faker->randomElement([50.00, 35.50, 25.00]),
            'concept_type' => $this->faker->randomElement(['certificate', 'fee']),
            'is_taxable' => false,
            'tax_rate' => 0.00,
            'is_mandatory' => false,
            'discount_applicable' => false,
            'status' => 'active',
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Institution>
 */
class InstitutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'IESTP-' . $this->faker->unique()->numberBetween(100, 999),
            'name' => 'Instituto de Educación Superior Tecnológico Público ' . $this->faker->city,
            'tax_id' => '20' . $this->faker->unique()->numerify('#########'),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => 'info@' . $this->faker->unique()->domainName,
            'website' => 'https://www.' . $this->faker->domainName,
            'status' => 'active',
        ];
    }
}

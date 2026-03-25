<?php

namespace Database\Factories;

use App\Models\AdmissionModality;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdmissionModalityFactory extends Factory
{
    protected $model = AdmissionModality::class;

    public function definition(): array
    {
        return [
            'name'      => $this->faker->unique()->words(3, true),
            'type'      => $this->faker->randomElement(['ordinario', 'extraordinario']),
            'is_active' => true,
        ];
    }
}

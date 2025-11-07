<?php

namespace Database\Factories;

use App\Models\StudyPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'study_plan_id' => StudyPlan::factory(),
            'module_number' => $this->faker->unique()->numberBetween(1, 6),
            'name' => 'Módulo ' . $this->faker->word(),
            'minimum_credits_approval' => 20,
            'total_hours' => 400,
            'sort_order' => $this->faker->numberBetween(1, 6),
            'status' => 'active',
        ];
    }
}

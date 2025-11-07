<?php

namespace Database\Factories;

use App\Models\Career;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyPlan>
 */
class StudyPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = $this->faker->year();
        return [
            'career_id' => Career::factory(),
            'code' => $this->faker->lexify('???') . '-' . $year,
            'name' => 'Plan de Estudios ' . $year,
            'version' => 'V' . $year,
            'start_date' => $year . '-01-01',
            'total_credits' => 120,
            'total_hours' => 2800,
            'status' => 'active',
        ];
    }
}

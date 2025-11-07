<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = $this->faker->unique()->year();
        return [
            'institution_id' => Institution::factory(), 
            'year' => $year,
            'name' => 'Año Académico ' . $year,
            'start_date' => $year . '-01-01',
            'end_date' => $year . '-12-31',
            'status' => 'planned',
        ];
    }
}

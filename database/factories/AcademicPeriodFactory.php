<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcademicPeriod>
 */
class AcademicPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = AcademicYear::first() ?? AcademicYear::factory()->create();
        
        return [
            'institution_id' => $year->institution_id,
            'academic_year_id' => $year->id,
            'code' => $year->year . '-I',
            'name' => 'Periodo Académico ' . $year->year . '-I',
            'start_date' => $year->year . '-03-01',
            'end_date' => $year->year . '-07-31',
            'enrollment_start_date' => $year->year . '-02-15',
            'enrollment_end_date' => $year->year . '-03-10',
            'classes_start_date' => $year->year . '-03-15',
            'classes_end_date' => $year->year . '-07-15',
            'status' => 'planned',
        ];
    }
}

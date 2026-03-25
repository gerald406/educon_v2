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
        // BUG-010 fix: usar siempre AcademicYear::factory() para evitar
        // la constraint única (institution_id, code) cuando se crean múltiples períodos.
        $academicYear = AcademicYear::factory()->create();
        $suffix       = fake()->unique()->randomElement(['I', 'II']) . '-' . fake()->unique()->numerify('##');

        return [
            'institution_id'        => $academicYear->institution_id,
            'academic_year_id'      => $academicYear->id,
            'code'                  => $academicYear->year . '-' . $suffix,
            'name'                  => 'Periodo Académico ' . $academicYear->year . '-' . $suffix,
            'start_date'            => $academicYear->year . '-03-01',
            'end_date'              => $academicYear->year . '-07-31',
            'enrollment_start_date' => $academicYear->year . '-02-15',
            'enrollment_end_date'   => $academicYear->year . '-03-10',
            'classes_start_date'    => $academicYear->year . '-03-15',
            'classes_end_date'      => $academicYear->year . '-07-15',
            'status'                => 'planned',
        ];
    }
}

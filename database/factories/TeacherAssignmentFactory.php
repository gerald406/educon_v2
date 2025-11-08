<?php

namespace Database\Factories;

use App\Models\AcademicPeriod;
use App\Models\DidacticUnit;
use App\Models\Shift;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeacherAssignment>
 */
class TeacherAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'didactic_unit_id' => DidacticUnit::factory(),
            'academic_period_id' => AcademicPeriod::factory(),
            'shift_id' => Shift::factory(),
            'section' => 'A',
            'max_capacity' => 30,
            'current_enrolled' => 0,
            'status' => 'active',
        ];
    }
}

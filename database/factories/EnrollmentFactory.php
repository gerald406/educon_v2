<?php

namespace Database\Factories;

use App\Models\AcademicPeriod;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'academic_period_id' => AcademicPeriod::factory(),
            'enrollment_date' => now(),
            'semester_enrolled' => 1,
            'enrollment_type' => 'continuing',
            'payment_status' => 'paid',
            'status' => 'active',
        ];
    }
}

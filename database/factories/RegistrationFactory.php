<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\TeacherAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'teacher_assignment_id' => TeacherAssignment::factory(),
            'registration_date' => now(),
            'registration_type' => 'mandatory',
            'status' => 'enrolled',
        ];
    }
}

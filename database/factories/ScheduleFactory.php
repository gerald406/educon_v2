<?php

namespace Database\Factories;

use App\Models\ClassroomResource;
use App\Models\TeacherAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_assignment_id' => TeacherAssignment::factory(),
            'classroom_resource_id' => ClassroomResource::factory(),
            'day_of_week' => $this->faker->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
        ];
    }
}

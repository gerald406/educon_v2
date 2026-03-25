<?php

namespace Database\Factories;

use App\Models\ExamClassroom;
use App\Models\ExamPavilion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamClassroomFactory extends Factory
{
    protected $model = ExamClassroom::class;

    public function definition(): array
    {
        return [
            'exam_pavilion_id' => ExamPavilion::factory(),
            'room_number'      => $this->faker->unique()->numerify('Aula-###'),
            'capacity'         => $this->faker->numberBetween(20, 40),
            'description'      => null,
            'is_active'        => true,
        ];
    }
}

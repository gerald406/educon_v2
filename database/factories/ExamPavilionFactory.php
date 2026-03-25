<?php

namespace Database\Factories;

use App\Models\ExamPavilion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamPavilionFactory extends Factory
{
    protected $model = ExamPavilion::class;

    public function definition(): array
    {
        return [
            'name'      => 'Pabellón ' . $this->faker->unique()->randomLetter() . $this->faker->numerify('##'),
            'location'  => $this->faker->sentence(3),
            'is_active' => true,
        ];
    }
}

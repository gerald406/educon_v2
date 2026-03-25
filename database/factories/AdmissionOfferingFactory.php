<?php

namespace Database\Factories;

use App\Models\AdmissionOffering;
use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdmissionOfferingFactory extends Factory
{
    protected $model = AdmissionOffering::class;

    public function definition(): array
    {
        return [
            'academic_period_id' => AcademicPeriod::factory(),
            'career_id'          => Career::factory(),
            'shift_id'           => Shift::factory(),
            'vacancies'          => $this->faker->numberBetween(10, 50),
            'is_active'          => true,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\User;
use App\Models\Teacher; // <-- [NUEVO] Importar Teacher
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class; // <-- [NUEVO] Especificar el modelo

    public function definition(): array
    {
        return [
            // [MODIFICADO] Ya no creamos el user aquí, lo hacemos en el hook de abajo
            'user_id' => User::factory(), 
            
            'institution_id' => Institution::first()->id,
            
            'code' => $this->faker->unique()->bothify('T-#####'),
            'academic_degree' => $this->faker->randomElement(['Lic.', 'Mag.', 'Dr.']),
            'specialty' => $this->faker->jobTitle(),
            'contract_type' => $this->faker->randomElement(['permanent', 'contracted']),
            'hire_date' => $this->faker->date(),
            'status' => 'active',
        ];
    }

    /**
     * [NUEVO] Hook para asignar el rol después de crear el docente.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Teacher $teacher) {
            $teacher->user->assignRole('Docente');
        });
    }
}
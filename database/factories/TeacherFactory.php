<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        // BUG-010 fix: usar Institution::factory() en vez de Institution::first()->id
        // que fallaba con "Attempt to read property 'id' on null" en BD vacía (tests).
        return [
            'user_id'        => User::factory(),
            'institution_id' => Institution::factory(),
            'code'           => $this->faker->unique()->bothify('T-#####'),
            'academic_degree' => $this->faker->randomElement(['Lic.', 'Mag.', 'Dr.']),
            'specialty'      => $this->faker->jobTitle(),
            'contract_type'  => $this->faker->randomElement(['permanent', 'contracted']),
            'hire_date'      => $this->faker->date(),
            'status'         => 'active',
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Teacher $teacher) {
            // Crear el rol si no existe para evitar RoleDoesNotExist en tests
            Role::firstOrCreate(['name' => 'Docente', 'guard_name' => 'web']);
            $teacher->user->assignRole('Docente');
        });
    }
}

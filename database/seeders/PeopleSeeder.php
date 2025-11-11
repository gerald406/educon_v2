<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 10 Docentes de ejemplo
        Teacher::factory(10)->create();
        
        // Crear 50 Estudiantes de ejemplo
        Student::factory(50)->create();

        // [NUEVO] Crear 15 Postulantes de ejemplo
        Applicant::factory(15)->create();
    }
}

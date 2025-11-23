<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmissionModalitySeeder extends Seeder
{
    public function run(): void
    {
        $modalities = [
            // Tipo Ordinario
            ['name' => 'Examen Ordinario', 'type' => 'ordinario'],

            // Tipo Extraordinario
            ['name' => 'CEPRE JAE', 'type' => 'extraordinario'],
            ['name' => 'Primeros Puestos', 'type' => 'extraordinario'],
            ['name' => 'Deportistas Calificados', 'type' => 'extraordinario'],
            ['name' => 'Ley N° 29248 Servicio Militar Obligatorio', 'type' => 'extraordinario'],
            ['name' => 'Ley N° 29973 y 29643 Persona con Discapacidad', 'type' => 'extraordinario'],
            ['name' => 'Ley N° 28592 P.I.R. (Plan Integral de Reparaciones)', 'type' => 'extraordinario'],
            ['name' => 'Ley N° 28131 Artistas Calificados', 'type' => 'extraordinario'],
            ['name' => 'Ley N° 30490 y 29600 Persona Adulta Mayor y Reinserción Escolar', 'type' => 'extraordinario'],
        ];

        foreach ($modalities as $modality) {
            DB::table('admission_modalities')->insert([
                'name' => $modality['name'],
                'type' => $modality['type'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

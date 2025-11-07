<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear la Institución Principal
        $institution = Institution::firstOrCreate(
            ['tax_id' => '20123456789'], // RUC
            [
                'code' => 'IESTP001',
                'name' => 'IESTP Educon (Sede Principal)',
                'address' => 'Av. Principal 123, Lima',
                'status' => 'active',
            ]
        );

        // 2. Crear Años Académicos
        AcademicYear::firstOrCreate(
            ['institution_id' => $institution->id, 'year' => 2024],
            ['name' => 'Año Académico 2024', 'start_date' => '2024-01-01', 'end_date' => '2024-12-31', 'status' => 'closed']
        );
        AcademicYear::firstOrCreate(
            ['institution_id' => $institution->id, 'year' => 2025],
            ['name' => 'Año Académico 2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'status' => 'active']
        );

        // 3. Crear el usuario Administrador
        // Modificamos el usuario que Jetstream crea por defecto
        $adminUser = User::firstOrCreate(
            ['email' => 'gcauna@admin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('gcauna@admin.com'),
            ]
        );


        // 4. Llamar a los Seeders
        $this->call([
            CatalogSeeder::class,
            AcademicStructureSeeder::class, // <-- AÑADE ESTA LÍNEA
        ]);
    }
    
}

<?php

namespace Database\Seeders;

use App\Models\ClassroomResource;
use App\Models\EvaluationType;
use App\Models\PaymentConcept;
use App\Models\Shift;
use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Configuración del Sistema
        SystemSetting::firstOrCreate(
            ['key_name' => 'minimum_passing_grade'],
            ['value' => '13', 'description' => 'Nota mínima aprobatoria.', 'data_type' => 'integer', 'module' => 'grades']
        );
        SystemSetting::firstOrCreate(
            ['key_name' => 'minimum_attendance_percentage'],
            ['value' => '70', 'description' => 'Porcentaje mínimo de asistencia.', 'data_type' => 'integer', 'module' => 'attendance']
        );

        // 2. Turnos
        Shift::firstOrCreate(['name' => 'Turno Mañana'], ['start_time' => '08:00:00', 'end_time' => '12:30:00']);
        Shift::firstOrCreate(['name' => 'Turno Tarde'], ['start_time' => '13:30:00', 'end_time' => '18:00:00']);
        Shift::firstOrCreate(['name' => 'Turno Noche'], ['start_time' => '18:30:00', 'end_time' => '22:30:00']);

        // 3. Tipos de Evaluación
        EvaluationType::firstOrCreate(['name' => 'Evaluación Parcial'], ['weight_percentage' => 30.00, 'sort_order' => 1]);
        EvaluationType::firstOrCreate(['name' => 'Evaluación Final'], ['weight_percentage' => 40.00, 'sort_order' => 3]);
        EvaluationType::firstOrCreate(['name' => 'Evaluación Continua'], ['weight_percentage' => 30.00, 'sort_order' => 2]);

        // 4. Conceptos de Pago (Ejemplos Fijos)
        PaymentConcept::firstOrCreate(
            ['code' => 'MAT-REG'],
            ['description' => 'Matrícula Regular', 'amount' => 120.00, 'concept_type' => 'enrollment', 'is_mandatory' => true]
        );
        PaymentConcept::firstOrCreate(
            ['code' => 'CERT-MOD'],
            ['description' => 'Certificado Modular', 'amount' => 50.00, 'concept_type' => 'certificate', 'is_mandatory' => false]
        );

        // 5. Datos Falsos
        if (ClassroomResource::count() == 0) {
            ClassroomResource::factory(10)->create();
        }
        if (PaymentConcept::count() < 5) {
            PaymentConcept::factory(5)->create(); // Crea 5 conceptos falsos más
        }
    }
}

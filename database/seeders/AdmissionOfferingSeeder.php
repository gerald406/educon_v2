<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmissionOfferingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener el periodo activo (o crear uno si no existe para pruebas)
        $period = AcademicPeriod::where('code', '2025-I')->first();
        if (!$period) {
            // Fallback por si no has corrido los seeders anteriores
            return;
        }

        // 2. Obtener Carreras y Turnos
        $careers = Career::all();
        $shiftDay = Shift::where('name', 'like', '%Mañana%')->first();
        $shiftNight = Shift::where('name', 'like', '%Noche%')->first();

        // 3. Crear Oferta (Ejemplo: Todas las carreras abren en Mañana y Noche con 40 vacantes)
        foreach ($careers as $career) {
            if ($shiftDay) {
                DB::table('admission_offerings')->insert([
                    'academic_period_id' => $period->id,
                    'career_id' => $career->id,
                    'shift_id' => $shiftDay->id,
                    'vacancies' => 40,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if ($shiftNight) {
                DB::table('admission_offerings')->insert([
                    'academic_period_id' => $period->id,
                    'career_id' => $career->id,
                    'shift_id' => $shiftNight->id,
                    'vacancies' => 40,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

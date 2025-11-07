<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\DidacticUnit;
use App\Models\Institution;
use App\Models\Module;
use App\Models\StudyPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener la institución principal
        $institution = Institution::first();
        if (!$institution) {
            $this->command->error('No se encontró la institución principal. Ejecuta el DatabaseSeeder primero.');
            return;
        }

        // 2. Crear la Carrera
        $career = Career::firstOrCreate(
            ['institution_id' => $institution->id, 'code' => 'APSTI'],
            [
                'name' => 'Administración de Plataformas y Servicios de Tecnologías de Información',
                'duration_semesters' => 6,
                'degree_awarded' => 'Profesional Técnico en Administración de Plataformas y Servicios de TI',
                'authorization_resolution' => 'R.D. 001-2021',
            ]
        );

        // 3. Crear el Plan de Estudios
        $plan = StudyPlan::firstOrCreate(
            ['career_id' => $career->id, 'code' => 'APSTI-2021'],
            [
                'name' => 'Plan de Estudios APSTI',
                'version' => '2021',
                'start_date' => '2021-01-01',
                'total_credits' => 114,
                'total_hours' => 2880,
                'approval_resolution' => 'R.D. 001-2021',
            ]
        );

        // 4. Crear los Módulos
        $m1 = Module::firstOrCreate(
            ['study_plan_id' => $plan->id, 'module_number' => 1],
            ['name' => 'Soporte, mantenimiento y control de riesgos en sistemas informáticos', 'minimum_credits_approval' => 25, 'total_hours' => 800, 'sort_order' => 1]
        );
        
        $m2 = Module::firstOrCreate(
            ['study_plan_id' => $plan->id, 'module_number' => 2],
            ['name' => 'Desarrollo de sistemas informáticos y gestión', 'minimum_credits_approval' => 30, 'total_hours' => 768, 'sort_order' => 2]
        );

        $m3 = Module::firstOrCreate(
            ['study_plan_id' => $plan->id, 'module_number' => 3],
            ['name' => 'Arquitectura y proyectos TI', 'minimum_credits_approval' => 35, 'total_hours' => 816, 'sort_order' => 3]
        );

        // 5. Crear Unidades Didácticas (Cursos)
        
        // MÓDULO 1 - SEMESTRE I
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'MEI-I'], ['name' => 'Mantenimiento de equipos informáticos', 'semester' => 1, 'weekly_hours' => 8, 'total_hours' => 128, 'credits' => 5, 'unit_type' => 'career', 'semester_order' => 1]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'ISO-I'], ['name' => 'Instalación de Sistemas operativos', 'semester' => 1, 'weekly_hours' => 5, 'total_hours' => 80, 'credits' => 3, 'unit_type' => 'career', 'semester_order' => 2]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'ACPD-I'], ['name' => 'Administración de Centros de procesamiento de datos', 'semester' => 1, 'weekly_hours' => 4, 'total_hours' => 64, 'credits' => 3, 'unit_type' => 'career', 'semester_order' => 3]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'DRC-I'], ['name' => 'Diseño de redes de comunicación', 'semester' => 1, 'weekly_hours' => 6, 'total_hours' => 96, 'credits' => 4, 'unit_type' => 'career', 'semester_order' => 4]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'CE-I'], ['name' => 'Comunicación efectiva', 'semester' => 1, 'weekly_hours' => 4, 'total_hours' => 64, 'credits' => 3, 'unit_type' => 'transversal', 'semester_order' => 5]);
        // ... (Agregando el resto de cursos de tu SQL)
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'OE-I'], ['name' => 'Ofimática empresarial', 'semester' => 1, 'weekly_hours' => 3, 'total_hours' => 48, 'credits' => 2, 'unit_type' => 'transversal', 'semester_order' => 6]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'CE2-I'], ['name' => 'Comportamiento ético', 'semester' => 1, 'weekly_hours' => 3, 'total_hours' => 48, 'credits' => 2, 'unit_type' => 'transversal', 'semester_order' => 7]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'SI-I'], ['name' => 'Seguridad informática', 'semester' => 1, 'weekly_hours' => 5, 'total_hours' => 80, 'credits' => 3, 'unit_type' => 'career', 'semester_order' => 8]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'ADS-I'], ['name' => 'Análisis y diseño de sistemas', 'semester' => 1, 'weekly_hours' => 5, 'total_hours' => 80, 'credits' => 3, 'unit_type' => 'career', 'semester_order' => 9]);

        // MÓDULO 1 - SEMESTRE II
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'REC-II'], ['name' => 'Reparación de equipos de cómputo', 'semester' => 2, 'weekly_hours' => 8, 'total_hours' => 128, 'credits' => 5, 'unit_type' => 'career', 'semester_order' => 1]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'ICRC-II'], ['name' => 'Instalación y configuración de redes de comunicación', 'semester' => 2, 'weekly_hours' => 7, 'total_hours' => 112, 'credits' => 4, 'unit_type' => 'career', 'semester_order' => 2]);
        DidacticUnit::firstOrCreate(['module_id' => $m1->id, 'code' => 'ARC-II'], ['name' => 'Administración de redes de comunicación', 'semester' => 2, 'weekly_hours' => 5, 'total_hours' => 80, 'credits' => 3, 'unit_type' => 'career', 'semester_order' => 3]);
        // ... (y así sucesivamente para todos los cursos)

        // MÓDULO 2 - SEMESTRE III
        DidacticUnit::firstOrCreate(['module_id' => $m2->id, 'code' => 'PSE-III'], ['name' => 'Programación de software empresarial', 'semester' => 3, 'weekly_hours' => 8, 'total_hours' => 128, 'credits' => 5, 'unit_type' => 'career', 'semester_order' => 1]);
        DidacticUnit::firstOrCreate(['module_id' => $m2->id, 'code' => 'ICO-III'], ['name' => 'Inglés para la comunicación oral', 'semester' => 3, 'weekly_hours' => 3, 'total_hours' => 48, 'credits' => 2, 'unit_type' => 'transversal', 'semester_order' => 3]);
        
        // MÓDULO 3 - SEMESTRE V
        DidacticUnit::firstOrCreate(['module_id' => $m3->id, 'code' => 'AW-V'], ['name' => 'Arquitectura web', 'semester' => 5, 'weekly_hours' => 9, 'total_hours' => 144, 'credits' => 5, 'unit_type' => 'career', 'semester_order' => 1]);

        // MÓDULO 3 - SEMESTRE VI
        DidacticUnit::firstOrCreate(['module_id' => $m3->id, 'code' => 'PT-VI'], ['name' => 'Proyecto de Tesis', 'semester' => 6, 'weekly_hours' => 8, 'total_hours' => 128, 'credits' => 5, 'unit_type' => 'career', 'semester_order' => 1]);
        DidacticUnit::firstOrCreate(['module_id' => $m3->id, 'code' => 'PP-VI'], ['name' => 'Prácticas Pre-profesionales', 'semester' => 6, 'weekly_hours' => 12, 'total_hours' => 192, 'credits' => 8, 'unit_type' => 'career', 'semester_order' => 2]);

        $this->command->info('Estructura académica de APSTI creada exitosamente.');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            // II. Sumilla
            $table->text('sumilla')->nullable()->after('teacher_assignment_id');

            // III. Unidad de Competencia (Específica del Módulo)
            $table->text('unit_competence')->nullable()->after('sumilla');

            // IV. Capacidad de la Unidad Didáctica (Técnica)
            $table->text('course_capacity')->nullable()->after('unit_competence');

            // V. Competencias de Empleabilidad (JSON para guardar múltiples)
            $table->json('employability_competencies')->nullable()->after('course_capacity');

            // VII. Metodología
            $table->text('methodology')->nullable()->after('employability_competencies');

            // VIII. Ambientes y Recursos
            $table->text('resources')->nullable()->after('methodology');

            // IX. Sistema de Evaluación (Descripción general)
            $table->text('evaluation_system')->nullable()->after('resources');

            // X. Fuentes de información
            // Nota: Si 'bibliography' ya existe como string, lo dejamos o lo cambiamos si es necesario.
            // Aquí asumimos que agregamos web_sources.
            $table->json('web_sources')->nullable()->after('bibliography');

            // Control de auditoría para aprobación
            $table->timestamp('approved_at')->nullable()->after('observation_notes');
        });
    }

    public function down(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            $table->dropColumn([
                'sumilla',
                'unit_competence',
                'course_capacity',
                'employability_competencies',
                'methodology',
                'resources',
                'evaluation_system',
                'web_sources',
                'approved_at'
            ]);
        });
    }
};

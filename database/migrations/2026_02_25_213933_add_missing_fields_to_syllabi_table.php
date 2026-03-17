<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            // ========================================
            // CAMPOS PRINCIPALES DEL SÍLABO (Tab II)
            // ========================================

            // Sumilla: Resumen del contenido del curso
            if (!Schema::hasColumn('syllabi', 'sumilla')) {
                $table->text('sumilla')->nullable()->after('version');
            }

            // ========================================
            // COMPETENCIAS Y CAPACIDADES (Tab III y IV)
            // ========================================

            // Competencia de la Unidad Didáctica
            if (!Schema::hasColumn('syllabi', 'unit_competence')) {
                $table->text('unit_competence')->nullable()->after('sumilla');
            }

            // Capacidad del curso (antes de los indicadores)
            if (!Schema::hasColumn('syllabi', 'course_capacity')) {
                $table->text('course_capacity')->nullable()->after('unit_competence');
            }

            // ========================================
            // EMPLEABILIDAD (Tab V) - Guardado como JSON
            // ========================================

            if (!Schema::hasColumn('syllabi', 'employability_competencies')) {
                $table->json('employability_competencies')->nullable()->after('course_capacity');
            }

            // ========================================
            // METODOLOGÍA Y RECURSOS (Tab VII y VIII)
            // ========================================

            // Estrategias metodológicas
            if (!Schema::hasColumn('syllabi', 'methodology')) {
                $table->text('methodology')->nullable()->after('employability_competencies');
            }

            // Ambientes/Espacios (ya debería existir por migración anterior, pero verificamos)
            if (!Schema::hasColumn('syllabi', 'environments')) {
                $table->text('environments')->nullable()->after('methodology');
            }

            // Recursos y medios didácticos
            if (!Schema::hasColumn('syllabi', 'resources')) {
                $table->text('resources')->nullable()->after('environments');
            }

            // ========================================
            // EVALUACIÓN (Tab IX)
            // ========================================

            if (!Schema::hasColumn('syllabi', 'evaluation_system')) {
                $table->text('evaluation_system')->nullable()->after('resources');
            }

            // ========================================
            // REFERENCIAS (Tab X)
            // ========================================

            // Fuentes web (guardado como JSON para múltiples URLs)
            if (!Schema::hasColumn('syllabi', 'web_sources')) {
                $table->json('web_sources')->nullable()->after('evaluation_system');
            }

            // Nota: 'bibliography' ya existe como text, pero podría convertirse a JSON
            // Si deseas mantener compatibilidad, déjalo como está por ahora

            // ========================================
            // CONTROL DE FLUJO Y AUDITORÍA
            // ========================================

            // Fecha de envío a aprobación (diferente de approved_at)
            if (!Schema::hasColumn('syllabi', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('status');
            }

            // Notas de observación del coordinador/jefe
            if (!Schema::hasColumn('syllabi', 'observation_notes')) {
                $table->text('observation_notes')->nullable()->after('submitted_at');
            }

            // ========================================
            // CORRECCIÓN DE NOMBRE DE COLUMNA (si no se ejecutó la migración anterior)
            // ========================================

            // Renombrar approved_by_user_id → approved_by (si existe)
            if (
                Schema::hasColumn('syllabi', 'approved_by_user_id') &&
                !Schema::hasColumn('syllabi', 'approved_by')
            ) {
                $table->renameColumn('approved_by_user_id', 'approved_by');
            }

            // Cambiar approval_date → approved_at (para consistencia con timestamp)
            if (
                Schema::hasColumn('syllabi', 'approval_date') &&
                !Schema::hasColumn('syllabi', 'approved_at')
            ) {
                $table->renameColumn('approval_date', 'approved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            // Revertir cambios en orden inverso
            $columnsToRemove = [
                'observation_notes',
                'submitted_at',
                'web_sources',
                'evaluation_system',
                'resources',
                'methodology',
                'employability_competencies',
                'course_capacity',
                'unit_competence',
                'sumilla',
            ];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('syllabi', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Revertir renombres
            if (Schema::hasColumn('syllabi', 'approved_by')) {
                $table->renameColumn('approved_by', 'approved_by_user_id');
            }

            if (
                Schema::hasColumn('syllabi', 'approved_at') &&
                !Schema::hasColumn('syllabi', 'approval_date')
            ) {
                $table->renameColumn('approved_at', 'approval_date');
            }
        });
    }
};

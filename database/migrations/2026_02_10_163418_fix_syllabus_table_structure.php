<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            // 1. Agregar la columna 'environments' que faltaba para el Tab VIII
            if (!Schema::hasColumn('syllabi', 'environments')) {
                $table->text('environments')->nullable()->after('methodology');
            }

            // 2. Corregir el nombre de la columna de aprobación para que coincida con tu Modelo
            // De 'approved_by_user_id' a 'approved_by'
            if (Schema::hasColumn('syllabi', 'approved_by_user_id') && !Schema::hasColumn('syllabi', 'approved_by')) {
                $table->renameColumn('approved_by_user_id', 'approved_by');
            }
        });

        // 3. Actualizar el ENUM 'status' para incluir 'submitted' y 'rejected'
        // Usamos SQL directo porque modificar ENUMs con Eloquent es complejo
        DB::statement("ALTER TABLE syllabi MODIFY COLUMN status ENUM('draft', 'submitted', 'approved', 'observed', 'rejected') DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Revertir cambios (Opcional, pero recomendado por seguridad)
        DB::statement("ALTER TABLE syllabi MODIFY COLUMN status ENUM('draft', 'pending_approval', 'approved', 'observed') DEFAULT 'draft'");

        Schema::table('syllabi', function (Blueprint $table) {
            if (Schema::hasColumn('syllabi', 'environments')) {
                $table->dropColumn('environments');
            }
            if (Schema::hasColumn('syllabi', 'approved_by')) {
                $table->renameColumn('approved_by', 'approved_by_user_id');
            }
        });
    }
};

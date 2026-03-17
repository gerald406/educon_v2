<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_sessions', function (Blueprint $table) {
            // Tipo de actividad: requerido por el modelo MINEDU
            // Teórico | Práctico | Teórico-Práctico
            $table->enum('activity_type', ['teorico', 'practico', 'teorico-practico'])
                ->default('teorico-practico')
                ->after('transversal_competence');
        });
    }

    public function down(): void
    {
        Schema::table('learning_sessions', function (Blueprint $table) {
            $table->dropColumn('activity_type');
        });
    }
};

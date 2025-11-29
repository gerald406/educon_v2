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
        Schema::table('enrollment_reserves', function (Blueprint $table) {
            // 1. Primero creamos la columna del periodo académico (que faltaba)
            $table->foreignId('academic_period_id')
                ->after('student_id') // La ponemos después del estudiante
                ->constrained('academic_periods')
                ->onDelete('cascade');

            // 2. Luego creamos el código de resolución
            $table->string('resolution_code', 50)
                ->after('academic_period_id'); // Ahora sí existe esta columna
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_reserves', function (Blueprint $table) {
            $table->dropForeign(['academic_period_id']);
            $table->dropColumn(['academic_period_id', 'resolution_code']);
        });
    }
};

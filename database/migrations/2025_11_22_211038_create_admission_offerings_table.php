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
        Schema::create('admission_offerings', function (Blueprint $table) {
            $table->id();
            // Relación con el periodo de admisión (ej. 2025-I)
            $table->foreignId('academic_period_id')->constrained('academic_periods');

            // Relación con la carrera
            $table->foreignId('career_id')->constrained('careers');

            // Relación con el turno
            $table->foreignId('shift_id')->constrained('shifts');

            // Configuración específica
            $table->integer('vacancies')->unsigned(); // Ej. 30

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Evitar duplicados: No puedes crear dos ofertas para la misma carrera/turno en el mismo periodo
            $table->unique(['academic_period_id', 'career_id', 'shift_id'], 'unique_offering');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_offerings');
    }
};

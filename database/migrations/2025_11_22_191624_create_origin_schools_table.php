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
        Schema::create('origin_schools', function (Blueprint $table) {
            $table->id();

            // COD_MOD: Código Modular (Único)
            $table->string('modular_code', 20)->unique();

            // CEN_EDU: Nombre del Colegio
            $table->string('name', 255);
            //D_NIV_MOD (nivel: secundaria, Secundaria de Adultos, Básica Alternativa-Avanzado)
            $table->string('d_niv_mod');

            // D_GESTION: Tipo de Gestión (Pública / Privada)
            $table->string('management_type', 50)->nullable();

            // CODGEO: Relación con el Ubigeo (Location)
            // Referencia a la columna 'iddist' de la tabla 'locations'
            $table->string('ubigeo_code', 10)->nullable();
            $table->foreign('ubigeo_code')->references('iddist')->on('locations')->onDelete('set null');

            $table->timestamps();

            // Índice para búsquedas rápidas por nombre (para el autocompletado)
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('origin_schools');
    }
};

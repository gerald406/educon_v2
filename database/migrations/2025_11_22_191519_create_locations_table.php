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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            // Código de Ubigeo (Ej. "010101"). Es String para preservar el '0' inicial.
            // Lo hacemos único e indexado para búsquedas rápidas.
            $table->string('iddist', 10)->unique();

            // Datos geográficos
            $table->string('nombdep', 100)->index();  // Indexado para el 1er select
            $table->string('nombprov', 100)->index(); // Indexado para el 2do select
            $table->string('nombdist', 100);

            $table->string('nom_capital', 100)->nullable();
            $table->string('cod_reg_nat', 10)->nullable();
            $table->string('region_natural', 50)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};

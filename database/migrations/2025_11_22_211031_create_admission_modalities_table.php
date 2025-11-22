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
        Schema::create('admission_modalities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150); // Ej. "Primeros Puestos", "Ordinario"
            $table->enum('type', ['ordinario', 'extraordinario']); // Para filtros rápidos
            $table->text('requirements')->nullable(); // Ej. "Certificado de notas..."
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_modalities');
    }
};

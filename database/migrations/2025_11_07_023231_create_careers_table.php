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
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            // Cada carrera pertenece a una institución
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            
            $table->string('code', 10); // Código de la carrera (ej. "APSTI")
            $table->string('name', 150); // Nombre (ej. "Administración de Plataformas...")
            $table->integer('duration_semesters')->default(6);
            $table->string('degree_awarded', 200)->nullable(); // Título otorgado
            $table->string('authorization_resolution', 50)->nullable(); // Resolución de autorización
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();

            // Una institución no puede tener dos carreras con el mismo código
            $table->unique(['institution_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};

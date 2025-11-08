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
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            
            // Llave foránea opcional al año académico
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');

            $table->string('code', 20); // ej. "2025-I"
            $table->string('name', 100); // ej. "Periodo Académico 2025-I"
            $table->date('start_date'); // Inicio del semestre
            $table->date('end_date'); // Fin del semestre
            
            // Fechas clave para el proceso
            $table->date('enrollment_start_date'); // Inicio de Matrícula
            $table->date('enrollment_end_date'); // Fin de Matrícula
            $table->date('classes_start_date'); // Inicio de Clases
            $table->date('classes_end_date'); // Fin de Clases

            $table->enum('status', ['planned', 'active', 'closed'])->default('planned');
            
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institution_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_periods');
    }
};

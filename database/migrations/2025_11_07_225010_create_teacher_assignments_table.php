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
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            
            // --- Las 4 llaves principales ---
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('didactic_unit_id')->constrained('didactic_units')->onDelete('cascade');
            $table->foreignId('academic_period_id')->constrained('academic_periods')->onDelete('cascade');
            
            // Llave foránea a nuestra tabla de Turnos
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');

            $table->string('section', 5)->default('A'); // ej. "A", "B", "C1"
            $table->integer('max_capacity')->default(30); // Vacantes
            $table->integer('current_enrolled')->default(0); // Matriculados (se actualiza con triggers)
            
            $table->enum('status', ['active', 'suspended', 'completed'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();

            // Clave única: No se puede asignar el mismo docente, al mismo curso, 
            // en el mismo periodo, con la misma sección.
            $table->unique([
                'teacher_id', 
                'didactic_unit_id', 
                'academic_period_id', 
                'section'
            ], 'unique_assignment_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};

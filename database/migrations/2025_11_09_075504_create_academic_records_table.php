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
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            
            // --- Llaves principales ---
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('didactic_unit_id')->constrained('didactic_units')->onDelete('cascade');
            $table->foreignId('academic_period_id')->constrained('academic_periods')->onDelete('cascade');
            
            // --- Resultados ---
            $table->decimal('final_grade', 4, 2); // Promedio final del curso
            $table->integer('credits_earned');
            $table->enum('course_status', ['approved', 'failed', 'withdrawn', 'nsp']); // nsp = No se presentó
            $table->integer('times_taken')->default(1); // Cuántas veces llevó el curso
            
            $table->text('notes')->nullable();
            $table->timestamps();

            // Un estudiante solo puede tener un registro por curso y por periodo
            $table->unique(['student_id', 'didactic_unit_id', 'academic_period_id'], 'unique_academic_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};

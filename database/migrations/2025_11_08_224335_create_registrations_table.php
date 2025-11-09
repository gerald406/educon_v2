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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            
            // A qué matrícula pertenece esta inscripción
            $table->foreignId('enrollment_id')->constrained('enrollments')->onDelete('cascade');
            
            // A qué sección (curso-docente-turno) se está inscribiendo
            $table->foreignId('teacher_assignment_id')->constrained('teacher_assignments')->onDelete('cascade');
            
            $table->timestamp('registration_date')->default(now());
            $table->enum('registration_type', ['mandatory', 'elective', 'recovery'])->default('mandatory');
            $table->enum('status', ['enrolled', 'withdrawn', 'transferred'])->default('enrolled');
            
            $table->timestamps();
            $table->softDeletes();

            // Un estudiante no puede inscribirse dos veces en la misma sección
            $table->unique(['enrollment_id', 'teacher_assignment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};

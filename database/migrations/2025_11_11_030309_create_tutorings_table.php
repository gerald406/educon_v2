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
        Schema::create('tutorings', function (Blueprint $table) {
            $table->id();
            
            // Llaves foráneas a los actores
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade'); // El docente tutor

            $table->dateTime('tutoring_date'); // Fecha y hora de la sesión
            $table->enum('tutoring_type', ['academic', 'personal', 'vocational', 'group']);
            
            // Detalles de la sesión
            $table->text('reason'); // Motivo de la tutoría
            $table->text('session_development')->nullable(); // Desarrollo de la sesión
            $table->text('agreements_commitments')->nullable(); // Acuerdos y compromisos
            
            $table->boolean('follow_up_required')->default(false); // ¿Requiere seguimiento?
            
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorings');
    }
};

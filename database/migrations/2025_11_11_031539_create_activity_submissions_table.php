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
        Schema::create('activity_submissions', function (Blueprint $table) {
            $table->id();
            
            // A qué actividad pertenece esta entrega
            $table->foreignId('academic_activity_id')->constrained('academic_activities')->onDelete('cascade');
            
            // Qué estudiante (inscrito en ese curso) está entregando
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            
            $table->timestamp('submission_date')->default(now());
            $table->string('submission_file_url')->nullable(); // Archivo del estudiante
            $table->text('student_comments')->nullable();
            
            // Calificación de la entrega
            $table->text('teacher_comments')->nullable();
            $table->decimal('grade', 4, 2)->nullable();
            $table->timestamp('review_date')->nullable();
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('status', ['submitted', 'reviewed'])->default('submitted');
            
            $table->timestamps();

            // Un estudiante solo puede entregar una vez por actividad
            $table->unique(['academic_activity_id', 'registration_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_submissions');
    }
};

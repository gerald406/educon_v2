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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // A qué carrera y plan pertenece
            $table->foreignId('career_id')->constrained('careers')->onDelete('cascade');
            $table->foreignId('study_plan_id')->constrained('study_plans')->onDelete('cascade');
            
            $table->string('code', 20)->unique(); // Código de estudiante
            $table->integer('current_semester')->default(1);
            $table->integer('accumulated_credits')->default(0);
            $table->decimal('weighted_average', 4, 2)->default(0.00); // Promedio Ponderado
            $table->enum('academic_status', ['regular', 'irregular', 'graduated', 'withdrawn', 'enrollment_reserved'])->default('regular');
            $table->date('admission_date');
            $table->date('graduation_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

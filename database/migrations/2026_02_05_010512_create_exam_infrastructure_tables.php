<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pabellones
        Schema::create('exam_pavilions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('location', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Aulas
        Schema::create('exam_classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_pavilion_id')->constrained()->onDelete('cascade');
            $table->string('room_number', 10); // Ej: 101, A-1
            $table->integer('capacity')->unsigned();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Un aula no debe duplicarse en un mismo pabellón
            $table->unique(['exam_pavilion_id', 'room_number']);
        });

        // 3. Asignación de Aula (Distribución)
        Schema::create('exam_classroom_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_classroom_id')->constrained();
            // Vinculamos con el postulante (Applicant)
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');

            $table->timestamp('assigned_at')->useCurrent();

            // Un postulante solo puede estar en un aula a la vez
            $table->unique('applicant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_classroom_assignments');
        Schema::dropIfExists('exam_classrooms');
        Schema::dropIfExists('exam_pavilions');
    }
};

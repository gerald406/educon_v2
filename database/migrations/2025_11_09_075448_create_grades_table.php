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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            
            // A qué inscripción (estudiante-en-curso) pertenece esta nota
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            
            // A qué tipo de evaluación corresponde (ej. "Examen Parcial")
            $table->foreignId('evaluation_type_id')->constrained('evaluation_types')->onDelete('cascade');
            
            // Quién registró la nota (el docente)
            $table->foreignId('registered_by_user_id')->constrained('users')->onDelete('cascade');
            
            $table->decimal('grade', 4, 2); // Nota (ej. 13.50)
            $table->date('evaluation_date');
            $table->text('notes')->nullable();
            
            $table->timestamps();

            // Un estudiante no puede tener dos notas del mismo tipo en el mismo curso
            $table->unique(['registration_id', 'evaluation_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};

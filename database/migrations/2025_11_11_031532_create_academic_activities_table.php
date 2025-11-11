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
        Schema::create('academic_activities', function (Blueprint $table) {
            $table->id();
            
            // A qué sección (curso-docente) pertenece esta actividad
            $table->foreignId('teacher_assignment_id')->constrained('teacher_assignments')->onDelete('cascade');
            
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('activity_type', ['practice', 'project', 'research', 'presentation', 'exam', 'workshop', 'laboratory']);
            
            $table->dateTime('assigned_date'); // Fecha de publicación
            $table->dateTime('due_date'); // Fecha límite de entrega
            
            $table->decimal('weight', 5, 2)->default(0.00); // Opcional: Peso sobre la nota
            $table->string('activity_file_url')->nullable(); // Archivo adjunto del docente (ej. PDF con instrucciones)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_activities');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_sessions', function (Blueprint $table) {
            $table->id();

            // Relación 1:1 estricta con la programación del sílabo
            // Esto garantiza la regla: "Por cada sesión programada, se genera una sesión de aprendizaje"
            $table->foreignId('syllabus_unit_id')->constrained('syllabus_units')->onDelete('cascade')->unique();

            // I. Información General (Hereda la mayoría, aquí van los específicos)
            $table->string('transversal_competence')->nullable(); // Competencia transversal priorizada

            // II. Secuencia Didáctica (Inicio, Desarrollo, Cierre)
            // Guardamos como JSON para flexibilidad: 
            // [{"moment": "inicio", "activity": "...", "resources": "...", "time": 15}, ...]
            $table->json('sequence_activities')->nullable();

            // III. Actividades de Evaluación Específica
            $table->text('evaluation_criteria')->nullable(); // Indicador de logro específico de la sesión
            $table->string('evaluation_technique')->nullable();
            $table->string('evaluation_instrument')->nullable(); // Puede confirmar o detallar el del sílabo
            $table->string('evaluation_moment')->nullable(); // Inicio, Proceso, Salida

            // IV. Bibliografía Específica (si difiere del sílabo)
            $table->text('bibliography')->nullable();

            // Control de Estado y Ejecución
            $table->enum('status', ['pending', 'completed', 'executed'])->default('pending');
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_sessions');
    }
};

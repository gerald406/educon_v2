<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabus_units', function (Blueprint $table) {
            $table->id();

            // Relación: Cada sesión pertenece a un Indicador de Logro específico
            $table->foreignId('syllabus_indicator_id')->constrained('syllabus_indicators')->onDelete('cascade');

            // Datos de Programación
            $table->integer('session_number'); // N° de Sesión (correlativo 1, 2, 3...)
            $table->string('week_range')->nullable(); // Ej: "Semana 1", "01/04 - 05/04"
            $table->date('execution_date')->nullable(); // Fecha específica

            // Contenido Pedagógico Base del Sílabo
            $table->string('name'); // Denominación/Tema de la sesión
            $table->text('content')->nullable(); // Contenidos básicos
            $table->text('learning_outcome')->nullable(); // Logro de la sesión
            $table->string('evaluation_instrument')->nullable(); // Instrumento de evaluación previsto

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_units');
    }
};

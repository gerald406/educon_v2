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
        Schema::create('didactic_units', function (Blueprint $table) {
            $table->id();
            // Cada unidad (curso) pertenece a un módulo
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            
            $table->string('code', 20); // ej. "MEI-I"
            $table->string('name', 200); // ej. "Mantenimiento de Equipos Informáticos"
            $table->integer('semester'); // Semestre en el que se dicta (1, 2, 3...)
            $table->integer('weekly_hours');
            $table->integer('total_hours');
            $table->integer('credits');
            $table->enum('unit_type', ['career', 'transversal']); // De carrera o transversal
            $table->text('description')->nullable();
            $table->text('specific_competencies')->nullable();
            $table->integer('semester_order'); // Orden dentro del semestre
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();

            // Un módulo no puede tener dos unidades con el mismo código
            $table->unique(['module_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('didactic_units');
    }
};

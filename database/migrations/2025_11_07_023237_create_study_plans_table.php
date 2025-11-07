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
        Schema::create('study_plans', function (Blueprint $table) {
            $table->id();
            // Cada plan de estudio pertenece a una carrera
            $table->foreignId('career_id')->constrained('careers')->onDelete('cascade');
            
            $table->string('code', 20); // ej. "APSTI-2021"
            $table->string('name', 100); // ej. "Plan de Estudios 2021"
            $table->string('version', 10);
            $table->date('start_date'); // Fecha de inicio de vigencia
            $table->date('end_date')->nullable(); // Fecha de fin (si es obsoleto)
            $table->integer('total_credits');
            $table->integer('total_hours');
            $table->string('approval_resolution', 50)->nullable();
            $table->enum('status', ['active', 'inactive', 'obsolete'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();

            // Una carrera no puede tener dos planes con el mismo código
            $table->unique(['career_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_plans');
    }
};

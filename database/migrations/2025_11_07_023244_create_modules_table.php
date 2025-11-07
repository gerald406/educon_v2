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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            // Cada módulo pertenece a un plan de estudios
            $table->foreignId('study_plan_id')->constrained('study_plans')->onDelete('cascade');
            
            $table->integer('module_number'); // ej. 1, 2, 3
            $table->string('name', 150); // Nombre del módulo
            $table->text('description')->nullable();
            $table->integer('minimum_credits_approval'); // Créditos para aprobar el módulo
            $table->integer('total_hours');
            $table->text('competencies')->nullable(); // Competencias del módulo
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();

            // Un plan no puede tener dos módulos con el mismo número
            $table->unique(['study_plan_id', 'module_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};

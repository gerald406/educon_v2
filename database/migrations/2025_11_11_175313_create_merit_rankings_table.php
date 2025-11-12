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
        Schema::create('merit_rankings', function (Blueprint $table) {
            $table->id();
            
            // Llaves foráneas
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('academic_period_id')->constrained('academic_periods')->onDelete('cascade');
            
            // Opcional: para rankings por módulo
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('set null');

            // --- Resultados del Ranking ---
            $table->decimal('weighted_average', 5, 3); // Promedio ponderado del periodo/módulo
            $table->integer('general_position'); // Posición general en el periodo
            $table->integer('module_position')->nullable(); // Posición dentro del módulo
            $table->integer('period_credits'); // Créditos cursados en el periodo
            
            $table->timestamp('calculation_date')->default(now());
            
            $table->timestamps();

            // Un estudiante solo puede tener un registro por periodo (y módulo, si aplica)
            $table->unique(['student_id', 'academic_period_id', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merit_rankings');
    }
};

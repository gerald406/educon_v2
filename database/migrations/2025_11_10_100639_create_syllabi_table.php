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
        Schema::create('syllabi', function (Blueprint $table) {
            $table->id();
            
            // Llave foránea única: Cada "Carga Académica" (sección) tiene un solo sílabo.
            $table->foreignId('teacher_assignment_id')
                    ->constrained('teacher_assignments')
                    ->onDelete('cascade')
                    ->unique();
            
            // Campos de contenido del sílabo
            $table->text('general_competence')->nullable(); // Competencia general
            $table->text('specific_competencies')->nullable();
            $table->text('terminal_capacities')->nullable();
            $table->text('evaluation_criteria')->nullable();
            $table->text('bibliography')->nullable();
            
            // Estado de aprobación
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'observed'])
                    ->default('draft');
            
            $table->date('approval_date')->nullable();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('file_url')->nullable(); // Enlace al PDF del sílabo
            $table->string('version', 10)->default('1.0');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabi');
    }
};

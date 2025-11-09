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
        Schema::create('graduation_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            $table->enum('process_type', ['thesis', 'project', 'sufficiency_exam']); // Modalidad
            $table->string('title', 500); // Título de la tesis o proyecto
            $table->text('abstract')->nullable(); // Resumen
            
            // Asesor y Jurados (enlazados a la tabla 'teachers')
            $table->foreignId('advisor_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->foreignId('jury_president_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->foreignId('jury_secretary_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->foreignId('jury_member_id')->nullable()->constrained('teachers')->onDelete('set null');
            
            $table->date('proposal_date')->nullable(); // Fecha de propuesta
            $table->date('approval_date')->nullable(); // Fecha de aprobación
            $table->date('defense_date')->nullable(); // Fecha de sustentación
            
            $table->decimal('final_grade', 4, 2)->nullable();
            $table->string('document_url')->nullable(); // URL al PDF de la tesis
            
            $table->enum('status', ['proposal', 'in_development', 'review', 'defended', 'approved', 'rejected'])->default('proposal');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_processes');
    }
};

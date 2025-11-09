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
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Datos de la Empresa
            $table->string('company_name', 200);
            $table->string('company_ruc', 11)->nullable();
            $table->text('company_address')->nullable();
            
            // Datos del Supervisor en la Empresa
            $table->string('supervisor_name', 200);
            $table->string('supervisor_position', 100)->nullable();
            $table->string('supervisor_email', 100)->nullable();
            
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_hours');
            
            // Evaluación
            $table->decimal('evaluation_score', 4, 2)->nullable();
            $table->string('evaluation_file_url')->nullable(); // URL a la ficha de evaluación
            $table->string('certificate_url')->nullable(); // URL a la constancia de prácticas
            
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};

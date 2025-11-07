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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            
            // Llave foránea a la tabla 'users' de Jetstream
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // A qué institución pertenece
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            
            $table->string('code', 20); // Código de docente
            $table->string('academic_degree', 100)->nullable(); // Grado Académico
            $table->string('specialty', 150)->nullable(); // Especialidad
            $table->text('professional_experience')->nullable();
            $table->string('cv_url')->nullable(); // URL a CV
            $table->enum('contract_type', ['permanent', 'contracted', 'hourly'])->default('contracted');
            $table->date('hire_date')->nullable();
            
            // [NUEVO] Campo para regla de negocio (Día de preparación)
            $table->enum('preparation_day', [
                'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'
            ])->nullable();

            $table->enum('status', ['active', 'leave', 'terminated'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();

            // Un docente solo puede estar una vez por institución
            $table->unique(['user_id', 'institution_id']);
            $table->unique(['institution_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};

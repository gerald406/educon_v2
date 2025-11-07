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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            // Llave foránea a 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // A qué carrera y plan postula
            $table->foreignId('career_id')->constrained('careers')->onDelete('cascade');
            $table->foreignId('study_plan_id')->constrained('study_plans')->onDelete('cascade');
            
            // A qué periodo de admisión postula (Lo crearemos más adelante)
            // $table->foreignId('admission_period_id')->constrained('admission_periods');
            
            $table->string('code', 20)->unique(); // Código de postulante
            $table->enum('admission_type', ['regular', 'extraordinary', 'external_transfer', 'internal_transfer'])->default('regular');
            $table->decimal('exam_score', 5, 2)->nullable();
            $table->integer('merit_position')->nullable();
            $table->enum('application_status', ['registered', 'evaluated', 'approved', 'no_vacancy', 'cancelled'])->default('registered');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};

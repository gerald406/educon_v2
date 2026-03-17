<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_coordinators', function (Blueprint $table) {
            $table->id();

            // El usuario que actúa como coordinador
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // La carrera que coordina
            $table->foreignId('career_id')
                ->constrained('careers')
                ->onDelete('cascade');

            // Un coordinador solo puede tener UNA carrera asignada
            $table->unique('user_id');

            // Una carrera solo puede tener UN coordinador activo a la vez
            $table->unique('career_id');

            $table->boolean('is_active')->default(true);
            $table->date('assigned_date');
            $table->text('notes')->nullable(); // Resolución o motivo de asignación

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_coordinators');
    }
};

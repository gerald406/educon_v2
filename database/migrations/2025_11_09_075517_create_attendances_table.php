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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            // A qué inscripción (estudiante-en-curso) pertenece
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            
            // A qué bloque de horario específico
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            
            // Quién registró la asistencia (el docente)
            $table->foreignId('registered_by_user_id')->constrained('users')->onDelete('cascade');
            
            $table->date('class_date');
            $table->enum('attendance_type', ['present', 'absent', 'late', 'justified']);
            $table->integer('late_minutes')->default(0);
            
            $table->timestamps();

            // Un estudiante solo puede tener un registro de asistencia por día/bloque
            $table->unique(['registration_id', 'schedule_id', 'class_date'], 'unique_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

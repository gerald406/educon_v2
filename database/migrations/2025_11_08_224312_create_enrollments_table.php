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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            
            // Llaves foráneas principales
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('academic_period_id')->constrained('academic_periods')->onDelete('cascade');
            
            $table->timestamp('enrollment_date')->default(now());
            $table->integer('semester_enrolled'); // El semestre que está cursando (ej. 3)
            
            $table->enum('enrollment_type', ['first_time', 'continuing', 'restart', 'reincorporation'])->default('continuing');
            
            // Control de pagos
            $table->decimal('amount_paid', 8, 2)->default(0.00);
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            
            $table->enum('status', ['active', 'cancelled', 'frozen'])->default('active');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Un estudiante solo puede tener UNA matrícula por periodo
            $table->unique(['student_id', 'academic_period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};

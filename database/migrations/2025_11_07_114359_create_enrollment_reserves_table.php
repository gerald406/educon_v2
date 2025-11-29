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
        Schema::create('enrollment_reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

            // A qué periodo académico aplica la reserva (Lo crearemos más adelante)
            //$table->foreignId('academic_period_id')->constrained('academic_periods');

            $table->text('reason');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('supporting_document_url')->nullable(); // URL a resolución o solicitud
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_reserves');
    }
};

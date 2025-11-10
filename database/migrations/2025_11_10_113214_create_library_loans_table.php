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
        Schema::create('library_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_resource_id')->constrained('library_resources')->onDelete('cascade');
            
            // Un préstamo lo puede hacer un 'user' (docente, estudiante o admin)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->dateTime('loan_date'); // Fecha y hora del préstamo
            $table->date('due_date'); // Fecha de devolución
            $table->dateTime('return_date')->nullable(); // Fecha real de devolución
            
            $table->enum('status', ['active', 'returned', 'overdue', 'lost'])->default('active');
            $table->decimal('fine_amount', 6, 2)->default(0.00); // Multa
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_loans');
    }
};

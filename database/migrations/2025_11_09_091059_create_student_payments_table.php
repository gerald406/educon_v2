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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            
            // A qué estudiante pertenece esta deuda/pago
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Qué concepto se está pagando (ej. "Matrícula Regular")
            $table->foreignId('payment_concept_id')->constrained('payment_concepts')->onDelete('cascade');
            
            // A qué periodo corresponde (opcional, ej. para pensiones)
            $table->foreignId('academic_period_id')->nullable()->constrained('academic_periods')->onDelete('set null');
            
            // Quién registró el pago (ej. el cajero)
            $table->foreignId('registered_by_user_id')->nullable()->constrained('users')->onDelete('set null');

            // --- Montos ---
            $table->decimal('original_amount', 8, 2);
            $table->decimal('discount_amount', 8, 2)->default(0.00);
            $table->decimal('final_amount', 8, 2); // original_amount - discount_amount
            
            // --- Fechas ---
            $table->date('due_date'); // Fecha de vencimiento
            $table->dateTime('payment_date')->nullable(); // Fecha en que se pagó

            // --- Información de la Transacción ---
            $table->string('transaction_number', 50)->nullable(); // Nro de operación
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'debit_card'])->nullable();
            
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->text('notes')->nullable(); // Notas de caja
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};

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
        Schema::create('cash_sessions', function (Blueprint $table) {
            $table->id();
            // Usuario que abre la caja (Cajero)
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('opening_time'); // Fecha y hora de apertura
            $table->decimal('opening_balance', 10, 2); // Monto inicial (caja chica)

            $table->dateTime('closing_time')->nullable(); // Fecha y hora de cierre
            $table->decimal('closing_balance_cash', 10, 2)->nullable(); // Monto contado (efectivo)
            $table->decimal('calculated_cash', 10, 2)->nullable(); // Monto que *debería* haber (efectivo)
            $table->decimal('total_other_methods', 10, 2)->nullable(); // Total tarjetas, yape, etc.
            $table->decimal('difference', 10, 2)->nullable(); // Sobrante o faltante

            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable(); // Notas de cierre

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_sessions');
    }
};

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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_session_id')->constrained('cash_sessions'); // Sesión de caja que lo emitió
            $table->foreignId('issuer_id')->constrained('users'); // El Cajero (User ID) que lo emitió
            $table->foreignId('client_id')->constrained('users'); // El Cliente (User ID) que paga

            $table->string('voucher_type', 20); // boleta, factura, recibo
            $table->string('series', 8); // B001
            $table->bigInteger('number'); // El correlativo (ej. 1234)

            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method', 50); // Efectivo, Yape/Plin, Tarjeta
            $table->string('transaction_code', 100)->nullable(); // Nro de operación de tarjeta/yape
            $table->text('observations')->nullable(); // Observaciones del pago

            $table->enum('status', ['issued', 'annulled'])->default('issued'); // Emitido, Anulado
            $table->timestamp('issued_at')->default(now());

            $table->timestamps();

            $table->unique(['series', 'number']); // El número de serie y correlativo es único
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

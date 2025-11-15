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
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers'); // El comprobante que se anula
            $table->foreignId('user_id')->constrained('users'); // Quién lo anula
            $table->foreignId('cash_session_id')->constrained('cash_sessions'); // En qué turno se anula

            $table->text('reason');
            $table->decimal('amount', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};

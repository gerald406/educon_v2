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
        Schema::create('voucher_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions');
            // Tipo de comprobante: Boleta, Factura, Nota de Crédito, o Recibo Interno
            $table->enum('voucher_type', ['boleta', 'factura', 'nota_credito', 'recibo'])->default('recibo');
            $table->string('series', 8); // Ej: B001, R001
            $table->bigInteger('current_number')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->unique(['institution_id', 'voucher_type', 'series']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_series');
    }
};

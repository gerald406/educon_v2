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
        Schema::table('student_payments', function (Blueprint $table) {
            // La deuda ahora se asocia al voucher que la pagó
            // Se coloca después de 'registered_by_user_id'
            $table->foreignId('voucher_id')
                ->nullable()
                ->constrained('vouchers')
                ->onDelete('set null')
                ->after('registered_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_payments', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn('voucher_id');
        });
    }
};

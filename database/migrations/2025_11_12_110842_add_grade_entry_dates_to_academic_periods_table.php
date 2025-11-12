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
        Schema::table('academic_periods', function (Blueprint $table) {
            $table->dateTime('grade_entry_start_date')->nullable()->after('classes_end_date');
            $table->dateTime('grade_entry_end_date')->nullable()->after('grade_entry_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_periods', function (Blueprint $table) {
            $table->dropColumn(['grade_entry_start_date', 'grade_entry_end_date']);
        });
    }
};

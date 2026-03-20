<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_classroom_assignments', function (Blueprint $table) {
            // Fecha/hora de ingreso al recinto — null = no asistió
            $table->timestamp('attended_at')->nullable()->after('assigned_at');
        });
    }

    public function down(): void
    {
        Schema::table('exam_classroom_assignments', function (Blueprint $table) {
            $table->dropColumn('attended_at');
        });
    }
};

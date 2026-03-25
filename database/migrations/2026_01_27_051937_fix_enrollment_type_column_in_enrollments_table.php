<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ->change() with default is MySQL-specific; skip on SQLite (column already has a default in original migration)
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->string('enrollment_type', 50)->default('regular')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->string('enrollment_type', 50)->change();
            });
        }
    }
};

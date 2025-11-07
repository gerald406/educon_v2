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
        Schema::create('classroom_resources', function (Blueprint $table) {
            $table->id();
            $table->string('classroom_code', 20)->unique();
            $table->string('name', 100);
            $table->string('building', 100)->nullable();
            $table->string('floor', 10)->nullable();
            $table->integer('capacity');
            $table->boolean('has_projector')->default(false);
            $table->boolean('has_computers')->default(false);
            $table->integer('computer_count')->default(0);
            $table->boolean('has_air_conditioning')->default(false);
            $table->text('location')->nullable();
            $table->enum('status', ['available', 'maintenance', 'unavailable'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_resources');
    }
};

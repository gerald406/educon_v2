<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabus_indicators', function (Blueprint $table) {
            $table->id();
            // Relación con el Sílabo Padre
            $table->foreignId('syllabus_id')->constrained('syllabi')->onDelete('cascade');

            $table->text('description'); // El texto del indicador de logro
            $table->integer('sort_order')->default(1); // Orden visual (1, 2, 3...)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_indicators');
    }
};

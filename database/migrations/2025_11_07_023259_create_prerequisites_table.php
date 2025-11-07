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
        // Esta es una tabla pivote para una relación de muchos a muchos (de una unidad consigo misma)
        Schema::create('prerequisites', function (Blueprint $table) {
            $table->id();
            
            // El curso (ej. Cálculo II)
            $table->foreignId('didactic_unit_id')
                  ->constrained('didactic_units')
                  ->onDelete('cascade');
            
            // El curso que es prerrequisito (ej. Cálculo I)
            $table->foreignId('prerequisite_unit_id')
                  ->constrained('didactic_units')
                  ->onDelete('cascade');
            
            $table->enum('type', ['mandatory', 'recommended'])->default('mandatory');
            $table->timestamps();

            // Una unidad no puede tener el mismo prerrequisito dos veces
            $table->unique(['didactic_unit_id', 'prerequisite_unit_id'], 'unit_prerequisite_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prerequisites');
    }
};

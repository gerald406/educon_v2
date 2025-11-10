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
        Schema::create('library_resources', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // Código del libro (ej. 808.1/M3R)
            $table->string('title', 300);
            $table->string('author', 200)->nullable();
            
            // Relación (corregida de nuestra Fase 6)
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->foreignId('career_id')->nullable()->constrained('careers')->onDelete('set null'); // Opcional, si el libro es de una carrera

            $table->enum('resource_type', ['book', 'magazine', 'thesis', 'manual', 'digital', 'audiovisual']);
            $table->string('publisher', 200)->nullable(); // Editorial
            $table->year('publication_year')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->integer('copies_available')->default(1);
            $table->string('physical_location', 100)->nullable(); // Ej. "Estante A-3"
            $table->text('description')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->string('digital_file_url')->nullable();
            
            $table->enum('status', ['available', 'borrowed', 'reserved', 'maintenance', 'lost'])->default('available');
            
            $table->timestamps();
            
            // Índice FullText para búsquedas
            $table->fullText(['title', 'author', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_resources');
    }
};

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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            $table->enum('certificate_type', ['modular', 'grades', 'studies', 'graduation']);
            
            // Si es 'modular', a qué módulo pertenece
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('set null');
            
            $table->string('code', 50)->unique(); // Código único del certificado
            $table->date('issue_date'); // Fecha de emisión
            $table->string('document_url')->nullable(); // URL al PDF generado
            
            // Quién emitió el certificado (ej. el admin de secretaría)
            $table->foreignId('issued_by_user_id')->constrained('users')->onDelete('cascade');
            
            $table->enum('status', ['valid', 'cancelled', 'expired'])->default('valid');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};

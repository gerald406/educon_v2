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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();

            // 1. Relación con el Usuario (Datos Personales Básicos: DNI, Nombres, Email, etc. están en 'users')
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['masculino', 'femenino']);
            $table->date('birthday');

            // 2. Datos de Nacimiento (Paso 2 del Wizard)
            // Relación con la tabla 'locations' (Ubigeo) usando el código 'iddist'
            
            $table->string('ubigeo_birth_id', 10)->nullable();
            $table->foreign('ubigeo_birth_id')->references('iddist')->on('locations')->onDelete('set null');

            // Foto del postulante (Paso 2)
            $table->string('photo_url', 255)->nullable();

            // 3. Datos del Colegio de Procedencia (Paso 3 del Wizard)
            $table->foreignId('origin_school_id')->nullable()->constrained('origin_schools')->onDelete('set null');
            $table->year('school_graduation_year')->nullable(); // Año de egreso

            // 4. Datos de la Postulación Académica (Paso 4 del Wizard)
            // Oferta: Conecta con Carrera, Turno y Periodo Académico
            $table->foreignId('admission_offering_id')->nullable()->constrained('admission_offerings')->onDelete('set null');

            // Modalidad: Conecta con Tipo de Examen (Ordinario/Extraordinario) y Modalidad específica
            $table->foreignId('admission_modality_id')->nullable()->constrained('admission_modalities')->onDelete('set null');

            // 5. Datos de Pago (Paso 4 del Wizard)
            $table->foreignId('financial_entity_id')->nullable()->constrained('financial_entities')->onDelete('set null');
            $table->string('payment_operation_code', 50)->nullable(); // Código de operación del voucher

            // 6. Datos de Gestión Interna
            $table->string('code', 20)->nullable()->unique(); // Código de postulante es sumuenro de dni
            $table->decimal('exam_score', 5, 2)->nullable(); // Nota del examen
            $table->integer('merit_position')->nullable(); // Puesto en el ranking

            $table->enum('application_status',
                ['registrado', 'evaluado', 'aprobado', 'sin_vacante', 'cancelado'])
                ->default('registrado');

            // Control del Wizard (para saber si se quedó en el paso 1, 2, 3 o completó)
            $table->integer('registration_step')->default(1);

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};

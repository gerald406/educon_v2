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
        Schema::table('students', function (Blueprint $table) {
            // Datos personales complementarios (que no están en users)
            $table->string('phone')->nullable()->after('academic_status');
            $table->string('address')->nullable()->after('phone');
            $table->enum('gender', ['masculino', 'femenino'])->nullable()->after('address');
            $table->date('birthday')->nullable()->after('gender');

            // Ubigeo Nacimiento
            $table->string('ubigeo_birth_id', 10)->nullable()->after('birthday');
            $table->foreign('ubigeo_birth_id')->references('iddist')->on('locations')->onDelete('set null');

            // Colegio Procedencia
            $table->foreignId('origin_school_id')->nullable()->constrained('origin_schools')->onDelete('set null')->after('ubigeo_birth_id');
            $table->year('school_graduation_year')->nullable()->after('origin_school_id');

            // Foto (Copiaremos la ruta aquí)
            $table->string('photo_url', 255)->nullable()->after('school_graduation_year');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'gender', 'birthday', 'ubigeo_birth_id', 'origin_school_id', 'school_graduation_year', 'photo_url']);
        });
    }
};

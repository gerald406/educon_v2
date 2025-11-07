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
        Schema::create('payment_concepts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); 
            $table->string('tupa_code', 20)->nullable()->unique(); 
            $table->string('description', 200); 
            $table->decimal('amount', 10, 2); 
            $table->enum('concept_type', ['enrollment', 'tuition', 'certificate', 'statement', 'fee', 'other']);
            $table->boolean('is_taxable')->default(false);
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->string('sunat_service_code', 50)->nullable(); 
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('discount_applicable')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_concepts');
    }
};

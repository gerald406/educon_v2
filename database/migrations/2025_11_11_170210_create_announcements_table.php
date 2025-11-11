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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('content');
            
            $table->enum('announcement_type', ['news', 'announcement', 'event', 'notice', 'urgent']);
            
            // A quién va dirigido
            $table->enum('target_audience', ['all', 'students', 'teachers'])->default('all');
            
            $table->dateTime('publish_date');
            $table->dateTime('expiration_date')->nullable();
            
            $table->string('attachment_url')->nullable();
            
            // Quién lo publicó (un admin, coordinador, etc.)
            $table->foreignId('published_by_user_id')->constrained('users')->onDelete('cascade');
            
            $table->boolean('is_featured')->default(false); // Para fijarlo en el dashboard
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

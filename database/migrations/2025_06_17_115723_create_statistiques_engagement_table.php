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
        Schema::create('statistiques_engagement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('action'); // 'login', 'view_opportunity', 'apply', 'attend_event', etc.
            $table->string('entite_type')->nullable(); // 'opportunity', 'event', 'offer', 'conversation', etc.
            $table->unsignedBigInteger('entite_id')->nullable();
            $table->json('metadonnees')->nullable();
            
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistiques_engagement');
    }
};

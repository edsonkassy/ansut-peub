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
        Schema::create('interactions_ia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('type_interaction', ['orientation', 'recommendation', 'analyse', 'conseil']);
            $table->text('question');
            $table->text('reponse');
            $table->json('contexte')->nullable(); // Données contextuelles pour l'IA
            $table->integer('satisfaction')->nullable(); // Note de 1 à 5
            
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions_ia');
    }
};

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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            
            $table->enum('expediteur_type', ['bachelier', 'partenaire']);
            $table->unsignedBigInteger('expediteur_id'); // ID du bachelier ou partenaire
            $table->text('contenu');
            $table->json('fichiers_joints')->nullable();
            $table->boolean('lu')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

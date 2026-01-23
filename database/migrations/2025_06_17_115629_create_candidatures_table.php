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
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bachelier_id')->constrained()->onDelete('cascade');
            $table->foreignId('opportunite_id')->constrained()->onDelete('cascade');
            
            $table->enum('type_interaction', ['candidature', 'inscription', 'utilisation'])->default('candidature');
            $table->text('lettre_motivation')->nullable();
            $table->json('documents_joints')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected', 'participated'])->default('pending');
            $table->timestamp('date_soumission')->useCurrent();
            $table->timestamp('date_reponse')->nullable();
            $table->text('commentaire_partenaire')->nullable();
            $table->integer('score_matching')->nullable(); // Score IA de compatibilité
            $table->integer('evaluation_experience')->nullable(); // Note de 1 à 5 pour événements
            $table->text('commentaire_evaluation')->nullable();
            $table->string('certificat_obtenu')->nullable(); // Pour formations/événements
            $table->string('code_utilise')->nullable(); // Pour offres spéciales
            
            $table->timestamps();
            
            // Index unique pour éviter les doublons
            $table->unique(['bachelier_id', 'opportunite_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};

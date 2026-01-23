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
        Schema::create('dotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bachelier_id')->constrained()->onDelete('cascade');
            
            $table->enum('type_dotation', ['ordinateur_portable', 'connexion_internet', 'abonnement_ia_premium']);
            $table->string('nom_dotation');
            $table->text('description')->nullable();
            $table->decimal('valeur_monetaire', 10, 2)->nullable();
            $table->integer('quantite')->default(1);
            $table->date('date_attribution');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->enum('status', ['active', 'suspendue', 'terminee', 'en_attente'])->default('en_attente');
            $table->string('fournisseur')->nullable(); // Nom du partenaire fournisseur
            $table->string('numero_serie')->nullable(); // Pour matériel
            $table->text('conditions_utilisation')->nullable();
            
            $table->timestamps();
            
            // Note: La contrainte pour vérifier que bachelier.boursier_peub = true
            // sera implémentée au niveau de l'application ou via un trigger de base de données
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dotations');
    }
};

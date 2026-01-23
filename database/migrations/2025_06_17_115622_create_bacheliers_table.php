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
        Schema::create('bacheliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            // Informations personnelles (etape 1)
            $table->string('nom');
            $table->string('prenoms');
            $table->date('date_naissance');
            $table->string('lieu_naissance');
            $table->enum('sexe', ['M', 'F']);
            $table->enum('piece_identite_type', ['carte_scolaire', 'cni', 'attestation'])->nullable();
            $table->string('piece_identite_file')->nullable();
            $table->string('telephone_eleve');
            $table->string('telephone_parent');
            $table->string('email_eleve'); // sera automatiquement le mail de l'utilisateur
            $table->string('email_parent');
            $table->string('region');
            $table->string('commune');
            
            // Informations académiques (etape 2)
            $table->string('matricule_bac')->unique();
            $table->string('serie_bac');
            $table->decimal('note_bac', 6, 2)->comment('Note sur 400 points');
            $table->enum('mention', ['passable', 'assez_bien', 'bien', 'tres_bien'])->nullable();
            $table->string('etablissement_nom');
            $table->enum('etablissement_type', ['public', 'prive_homologue', 'prive_non_homologue']);
            $table->string('collante_bac_file');
            $table->year('annee_bac');
            
            // Informations socio-économiques (etape 3)
            $table->boolean('pensionnaire_internat')->default(false);
            $table->boolean('bourse_scolaire_lycee')->default(false);
            $table->string('profession_pere')->nullable();
            $table->string('profession_mere')->nullable();
            $table->json('situations_particulieres')->nullable(); // handicap, orphelin, etc.
            $table->boolean('possede_ordinateur')->default(false);
            $table->enum('connexion_internet', ['aucune', '3g_4g', 'fibre']);
            $table->boolean('acces_smartphone')->default(false);
            $table->boolean('acces_ia')->default(false);
            
            // Motivations (etape 4)
            $table->text('motivation')->nullable();           

            // Statut dans le programme
            $table->boolean('boursier_peub')->default(false);
            $table->date('date_integration_peub')->nullable();
            $table->enum('status_candidature', ['en_attente', 'accepte', 'refuse', 'en_cours_evaluation'])->default('en_attente');
            $table->enum('status_profil', ['incomplet', 'complet', 'verifie'])->default('incomplet');
            $table->date('date_verification')->nullable();
            
            // Informations complémentaires
            $table->text('bio')->nullable();
            $table->json('competences')->nullable();
            $table->json('langues')->nullable();
            $table->string('photo')->nullable();
            $table->string('cv_path')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bacheliers');
    }
};

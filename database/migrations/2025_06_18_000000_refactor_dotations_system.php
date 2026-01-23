<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table des fournisseurs (commune à tous les types)
        Schema::create('dotations_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('contact_email')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->enum('status', ['active', 'suspendu', 'archive'])->default('active');
            $table->timestamps();
        });

        // 2. Table principale de l'inventaire (commune et harmonisée)
        Schema::create('dotations_inventaire', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code_interne')->unique();
            $table->enum('type_dotation', ['ordinateur_portable', 'connexion_internet', 'abonnement_ia']);
            $table->text('description')->nullable();
            
            // Champs financiers communs
            $table->decimal('valeur_unitaire', 10, 2);
            $table->decimal('prix_mensuel', 8, 2)->nullable(); // Pour abonnements
            
            // Gestion stock commune
            $table->integer('stock_total')->default(0);
            $table->integer('stock_disponible')->default(0);
            $table->integer('stock_attribue')->default(0);
            $table->integer('stock_minimum')->default(0);
            
            // Informations fournisseur
            $table->foreignId('fournisseur_id')->nullable()->constrained('dotations_fournisseurs');
            $table->date('date_achat')->nullable();
            
            // Champs spécifiques harmonisés
            $table->string('marque')->nullable(); // Marque (ordinateur) ou Opérateur (connexion) ou Plateforme (IA)
            $table->string('modele')->nullable(); // Modèle (ordinateur) ou Plan (connexion/IA)
            $table->string('caracteristiques')->nullable(); // Caractéristiques techniques condensées
            $table->string('duree_validite')->nullable(); // Durée d'utilisation/validité
            
            // Statut commun
            $table->enum('status', ['active', 'suspendu', 'archive'])->default('active');
            
            // Métadonnées flexibles pour infos spécifiques
            $table->json('metadata')->nullable();
            
            $table->timestamps();
        });

        // 3. Table des attributions (simplifiée et commune)
        Schema::create('dotations_attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bachelier_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventaire_id')->constrained('dotations_inventaire');
            
            // Identification commune
            $table->string('identifiant_unique')->nullable(); // Numéro série, code activation, etc.
            
            // Dates communes
            $table->date('date_attribution');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            
            // Statut commun
            $table->enum('status', ['active', 'suspendue', 'terminee', 'en_attente', 'retournee'])->default('en_attente');
            
            // Traçabilité
            $table->foreignId('attribue_par')->nullable()->constrained('users');
            $table->timestamp('date_activation')->nullable();
            $table->timestamp('date_suspension')->nullable();
            $table->text('raison_suspension')->nullable();
            
            // Données spécifiques si nécessaire
            $table->json('donnees_specifiques')->nullable();
            
            $table->timestamps();
        });

        // 4. Table des mouvements de stock (commune)
        Schema::create('dotations_mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaire_id')->constrained('dotations_inventaire');
            $table->enum('type_mouvement', ['entree', 'sortie', 'retour', 'ajustement']);
            $table->integer('quantite');
            $table->string('motif');
            $table->text('commentaire')->nullable();
            $table->foreignId('effectue_par')->constrained('users');
            $table->foreignId('attribution_id')->nullable()->constrained('dotations_attributions');
            $table->timestamps();
        });

        // 5. Migration des données existantes
        $this->migrateDonneesDotations();

        // 6. Supprimer l'ancienne table après migration
        Schema::dropIfExists('dotations');
    }

    /**
     * Migrer les données de l'ancienne table vers la nouvelle structure
     */
    private function migrateDonneesDotations(): void
    {
        // Vérifier si l'ancienne table existe et contient des données
        if (!Schema::hasTable('dotations')) {
            return;
        }

        $anciennesDotations = DB::table('dotations')->get();

        foreach ($anciennesDotations as $ancienneDotation) {
            // Créer ou récupérer le fournisseur
            $fournisseurId = null;
            if ($ancienneDotation->fournisseur) {
                $fournisseur = DB::table('dotations_fournisseurs')
                    ->where('nom', $ancienneDotation->fournisseur)
                    ->first();
                
                if (!$fournisseur) {
                    $fournisseurId = DB::table('dotations_fournisseurs')->insertGetId([
                        'nom' => $ancienneDotation->fournisseur,
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $fournisseurId = $fournisseur->id;
                }
            }

            // Créer l'entrée dans l'inventaire
            $inventaireId = DB::table('dotations_inventaire')->insertGetId([
                'nom' => $ancienneDotation->nom_dotation,
                'code_interne' => 'DOT-' . str_pad($ancienneDotation->id, 6, '0', STR_PAD_LEFT),
                'type_dotation' => $ancienneDotation->type_dotation,
                'description' => $ancienneDotation->description,
                'valeur_unitaire' => $ancienneDotation->valeur_monetaire ?? 0,
                'stock_total' => $ancienneDotation->quantite,
                'stock_disponible' => 0, // Déjà attribué
                'stock_attribue' => $ancienneDotation->quantite,
                'stock_minimum' => 0,
                'fournisseur_id' => $fournisseurId,
                'date_achat' => $ancienneDotation->date_attribution, // Approximation
                'status' => $ancienneDotation->status === 'active' ? 'active' : 'suspendu',
                'created_at' => $ancienneDotation->created_at,
                'updated_at' => $ancienneDotation->updated_at,
            ]);

            // Créer l'attribution
            DB::table('dotations_attributions')->insert([
                'bachelier_id' => $ancienneDotation->bachelier_id,
                'inventaire_id' => $inventaireId,
                'identifiant_unique' => $ancienneDotation->numero_serie,
                'date_attribution' => $ancienneDotation->date_attribution,
                'date_debut' => $ancienneDotation->date_debut,
                'date_fin' => $ancienneDotation->date_fin,
                'status' => $ancienneDotation->status,
                'donnees_specifiques' => json_encode([
                    'conditions_utilisation' => $ancienneDotation->conditions_utilisation,
                ]),
                'created_at' => $ancienneDotation->created_at,
                'updated_at' => $ancienneDotation->updated_at,
            ]);

            // Créer le mouvement de stock pour l'attribution
            DB::table('dotations_mouvements_stock')->insert([
                'inventaire_id' => $inventaireId,
                'type_mouvement' => 'sortie',
                'quantite' => $ancienneDotation->quantite,
                'motif' => 'Attribution initiale - Migration',
                'commentaire' => 'Données migrées depuis l\'ancienne table dotations',
                'effectue_par' => 1, // Admin par défaut
                'created_at' => $ancienneDotation->created_at,
                'updated_at' => $ancienneDotation->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer l'ancienne table dotations
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
            $table->string('fournisseur')->nullable();
            $table->string('numero_serie')->nullable();
            $table->text('conditions_utilisation')->nullable();
            $table->timestamps();
        });

        // Supprimer les nouvelles tables
        Schema::dropIfExists('dotations_mouvements_stock');
        Schema::dropIfExists('dotations_attributions');
        Schema::dropIfExists('dotations_inventaire');
        Schema::dropIfExists('dotations_fournisseurs');
    }
}; 
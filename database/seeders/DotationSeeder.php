<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DotationFournisseur;
use App\Models\DotationInventaire;
use App\Models\DotationMouvementStock;
use App\Models\Bachelier;
use App\Models\Dotation;

class DotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des fournisseurs ivoiriens
        $ciFournisseur1 = DotationFournisseur::create([
            'nom' => 'SITELCI',
            'contact_email' => 'contact@sitelci.ci',
            'contact_telephone' => '+225 27 22 00 11 22',
            'status' => 'active'
        ]);

        $ciFournisseur2 = DotationFournisseur::create([
            'nom' => 'Orange Côte d\'Ivoire',
            'contact_email' => 'serviceclient@orange.ci',
            'contact_telephone' => '+225 07 07 07 07 07',
            'status' => 'active'
        ]);

        $openAiFournisseur = DotationFournisseur::create([
            'nom' => 'OpenAI',
            'contact_email' => 'business@openai.com',
            'contact_telephone' => null,
            'status' => 'active'
        ]);

        // Créer des inventaires d'ordinateurs portables
        $laptopDell = DotationInventaire::create([
            'nom' => 'Dell Latitude 3520',
            'code_interne' => 'DELL-LAT-3520',
            'type_dotation' => 'ordinateur_portable',
            'description' => 'Ordinateur portable Dell Latitude 3520 pour étudiants',
            'valeur_unitaire' => 450000,
            'prix_mensuel' => null,
            'stock_total' => 50,
            'stock_disponible' => 50,
            'stock_attribue' => 0,
            'stock_minimum' => 10,
            'fournisseur_id' => $ciFournisseur1->id,
            'date_achat' => now()->subDays(30),
            'marque' => 'Dell',
            'modele' => 'Latitude 3520',
            'caracteristiques' => 'Intel i5, 8GB RAM, 256GB SSD, Windows 11',
            'duree_validite' => '36 mois',
            'status' => 'active',
            'metadata' => [
                'processeur' => 'Intel Core i5-1135G7',
                'ram' => '8GB DDR4',
                'stockage' => '256GB SSD',
                'ecran' => '15.6" Full HD',
                'garantie_mois' => 36,
                'couleur' => 'Noir',
                'poids' => '1.79 kg'
            ]
        ]);

        $laptopHP = DotationInventaire::create([
            'nom' => 'HP Pavilion 15',
            'code_interne' => 'HP-PAV-15',
            'type_dotation' => 'ordinateur_portable',
            'description' => 'Ordinateur portable HP Pavilion 15 pour étudiants',
            'valeur_unitaire' => 380000,
            'prix_mensuel' => null,
            'stock_total' => 30,
            'stock_disponible' => 30,
            'stock_attribue' => 0,
            'stock_minimum' => 5,
            'fournisseur_id' => $ciFournisseur1->id, // On peut réutiliser le fournisseur
            'date_achat' => now()->subDays(15),
            'marque' => 'HP',
            'modele' => 'Pavilion 15',
            'caracteristiques' => 'AMD Ryzen 5, 8GB RAM, 512GB SSD, Windows 11',
            'duree_validite' => '24 mois',
            'status' => 'active',
            'metadata' => [
                'processeur' => 'AMD Ryzen 5 5500U',
                'ram' => '8GB DDR4',
                'stockage' => '512GB SSD',
                'ecran' => '15.6" Full HD',
                'garantie_mois' => 24,
                'couleur' => 'Argent',
                'poids' => '1.75 kg'
            ]
        ]);

        // Créer des inventaires de connexions internet
        $mtnData5GB = DotationInventaire::create([
            'nom' => 'Orange Data 5GB/mois',
            'code_interne' => 'ORANGE-DATA-5GB',
            'type_dotation' => 'connexion_internet',
            'description' => 'Forfait Orange 5GB de données par mois',
            'valeur_unitaire' => 15000,
            'prix_mensuel' => 15000,
            'stock_total' => 500,
            'stock_disponible' => 500,
            'stock_attribue' => 0,
            'stock_minimum' => 50,
            'fournisseur_id' => $ciFournisseur2->id,
            'date_achat' => now()->subDays(5),
            'marque' => 'Orange',
            'modele' => 'Data 5GB',
            'caracteristiques' => '5GB/mois, SMS inclus, appels gratuits Orange',
            'duree_validite' => '1 mois',
            'status' => 'active',
            'metadata' => [
                'data_volume' => '5GB',
                'sms_inclus' => true,
                'appels_inclus' => true,
                'reseau' => '4G/5G',
                'renouvellement_auto' => true,
                'validite_jours' => 30
            ]
        ]);

        $mtnDataIllimite = DotationInventaire::create([
            'nom' => 'Orange Data Illimité',
            'code_interne' => 'ORANGE-DATA-ILLIMITE',
            'type_dotation' => 'connexion_internet',
            'description' => 'Forfait Orange données illimitées',
            'valeur_unitaire' => 45000,
            'prix_mensuel' => 45000,
            'stock_total' => 100,
            'stock_disponible' => 100,
            'stock_attribue' => 0,
            'stock_minimum' => 20,
            'fournisseur_id' => $ciFournisseur2->id,
            'date_achat' => now()->subDays(2),
            'marque' => 'Orange',
            'modele' => 'Data Illimité',
            'caracteristiques' => 'Données illimitées, SMS inclus, appels gratuits Orange',
            'duree_validite' => '1 mois',
            'status' => 'active',
            'metadata' => [
                'data_volume' => 'illimite',
                'sms_inclus' => true,
                'appels_inclus' => true,
                'reseau' => '4G/5G',
                'renouvellement_auto' => true,
                'validite_jours' => 30
            ]
        ]);

        // Créer des inventaires d'abonnements IA
        $gptPlus = DotationInventaire::create([
            'nom' => 'ChatGPT Plus',
            'code_interne' => 'OPENAI-GPT-PLUS',
            'type_dotation' => 'abonnement_ia',
            'description' => 'Abonnement ChatGPT Plus pour étudiants',
            'valeur_unitaire' => 12000,
            'prix_mensuel' => 12000,
            'stock_total' => 200,
            'stock_disponible' => 200,
            'stock_attribue' => 0,
            'stock_minimum' => 30,
            'fournisseur_id' => $openAiFournisseur->id,
            'date_achat' => now()->subDays(7),
            'marque' => 'OpenAI',
            'modele' => 'GPT-4 Plus',
            'caracteristiques' => 'GPT-4, 40 requêtes/3h, réponses prioritaires',
            'duree_validite' => '1 mois',
            'status' => 'active',
            'metadata' => [
                'modele_ia' => 'GPT-4',
                'limite_requetes' => 40,
                'limite_periode' => '3 heures',
                'fonctionnalites' => ['chat', 'code', 'analyse', 'images'],
                'reponse_prioritaire' => true,
                'acces_plugins' => true
            ]
        ]);

        $claudePro = DotationInventaire::create([
            'nom' => 'Claude Pro',
            'code_interne' => 'ANTHROPIC-CLAUDE-PRO',
            'type_dotation' => 'abonnement_ia',
            'description' => 'Abonnement Claude Pro pour étudiants',
            'valeur_unitaire' => 12000,
            'prix_mensuel' => 12000,
            'stock_total' => 100,
            'stock_disponible' => 100,
            'stock_attribue' => 0,
            'stock_minimum' => 20,
            'fournisseur_id' => $openAiFournisseur->id, // On peut réutiliser
            'date_achat' => now()->subDays(3),
            'marque' => 'Anthropic',
            'modele' => 'Claude Pro',
            'caracteristiques' => 'Claude 3, 5x plus de messages, réponses prioritaires',
            'duree_validite' => '1 mois',
            'status' => 'active',
            'metadata' => [
                'modele_ia' => 'Claude 3',
                'limite_messages' => '5x normal',
                'fonctionnalites' => ['chat', 'analyse', 'documents'],
                'reponse_prioritaire' => true,
                'analyse_documents' => true
            ]
        ]);

        // Créer des mouvements de stock d'entrée pour tous les inventaires
        $inventaires = [
            $laptopDell, $laptopHP, $mtnData5GB, 
            $mtnDataIllimite, $gptPlus, $claudePro
        ];

        foreach ($inventaires as $inventaire) {
            DotationMouvementStock::creerEntree(
                $inventaire->id,
                $inventaire->stock_total,
                'Stock initial',
                'Ajout du stock initial lors de la création',
                1 // ID utilisateur admin par défaut
            );
        }

        // Ajout d'attributions de test
        $bachelier1 = Bachelier::where('email_eleve', 'kouame.jean@gmail.com')->first();
        $bachelier2 = Bachelier::where('email_eleve', 'traore.fatou@yahoo.fr')->first();
        if ($bachelier1) {
            Dotation::creerPourBachelier(
                $bachelier1->id,
                $laptopDell->id,
                [
                    'attribue_par' => 1,
                    'identifiant_unique' => 'DELL-' . strtoupper(uniqid()),
                    'date_debut' => now(),
                    'date_fin' => now()->addYears(3),
                    'status' => 'active',
                    'donnees_specifiques' => [
                        'numero_serie' => 'DELL123456',
                        'conditions_utilisation' => 'Usage éducatif, maintenance annuelle requise'
                    ]
                ]
            );
            Dotation::creerPourBachelier(
                $bachelier1->id,
                $gptPlus->id,
                [
                    'attribue_par' => 1,
                    'identifiant_unique' => 'GPT-' . strtoupper(uniqid()),
                    'date_debut' => now(),
                    'date_fin' => now()->addMonth(),
                    'status' => 'active',
                    'donnees_specifiques' => [
                        'login' => 'kouamejean@peub.ci',
                        'conditions_utilisation' => 'Usage académique uniquement'
                    ]
                ]
            );
        }
        if ($bachelier2) {
            Dotation::creerPourBachelier(
                $bachelier2->id,
                $mtnData5GB->id,
                [
                    'attribue_par' => 1,
                    'identifiant_unique' => 'MTN-' . strtoupper(uniqid()),
                    'date_debut' => now(),
                    'date_fin' => now()->addMonth(),
                    'status' => 'active',
                    'donnees_specifiques' => [
                        'numero_ligne' => '221771234567',
                        'conditions_utilisation' => 'Usage mensuel, renouvellement automatique'
                    ]
                ]
            );
        }

        $this->command->info('Seeder DotationSeeder terminé avec succès !');
        $this->command->info('Créé :');
        $this->command->info('- 3 fournisseurs');
        $this->command->info('- 6 inventaires (2 ordinateurs, 2 connexions, 2 abonnements IA)');
        $this->command->info('- 6 mouvements de stock d\'entrée');
        $this->command->info('- 3 attributions de dotations');
    }
} 
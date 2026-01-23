<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bachelier;
use Carbon\Carbon;

class BachelierSeeder extends Seeder
{
    /**
     * Helper method to create user only if email doesn't exist
     */
    private function createUserIfNotExists(array $userData)
    {
        $existingUser = User::where('email', $userData['email'])->first();
        if ($existingUser) {
            $this->command->info("⚠️  Email {$userData['email']} already exists, skipping...");
            return $existingUser;
        }
        
        $newUser = User::create($userData);
        $this->command->info("✅ User created: {$userData['email']}");
        return $newUser;
    }

    /**
     * Helper method to create bachelier only if user doesn't have one already
     */
    private function createBachelierIfNotExists($user, array $bachelierData)
    {
        $existingBachelier = Bachelier::where('user_id', $user->id)->first();
        if ($existingBachelier) {
            $this->command->info("⚠️  Bachelier profile for user {$user->email} already exists, skipping...");
            return $existingBachelier;
        }
        
        // Add default values for required fields if not provided
        $defaults = [
            'piece_identite_file' => 'default_cni.pdf',
            'telephone_parent' => '+2250700000000',
            'email_parent' => 'parent@example.com', 
            'collante_bac_file' => 'default_collante.pdf',
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false
        ];
        
        foreach ($defaults as $key => $value) {
            if (!isset($bachelierData[$key])) {
                $bachelierData[$key] = $value;
            }
        }
        
        $newBachelier = Bachelier::create($bachelierData);
        $this->command->info("✅ Bachelier profile created for: {$user->email}");
        return $newBachelier;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Premier bachelier - Fatou Diallo
        $this->createBachelierFatou();
        
        // Deuxième bachelier - Koffi Kouassi
        $this->createBachelierKoffi();

        // Troisième bachelier - Alex Degny
        $this->createBachelierAlex();

        // Quatrième bachelier - Serge Kokoua
        $this->createBachelierSerge();

        // Cinquième bachelier - Marc Kouassi
        $this->createBachelierMarc();

        // 30 nouveaux bacheliers réalistes
        $this->createBachelierAissata();
        $this->createBachelierYves();
        $this->createBachelierMariam();
        $this->createBachelierKouadio();
        $this->createBachelierFatoumata();
        $this->createBachelierJean();
        $this->createBachelierAkissi();
        $this->createBachelierAbdoul();
        $this->createBachelierBinta();
        $this->createBachelierPatrick();
        $this->createBachelierAminata();
        $this->createBachelierEmmanuel();
        $this->createBachelierCeline();
        $this->createBachelierIbrahim();
        $this->createBachelierNancy();
        $this->createBachelierMamadou();
        $this->createBachelierGrace();
        $this->createBachelierOusmane();
        $this->createBachelierMarie();
        $this->createBachelierAdama();
        $this->createBachelierEsther();
        $this->createBachelierSidiki();
        $this->createBachelierRose();
        $this->createBachelierAlassane();
        $this->createBachelierVirginie();
        $this->createBachelierIssiaka();
        $this->createBachelierJuliette();
        $this->createBachelierSekou();
        $this->createBachelierChristine();
        $this->createBachelierBakary();

        // 65 profils supplémentaires pour atteindre 100 profils
        $this->createBachelierSalimata();
        $this->createBachelierYoussou();
        $this->createBachelierNatasha();
        $this->createBachelierSouleymane();
        $this->createBachelierAya();
        $this->createBachelierKouame();
        $this->createBachelierRachelle();
        $this->createBachelierIbrahima();
        $this->createBachelierLorraine();
        $this->createBachelierMoussa();
        $this->createBachelierBrigitte();
        $this->createBachelierElhadji();
        $this->createBachelierJoelle();
        $this->createBachelierDaouda();
        $this->createBachelierCaroline();
        $this->createBachelierLassana();
        $this->createBachelierSandra();
        $this->createBachelierTiemoko();
        $this->createBachelierAngelique();
        $this->createBachelierDrissa();
        $this->createBachelierConstance();
        $this->createBachelierSita();
        $this->createBachelierFrancis();
        $this->createBachelierNdeye();
        $this->createBachelierSeydou();
        $this->createBachelierMireille();
        $this->createBachelierBoubacar();
        $this->createBachelierBernadette();
        $this->createBachelierFofie();
        $this->createBachelierMelanie();
        $this->createBachelierDjakaridja();
        $this->createBachelierClementine();
        $this->createBachelierNoufou();
        $this->createBachelierPhilomene();
        $this->createBachelierBruno();
        $this->createBachelierMohamed();
        $this->createBachelierLaetitia();
        $this->createBachelierSanogo();
        $this->createBachelierPerle();
        $this->createBachelierMamadou2();
        $this->createBachelierVictoire();
        $this->createBachelierClement();
        $this->createBachelierDjeneba();
        $this->createBachelierAmadou();
        $this->createBachelierNatacha();
        $this->createBachelierAbou();
        $this->createBachelierRegina();
        $this->createBachelierKarimu();
        $this->createBachelierSylvie();
        $this->createBachelierOmar();
        $this->createBachelierKadiatou();
        $this->createBachelierWoury();
        $this->createBachelierAristide();
        $this->createBachelierCamille();
        $this->createBachelierGodwin();
        $this->createBachelierFanta();
        $this->createBachelierDenis();
        $this->createBachelierJosette();
        $this->createBachelierAlbert();
        $this->createBachelierLinda();
        $this->createBachelierGeoffroy();
        $this->createBachelierYamyness();
        $this->createBachelierTraore();
        $this->createBachelierCharlotte();
        $this->createBachelierFodé();
        
        // Ajout des profils spécifiques demandés
        $this->createBachelierThierry();
        $this->createBachelierBensouma();
    }

    private function createBachelierFatou()
    {
        // Créer un utilisateur pour le bachelier
        $user = $this->createUserIfNotExists([
            'email' => 'fatou.diallo@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Créer le profil bachelier
        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            
            // Informations personnelles
            'nom' => 'Diallo',
            'prenoms' => 'Fatou',
            'date_naissance' => '2005-03-15',
            'lieu_naissance' => 'Abidjan',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_fatou_diallo.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Diallo',
                'prenoms' => 'Fatou',
                'date_naissance' => '2005-03-15',
                'lieu_naissance' => 'Abidjan',
                'numero' => 'CI-123456789-01'
            ],
            'telephone_eleve' => '+2250701234567',
            'telephone_parent' => '+2250701234568',
            'email_eleve' => 'fatou.diallo@example.com',
            'email_parent' => 'papa.diallo@example.com',
            'region' => 'Abidjan',
            'commune' => 'Cocody',
            
            // Informations académiques
            'matricule_bac' => '2024-ABJ-001234',
            'serie_bac' => 'D',
            'note_bac' => 16.75,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Sainte Marie de Cocody',
            'etablissement_type' => 'prive_homologue',
            'collante_bac_file' => 'collante_bac_fatou_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-ABJ-001234',
                'serie' => 'D',
                'note' => 16.75,
                'mention' => 'bien',
                'etablissement' => 'Lycée Sainte Marie de Cocody',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            
            // Informations socio-économiques
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Enseignant',
            'profession_mere' => 'Infirmière',
            'situations_particulieres' => ['boursier_lycee'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            
            // Motivations
            'motivation' => "Je suis passionnée par les sciences et j'aspire à devenir médecin pour contribuer à l'amélioration de la santé en Côte d'Ivoire. Mon parcours scolaire a été marqué par de nombreux défis financiers, mais ma détermination et le soutien de mes parents m'ont permis de réussir mon baccalauréat avec mention bien. Le programme PEUB représente pour moi une opportunité unique de poursuivre mes études supérieures et de réaliser mon rêve de servir ma communauté à travers la médecine. Je suis convaincue que l'éducation est la clé du développement de notre pays et je souhaite participer activement à ce processus.",
            'motivation_ai_score' => 8.5,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.85,
                'themes' => ['medecine', 'service_communautaire', 'determination'],
                'score_global' => 8.5
            ],
            
            // Statut dans le programme
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-15',
            
            // Informations complémentaires
            'bio' => "Étudiante passionnée par les sciences médicales, je vise une carrière en médecine pour contribuer au développement sanitaire de la Côte d'Ivoire.",
            'competences' => [
                'Biologie',
                'Chimie',
                'Physique',
                'Mathématiques',
                'Français',
                'Anglais'
            ],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Dioula' => 'Maternelle'
            ],
            'photo' => 'photo_fatou_diallo.jpg',
            'cv_path' => 'cv_fatou_diallo.pdf',
            
            // Scoring PEUB
            'score_academique' => 85.50,
            'score_geographique' => 78.25,
            'score_socio_economique' => 92.00,
            'score_motivations' => 88.75,
            'score_final_peub' => 86.13,
            'rang_peub' => 156,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 16.75,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 85.50
                ],
                'geographique' => [
                    'region' => 'Abidjan',
                    'commune' => 'Cocody',
                    'score_region' => 75.0,
                    'bonus_commune' => 3.25,
                    'score_calcule' => 78.25
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'enseignant',
                    'profession_mere' => 'infirmiere',
                    'situations_particulieres' => ['boursier_lycee'],
                    'score_calcule' => 92.00
                ],
                'motivations' => [
                    'score_ia' => 8.5,
                    'longueur_texte' => 250,
                    'themes_identifies' => 3,
                    'score_calcule' => 88.75
                ]
            ],
            'date_calcul_scoring' => now(),
            
            // Métadonnées d'extraction IA
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.92,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 45.2
            ]
        ]);

        // Ajout d'un parcours universitaire d'exemple
        $bachelier->parcoursUniversitaires()->create([
            'universite_nom' => 'Université Félix Houphouët-Boigny',
            'pays' => 'Côte d\'Ivoire',
            'niveau' => 'licence',
            'annee_academique' => '2024-2025',
            'performance' => 15.50,
            'mention' => 'bien',
            'attestation_admission_file' => 'attestation_admission_fatou_2024.pdf',
            'extracted_data' => [
                'date_admission' => '2024-09-01',
                'filiere' => 'Médecine'
            ],
            'statut' => 'en_cours',
        ]);

        $this->command->info('✅ Profil bachelier Fatou Diallo créé avec succès!');
        $this->command->info('📧 Email: ' . $bachelier->email_eleve);
        $this->command->info('🎯 Score PEUB: ' . $bachelier->score_final_peub . '/100');
        $this->command->info('🏆 Rang PEUB: ' . $bachelier->rang_peub);
    }

    private function createBachelierKoffi()
    {
        // Créer un utilisateur pour le bachelier
        $user = $this->createUserIfNotExists([
            'email' => 'koffi.kouassi@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Créer le profil bachelier
        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            
            // Informations personnelles
            'nom' => 'Kouassi',
            'prenoms' => 'Koffi',
            'date_naissance' => '2004-08-22',
            'lieu_naissance' => 'Bouaké',
            'sexe' => 'M',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'carte_scolaire_koffi.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Kouassi',
                'prenoms' => 'Koffi',
                'date_naissance' => '2004-08-22',
                'lieu_naissance' => 'Bouaké',
                'numero' => 'CS-987654321-02'
            ],
            'telephone_eleve' => '+2250709876543',
            'telephone_parent' => '+2250709876544',
            'email_eleve' => 'koffi.kouassi@example.com',
            'email_parent' => 'maman.kouassi@example.com',
            'region' => 'Vallée du Bandama',
            'commune' => 'Bouaké',
            
            // Informations académiques
            'matricule_bac' => '2024-BKE-005678',
            'serie_bac' => 'C',
            'note_bac' => 18.25,
            'mention' => 'tres_bien',
            'etablissement_nom' => 'Lycée Moderne de Bouaké',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_koffi_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-BKE-005678',
                'serie' => 'C',
                'note' => 18.25,
                'mention' => 'tres_bien',
                'etablissement' => 'Lycée Moderne de Bouaké',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            
            // Informations socio-économiques
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Agriculteur',
            'profession_mere' => 'Commerçante',
            'situations_particulieres' => ['orphelin_pere'],
            'possede_ordinateur' => true,
            'connexion_internet' => 'fibre',
            'acces_smartphone' => true,
            'acces_ia' => true,
            
            // Motivations
            'motivation' => "Passionné par les mathématiques et l'informatique depuis mon plus jeune âge, je rêve de devenir ingénieur en intelligence artificielle. Mon père, décédé il y a 3 ans, était agriculteur et m'a toujours encouragé à poursuivre mes études. Ma mère, commerçante, fait des sacrifices énormes pour m'offrir une éducation de qualité. J'ai obtenu mon baccalauréat avec mention très bien grâce à mon travail acharné et ma passion pour les sciences. Le programme PEUB me permettrait de réaliser mon rêve de contribuer au développement technologique de la Côte d'Ivoire et d'honorer la mémoire de mon père.",
            'motivation_ai_score' => 9.2,
            'motivation_ai_analysis' => [
                'sentiment' => 'tres_positif',
                'confiance' => 0.94,
                'themes' => ['informatique', 'ia', 'determination', 'honneur_familial'],
                'score_global' => 9.2
            ],
            
            // Statut dans le programme
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-10',
            
            // Informations complémentaires
            'bio' => "Étudiant brillant en mathématiques et informatique, je vise une carrière en intelligence artificielle pour moderniser l'agriculture ivoirienne.",
            'competences' => [
                'Mathématiques',
                'Physique',
                'Informatique',
                'Programmation Python',
                'Anglais',
                'Français'
            ],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Avancé',
                'Baoulé' => 'Maternelle'
            ],
            'photo' => 'photo_koffi_kouassi.jpg',
            'cv_path' => 'cv_koffi_kouassi.pdf',
            
            // Scoring PEUB
            'score_academique' => 95.25,
            'score_geographique' => 85.50,
            'score_socio_economique' => 88.75,
            'score_motivations' => 92.00,
            'score_final_peub' => 90.38,
            'rang_peub' => 23,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 18.25,
                    'mention' => 'tres_bien',
                    'bonus_mention' => 10.0,
                    'score_calcule' => 95.25
                ],
                'geographique' => [
                    'region' => 'Vallée du Bandama',
                    'commune' => 'Bouaké',
                    'score_region' => 80.0,
                    'bonus_commune' => 5.5,
                    'score_calcule' => 85.50
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'agriculteur',
                    'profession_mere' => 'commercante',
                    'situations_particulieres' => ['orphelin_pere'],
                    'score_calcule' => 88.75
                ],
                'motivations' => [
                    'score_ia' => 9.2,
                    'longueur_texte' => 280,
                    'themes_identifies' => 4,
                    'score_calcule' => 92.00
                ]
            ],
            'date_calcul_scoring' => now(),
            
            // Métadonnées d'extraction IA
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.89,
                'documents_traites' => ['carte_scolaire', 'collante_bac'],
                'temps_traitement' => 38.7
            ]
        ]);

        // Ajout d'un parcours universitaire d'exemple (international)
        $bachelier->parcoursUniversitaires()->create([
            'universite_nom' => 'Université de Montréal',
            'pays' => 'Canada',
            'niveau' => 'licence',
            'annee_academique' => '2024-2025',
            'performance' => 17.20,
            'mention' => 'très bien',
            'attestation_admission_file' => 'attestation_admission_koffi_2024.pdf',
            'extracted_data' => [
                'date_admission' => '2024-08-15',
                'filiere' => 'Informatique'
            ],
            'statut' => 'en_cours',
        ]);

        $this->command->info('✅ Profil bachelier Koffi Kouassi créé avec succès!');
        $this->command->info('📧 Email: ' . $bachelier->email_eleve);
        $this->command->info('🎯 Score PEUB: ' . $bachelier->score_final_peub . '/100');
        $this->command->info('🏆 Rang PEUB: ' . $bachelier->rang_peub);
    }

    private function createBachelierAlex()
    {
        // Créer un utilisateur pour le bachelier
        $user = $this->createUserIfNotExists([
            'email' => 'alexdegny@gmail.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Créer le profil bachelier
        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            
            // Informations personnelles
            'nom' => 'Degny',
            'prenoms' => 'Alex',
            'date_naissance' => '2005-05-10',
            'lieu_naissance' => 'Yamoussoukro',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_alex_degny.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Degny',
                'prenoms' => 'Alex',
                'date_naissance' => '2005-05-10',
                'lieu_naissance' => 'Yamoussoukro',
                'numero' => 'CI-456789123-03'
            ],
            'telephone_eleve' => '+2250707456789',
            'telephone_parent' => '+2250707456790',
            'email_eleve' => 'alexdegny@gmail.com',
            'email_parent' => 'parent.degny@example.com',
            'region' => 'Lacs',
            'commune' => 'Yamoussoukro',
            
            // Informations académiques
            'matricule_bac' => '2024-YAM-003456',
            'serie_bac' => 'C',
            'note_bac' => 17.50,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Scientifique de Yamoussoukro',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_alex_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-YAM-003456',
                'serie' => 'C',
                'note' => 17.50,
                'mention' => 'bien',
                'etablissement' => 'Lycée Scientifique de Yamoussoukro',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            
            // Informations socio-économiques
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Ingénieur',
            'profession_mere' => 'Professeur',
            'situations_particulieres' => [],
            'possede_ordinateur' => true,
            'connexion_internet' => 'fibre',
            'acces_smartphone' => true,
            'acces_ia' => true,
            
            // Motivations
            'motivation' => "Passionné par l'informatique et les nouvelles technologies, je souhaite devenir développeur full-stack pour contribuer à la transformation digitale de la Côte d'Ivoire. Mon projet est de créer des solutions innovantes pour faciliter l'accès à l'éducation dans les zones rurales.",
            'motivation_ai_score' => 8.8,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.88,
                'themes' => ['informatique', 'innovation', 'education'],
                'score_global' => 8.8
            ],
            
            // Statut dans le programme
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-20',
            
            // Informations complémentaires
            'bio' => "Développeur en herbe passionné par les technologies web et mobile.",
            'competences' => [
                'Programmation',
                'Mathématiques',
                'Physique',
                'Anglais',
                'Français'
            ],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Avancé'
            ],
            'photo' => 'photo_alex_degny.jpg',
            'cv_path' => 'cv_alex_degny.pdf',
            
            // Scoring PEUB
            'score_academique' => 88.75,
            'score_geographique' => 82.00,
            'score_socio_economique' => 75.50,
            'score_motivations' => 88.00,
            'score_final_peub' => 83.56,
            'rang_peub' => 187,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 17.50,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 88.75
                ],
                'geographique' => [
                    'region' => 'Lacs',
                    'commune' => 'Yamoussoukro',
                    'score_region' => 77.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 82.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'ingenieur',
                    'profession_mere' => 'professeur',
                    'situations_particulieres' => [],
                    'score_calcule' => 75.50
                ],
                'motivations' => [
                    'score_ia' => 8.8,
                    'longueur_texte' => 200,
                    'themes_identifies' => 3,
                    'score_calcule' => 88.00
                ]
            ],
            'date_calcul_scoring' => now(),
            
            // Métadonnées d'extraction IA
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.91,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 42.3
            ]
        ]);

        $this->command->info('✅ Profil bachelier Alex Degny créé avec succès!');
        $this->command->info('📧 Email: ' . $bachelier->email_eleve);
        $this->command->info('🎯 Score PEUB: ' . $bachelier->score_final_peub . '/100');
        $this->command->info('🏆 Rang PEUB: ' . $bachelier->rang_peub);
    }

    private function createBachelierSerge()
    {
        // Créer un utilisateur pour le bachelier
        $user = $this->createUserIfNotExists([
            'email' => 'kokouaserge3@gmail.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Créer le profil bachelier
        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            
            // Informations personnelles
            'nom' => 'Kokoua',
            'prenoms' => 'Serge',
            'date_naissance' => '2005-11-28',
            'lieu_naissance' => 'San-Pédro',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_serge_kokoua.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Kokoua',
                'prenoms' => 'Serge',
                'date_naissance' => '2005-11-28',
                'lieu_naissance' => 'San-Pédro',
                'numero' => 'CI-789123456-04'
            ],
            'telephone_eleve' => '+2250708912345',
            'telephone_parent' => '+2250708912346',
            'email_eleve' => 'kokouaserge3@gmail.com',
            'email_parent' => 'parent.kokoua@example.com',
            'region' => 'San-Pédro',
            'commune' => 'San-Pédro',
            
            // Informations académiques
            'matricule_bac' => '2024-SPD-004567',
            'serie_bac' => 'D',
            'note_bac' => 16.25,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de San-Pédro',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_serge_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-SPD-004567',
                'serie' => 'D',
                'note' => 16.25,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de San-Pédro',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            
            // Informations socio-économiques
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Pêcheur',
            'profession_mere' => 'Ménagère',
            'situations_particulieres' => ['situation_financiere_difficile'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            
            // Motivations
            'motivation' => "Je veux devenir biologiste marin pour protéger les ressources halieutiques de notre pays. Venant d'une famille de pêcheurs, j'ai vu l'impact de la surpêche et de la pollution sur nos côtes. Mon ambition est d'étudier les sciences marines pour développer des solutions durables.",
            'motivation_ai_score' => 8.5,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.85,
                'themes' => ['biologie_marine', 'environnement', 'developpement_durable'],
                'score_global' => 8.5
            ],
            
            // Statut dans le programme
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-18',
            
            // Informations complémentaires
            'bio' => "Futur biologiste marin engagé pour la protection de l'environnement côtier.",
            'competences' => [
                'Biologie',
                'Chimie',
                'Sciences environnementales',
                'Français'
            ],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Débutant',
                'Godié' => 'Maternelle'
            ],
            'photo' => 'photo_serge_kokoua.jpg',
            'cv_path' => 'cv_serge_kokoua.pdf',
            
            // Scoring PEUB
            'score_academique' => 83.13,
            'score_geographique' => 90.00,
            'score_socio_economique' => 95.00,
            'score_motivations' => 85.00,
            'score_final_peub' => 88.28,
            'rang_peub' => 78,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 16.25,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 83.13
                ],
                'geographique' => [
                    'region' => 'San-Pédro',
                    'commune' => 'San-Pédro',
                    'score_region' => 85.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 90.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'pecheur',
                    'profession_mere' => 'menagere',
                    'situations_particulieres' => ['situation_financiere_difficile'],
                    'score_calcule' => 95.00
                ],
                'motivations' => [
                    'score_ia' => 8.5,
                    'longueur_texte' => 180,
                    'themes_identifies' => 3,
                    'score_calcule' => 85.00
                ]
            ],
            'date_calcul_scoring' => now(),
            
            // Métadonnées d'extraction IA
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.87,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 40.8
            ]
        ]);

        $this->command->info('✅ Profil bachelier Serge Kokoua créé avec succès!');
        $this->command->info('📧 Email: ' . $bachelier->email_eleve);
        $this->command->info('🎯 Score PEUB: ' . $bachelier->score_final_peub . '/100');
        $this->command->info('🏆 Rang PEUB: ' . $bachelier->rang_peub);
    }

    private function createBachelierMarc()
    {
        // Créer un utilisateur pour le bachelier
        $user = $this->createUserIfNotExists([
            'email' => 'marckouassi@innoving.io',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Créer le profil bachelier
        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            
            // Informations personnelles
            'nom' => 'Kouassi',
            'prenoms' => 'Marc',
            'date_naissance' => '2005-02-14',
            'lieu_naissance' => 'Daloa',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_marc_kouassi.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Kouassi',
                'prenoms' => 'Marc',
                'date_naissance' => '2005-02-14',
                'lieu_naissance' => 'Daloa',
                'numero' => 'CI-321654987-05'
            ],
            'telephone_eleve' => '+2250703216549',
            'telephone_parent' => '+2250703216550',
            'email_eleve' => 'marckouassi@innoving.io',
            'email_parent' => 'parent.kouassi@example.com',
            'region' => 'Haut-Sassandra',
            'commune' => 'Daloa',
            
            // Informations académiques
            'matricule_bac' => '2024-DAL-002345',
            'serie_bac' => 'C',
            'note_bac' => 19.00,
            'mention' => 'tres_bien',
            'etablissement_nom' => 'Lycée Excellence de Daloa',
            'etablissement_type' => 'prive_homologue',
            'collante_bac_file' => 'collante_bac_marc_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-DAL-002345',
                'serie' => 'C',
                'note' => 19.00,
                'mention' => 'tres_bien',
                'etablissement' => 'Lycée Excellence de Daloa',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            
            // Informations socio-économiques
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Entrepreneur',
            'profession_mere' => 'Directrice commerciale',
            'situations_particulieres' => ['excellent_eleve'],
            'possede_ordinateur' => true,
            'connexion_internet' => 'fibre',
            'acces_smartphone' => true,
            'acces_ia' => true,
            
            // Motivations
            'motivation' => "Passionné par l'entrepreneuriat technologique et l'innovation, je veux créer la prochaine licorne africaine. Mon objectif est d'étudier l'informatique et le business pour développer des solutions tech qui transformeront l'économie ivoirienne. J'ai déjà créé plusieurs applications mobiles et je souhaite approfondir mes connaissances pour avoir un impact significatif.",
            'motivation_ai_score' => 9.5,
            'motivation_ai_analysis' => [
                'sentiment' => 'tres_positif',
                'confiance' => 0.95,
                'themes' => ['entrepreneuriat', 'technologie', 'innovation', 'impact_economique'],
                'score_global' => 9.5
            ],
            
            // Statut dans le programme
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-05',
            
            // Informations complémentaires
            'bio' => "Entrepreneur tech en devenir, créateur d'applications mobiles, passionné par l'innovation.",
            'competences' => [
                'Programmation avancée',
                'Entrepreneuriat',
                'Leadership',
                'Marketing digital',
                'Intelligence artificielle',
                'Anglais business'
            ],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Courant',
                'Espagnol' => 'Intermédiaire',
                'Mandarin' => 'Débutant'
            ],
            'photo' => 'photo_marc_kouassi.jpg',
            'cv_path' => 'cv_marc_kouassi.pdf',
            
            // Scoring PEUB
            'score_academique' => 97.50,
            'score_geographique' => 87.00,
            'score_socio_economique' => 70.00,
            'score_motivations' => 95.00,
            'score_final_peub' => 87.38,
            'rang_peub' => 5,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 19.00,
                    'mention' => 'tres_bien',
                    'bonus_mention' => 10.0,
                    'score_calcule' => 97.50
                ],
                'geographique' => [
                    'region' => 'Haut-Sassandra',
                    'commune' => 'Daloa',
                    'score_region' => 82.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 87.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'entrepreneur',
                    'profession_mere' => 'directrice_commerciale',
                    'situations_particulieres' => ['excellent_eleve'],
                    'score_calcule' => 70.00
                ],
                'motivations' => [
                    'score_ia' => 9.5,
                    'longueur_texte' => 300,
                    'themes_identifies' => 4,
                    'score_calcule' => 95.00
                ]
            ],
            'date_calcul_scoring' => now(),
            
            // Métadonnées d'extraction IA
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.96,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 35.2
            ]
        ]);

        $this->command->info('✅ Profil bachelier Marc Kouassi créé avec succès!');
        $this->command->info('📧 Email: ' . $bachelier->email_eleve);
        $this->command->info('🎯 Score PEUB: ' . $bachelier->score_final_peub . '/100');
        $this->command->info('🏆 Rang PEUB: ' . $bachelier->rang_peub);
    }

    private function createBachelierAissata()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'aissata.traore@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Traoré',
            'prenoms' => 'Aïssata',
            'date_naissance' => '2005-07-12',
            'lieu_naissance' => 'Korhogo',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_aissata_traore.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Traoré',
                'prenoms' => 'Aïssata',
                'date_naissance' => '2005-07-12',
                'lieu_naissance' => 'Korhogo',
                'numero' => 'CI-147258369-06'
            ],
            'telephone_eleve' => '+2250701472583',
            'telephone_parent' => '+2250701472584',
            'email_eleve' => 'aissata.traore@example.com',
            'email_parent' => 'papa.traore@example.com',
            'region' => 'Poro',
            'commune' => 'Korhogo',
            'matricule_bac' => '2024-KOR-001472',
            'serie_bac' => 'A4',
            'note_bac' => 15.80,
            'mention' => 'assez_bien',
            'etablissement_nom' => 'Lycée Municipal de Korhogo',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_aissata_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-KOR-001472',
                'serie' => 'A4',
                'note' => 15.80,
                'mention' => 'assez_bien',
                'etablissement' => 'Lycée Municipal de Korhogo',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Cultivateur',
            'profession_mere' => 'Couturière',
            'situations_particulieres' => ['boursier_lycee', 'situation_financiere_difficile'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Originaire du nord de la Côte d'Ivoire, je souhaite devenir journaliste pour donner une voix aux communautés rurales souvent oubliées. Mon rêve est de raconter les histoires de mon peuple et de contribuer à un développement plus équitable de notre pays.",
            'motivation_ai_score' => 8.2,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.82,
                'themes' => ['journalisme', 'communautes_rurales', 'developpement'],
                'score_global' => 8.2
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-22',
            'bio' => "Future journaliste engagée pour les communautés rurales du nord de la Côte d'Ivoire.",
            'competences' => ['Français', 'Histoire-Géographie', 'Philosophie', 'Anglais', 'Sénoufo'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Sénoufo' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_aissata_traore.jpg',
            'cv_path' => 'cv_aissata_traore.pdf',
            'score_academique' => 79.00,
            'score_geographique' => 95.00,
            'score_socio_economique' => 96.00,
            'score_motivations' => 82.00,
            'score_final_peub' => 88.00,
            'rang_peub' => 89,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 15.80,
                    'mention' => 'assez_bien',
                    'bonus_mention' => 2.0,
                    'score_calcule' => 79.00
                ],
                'geographique' => [
                    'region' => 'Poro',
                    'commune' => 'Korhogo',
                    'score_region' => 90.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 95.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'cultivateur',
                    'profession_mere' => 'couturiere',
                    'situations_particulieres' => ['boursier_lycee', 'situation_financiere_difficile'],
                    'score_calcule' => 96.00
                ],
                'motivations' => [
                    'score_ia' => 8.2,
                    'longueur_texte' => 220,
                    'themes_identifies' => 3,
                    'score_calcule' => 82.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.88,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 43.1
            ]
        ]);

        $this->command->info('✅ Profil bachelier Aïssata Traoré créé avec succès!');
    }

    private function createBachelierYves()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'yves.akoto@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Akoto',
            'prenoms' => 'Yves',
            'date_naissance' => '2004-12-03',
            'lieu_naissance' => 'Gagnoa',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_yves_akoto.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Akoto',
                'prenoms' => 'Yves',
                'date_naissance' => '2004-12-03',
                'lieu_naissance' => 'Gagnoa',
                'numero' => 'CI-258369147-07'
            ],
            'telephone_eleve' => '+2250702583691',
            'telephone_parent' => '+2250702583692',
            'email_eleve' => 'yves.akoto@example.com',
            'email_parent' => 'maman.akoto@example.com',
            'region' => 'Gôh',
            'commune' => 'Gagnoa',
            'matricule_bac' => '2024-GAG-002583',
            'serie_bac' => 'C',
            'note_bac' => 17.25,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Gagnoa',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_yves_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-GAG-002583',
                'serie' => 'C',
                'note' => 17.25,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de Gagnoa',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Mécanicien',
            'profession_mere' => 'Vendeuse',
            'situations_particulieres' => ['bon_eleve'],
            'possede_ordinateur' => true,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Passionné par la mécanique automobile et l'innovation technologique, je veux devenir ingénieur en génie mécanique pour moderniser l'industrie automobile en Afrique. Mon père mécanicien m'a transmis sa passion et je souhaite l'élever au niveau supérieur.",
            'motivation_ai_score' => 8.7,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.87,
                'themes' => ['mecanique', 'innovation', 'industrie_automobile'],
                'score_global' => 8.7
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-19',
            'bio' => "Futur ingénieur mécanicien, passionné par l'innovation automobile en Afrique.",
            'competences' => ['Mathématiques', 'Physique', 'Mécanique', 'Dessin technique', 'Français'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Bété' => 'Maternelle'
            ],
            'photo' => 'photo_yves_akoto.jpg',
            'cv_path' => 'cv_yves_akoto.pdf',
            'score_academique' => 86.25,
            'score_geographique' => 88.00,
            'score_socio_economique' => 82.50,
            'score_motivations' => 87.00,
            'score_final_peub' => 85.94,
            'rang_peub' => 124,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 17.25,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 86.25
                ],
                'geographique' => [
                    'region' => 'Gôh',
                    'commune' => 'Gagnoa',
                    'score_region' => 83.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 88.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'mecanicien',
                    'profession_mere' => 'vendeuse',
                    'situations_particulieres' => ['bon_eleve'],
                    'score_calcule' => 82.50
                ],
                'motivations' => [
                    'score_ia' => 8.7,
                    'longueur_texte' => 240,
                    'themes_identifies' => 3,
                    'score_calcule' => 87.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.91,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 39.8
            ]
        ]);

        $this->command->info('✅ Profil bachelier Yves Akoto créé avec succès!');
    }

    private function createBachelierMariam()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'mariam.ouattara@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Ouattara',
            'prenoms' => 'Mariam',
            'date_naissance' => '2005-04-18',
            'lieu_naissance' => 'Man',
            'sexe' => 'F',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'carte_scolaire_mariam.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Ouattara',
                'prenoms' => 'Mariam',
                'date_naissance' => '2005-04-18',
                'lieu_naissance' => 'Man',
                'numero' => 'CS-369147258-08'
            ],
            'telephone_eleve' => '+2250703691472',
            'telephone_parent' => '+2250703691473',
            'email_eleve' => 'mariam.ouattara@example.com',
            'email_parent' => 'papa.ouattara@example.com',
            'region' => 'Tonkpi',
            'commune' => 'Man',
            'matricule_bac' => '2024-MAN-003691',
            'serie_bac' => 'D',
            'note_bac' => 16.50,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Man',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_mariam_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-MAN-003691',
                'serie' => 'D',
                'note' => 16.50,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de Man',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Planteur',
            'profession_mere' => 'Sage-femme',
            'situations_particulieres' => ['boursier_lycee'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Inspirée par ma mère sage-femme, je veux devenir gynécologue-obstétricienne pour réduire la mortalité maternelle dans les zones rurales de l'ouest ivoirien. Mon objectif est d'améliorer les conditions d'accouchement et la santé reproductive des femmes.",
            'motivation_ai_score' => 9.1,
            'motivation_ai_analysis' => [
                'sentiment' => 'tres_positif',
                'confiance' => 0.91,
                'themes' => ['medecine', 'sante_maternelle', 'zones_rurales'],
                'score_global' => 9.1
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-17',
            'bio' => "Future gynécologue-obstétricienne engagée pour la santé maternelle en milieu rural.",
            'competences' => ['Biologie', 'Chimie', 'Sciences physiques', 'Français', 'Yacouba'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Yacouba' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_mariam_ouattara.jpg',
            'cv_path' => 'cv_mariam_ouattara.pdf',
            'score_academique' => 82.50,
            'score_geographique' => 92.00,
            'score_socio_economique' => 90.00,
            'score_motivations' => 91.00,
            'score_final_peub' => 88.88,
            'rang_peub' => 67,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 16.50,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 82.50
                ],
                'geographique' => [
                    'region' => 'Tonkpi',
                    'commune' => 'Man',
                    'score_region' => 87.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 92.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'planteur',
                    'profession_mere' => 'sage_femme',
                    'situations_particulieres' => ['boursier_lycee'],
                    'score_calcule' => 90.00
                ],
                'motivations' => [
                    'score_ia' => 9.1,
                    'longueur_texte' => 260,
                    'themes_identifies' => 3,
                    'score_calcule' => 91.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.89,
                'documents_traites' => ['carte_scolaire', 'collante_bac'],
                'temps_traitement' => 41.5
            ]
        ]);

        $this->command->info('✅ Profil bachelier Mariam Ouattara créé avec succès!');
    }

    private function createBachelierKouadio()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'kouadio.ange@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Kouadio',
            'prenoms' => 'Ange',
            'date_naissance' => '2005-09-25',
            'lieu_naissance' => 'Agboville',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_kouadio_ange.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Kouadio',
                'prenoms' => 'Ange',
                'date_naissance' => '2005-09-25',
                'lieu_naissance' => 'Agboville',
                'numero' => 'CI-147852963-09'
            ],
            'telephone_eleve' => '+2250701478529',
            'telephone_parent' => '+2250701478530',
            'email_eleve' => 'kouadio.ange@example.com',
            'email_parent' => 'papa.kouadio@example.com',
            'region' => 'Agnéby-Tiassa',
            'commune' => 'Agboville',
            'matricule_bac' => '2024-AGB-001478',
            'serie_bac' => 'C',
            'note_bac' => 18.75,
            'mention' => 'tres_bien',
            'etablissement_nom' => 'Lycée Classique d\'Agboville',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_kouadio_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-AGB-001478',
                'serie' => 'C',
                'note' => 18.75,
                'mention' => 'tres_bien',
                'etablissement' => 'Lycée Classique d\'Agboville',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Fonctionnaire',
            'profession_mere' => 'Institutrice',
            'situations_particulieres' => ['excellent_eleve'],
            'possede_ordinateur' => true,
            'connexion_internet' => 'fibre',
            'acces_smartphone' => true,
            'acces_ia' => true,
            'motivation' => "Passionné par les énergies renouvelables et l'ingénierie électrique, je veux contribuer à l'électrification rurale de la Côte d'Ivoire avec des solutions solaires innovantes. Mon rêve est de créer une startup spécialisée dans les mini-grids solaires pour les villages isolés.",
            'motivation_ai_score' => 9.3,
            'motivation_ai_analysis' => [
                'sentiment' => 'tres_positif',
                'confiance' => 0.93,
                'themes' => ['energies_renouvelables', 'electrification_rurale', 'innovation'],
                'score_global' => 9.3
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-08',
            'bio' => "Futur ingénieur électricien spécialisé dans les énergies renouvelables et l'électrification rurale.",
            'competences' => ['Mathématiques', 'Physique', 'Électricité', 'Programmation', 'Anglais technique'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Avancé',
                'Baoulé' => 'Maternelle'
            ],
            'photo' => 'photo_kouadio_ange.jpg',
            'cv_path' => 'cv_kouadio_ange.pdf',
            'score_academique' => 93.75,
            'score_geographique' => 84.00,
            'score_socio_economique' => 75.00,
            'score_motivations' => 93.00,
            'score_final_peub' => 86.44,
            'rang_peub' => 43,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 18.75,
                    'mention' => 'tres_bien',
                    'bonus_mention' => 10.0,
                    'score_calcule' => 93.75
                ],
                'geographique' => [
                    'region' => 'Agnéby-Tiassa',
                    'commune' => 'Agboville',
                    'score_region' => 79.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 84.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'fonctionnaire',
                    'profession_mere' => 'institutrice',
                    'situations_particulieres' => ['excellent_eleve'],
                    'score_calcule' => 75.00
                ],
                'motivations' => [
                    'score_ia' => 9.3,
                    'longueur_texte' => 280,
                    'themes_identifies' => 3,
                    'score_calcule' => 93.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.94,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 37.2
            ]
        ]);

        $this->command->info('✅ Profil bachelier Kouadio Ange créé avec succès!');
    }

    private function createBachelierFatoumata()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'fatoumata.kone@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Koné',
            'prenoms' => 'Fatoumata',
            'date_naissance' => '2005-01-30',
            'lieu_naissance' => 'Odienné',
            'sexe' => 'F',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'carte_scolaire_fatoumata.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Koné',
                'prenoms' => 'Fatoumata',
                'date_naissance' => '2005-01-30',
                'lieu_naissance' => 'Odienné',
                'numero' => 'CS-852963147-10'
            ],
            'telephone_eleve' => '+2250708529631',
            'telephone_parent' => '+2250708529632',
            'email_eleve' => 'fatoumata.kone@example.com',
            'email_parent' => 'maman.kone@example.com',
            'region' => 'Kabadougou',
            'commune' => 'Odienné',
            'matricule_bac' => '2024-ODI-008529',
            'serie_bac' => 'A4',
            'note_bac' => 14.75,
            'mention' => 'passable',
            'etablissement_nom' => 'Lycée Moderne d\'Odienné',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_fatoumata_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-ODI-008529',
                'serie' => 'A4',
                'note' => 14.75,
                'mention' => 'passable',
                'etablissement' => 'Lycée Moderne d\'Odienné',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Éleveur',
            'profession_mere' => 'Ménagère',
            'situations_particulieres' => ['boursier_lycee', 'famille_nombreuse'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => false,
            'acces_ia' => false,
            'motivation' => "Issue d'une famille d'éleveurs du nord-ouest, je veux devenir vétérinaire pour améliorer la santé du bétail et moderniser l'élevage traditionnel. Mon objectif est de revenir dans ma région pour former les éleveurs aux nouvelles techniques.",
            'motivation_ai_score' => 8.0,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.80,
                'themes' => ['veterinaire', 'elevage', 'formation'],
                'score_global' => 8.0
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-25',
            'bio' => "Future vétérinaire dédiée à la modernisation de l'élevage traditionnel.",
            'competences' => ['Biologie', 'Français', 'Histoire-Géographie', 'Malinké'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Débutant',
                'Malinké' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_fatoumata_kone.jpg',
            'cv_path' => 'cv_fatoumata_kone.pdf',
            'score_academique' => 73.75,
            'score_geographique' => 98.00,
            'score_socio_economique' => 98.00,
            'score_motivations' => 80.00,
            'score_final_peub' => 87.44,
            'rang_peub' => 95,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 14.75,
                    'mention' => 'passable',
                    'bonus_mention' => 0.0,
                    'score_calcule' => 73.75
                ],
                'geographique' => [
                    'region' => 'Kabadougou',
                    'commune' => 'Odienné',
                    'score_region' => 93.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 98.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'eleveur',
                    'profession_mere' => 'menagere',
                    'situations_particulieres' => ['boursier_lycee', 'famille_nombreuse'],
                    'score_calcule' => 98.00
                ],
                'motivations' => [
                    'score_ia' => 8.0,
                    'longueur_texte' => 200,
                    'themes_identifies' => 3,
                    'score_calcule' => 80.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.85,
                'documents_traites' => ['carte_scolaire', 'collante_bac'],
                'temps_traitement' => 44.7
            ]
        ]);

        $this->command->info('✅ Profil bachelier Fatoumata Koné créé avec succès!');
    }

    private function createBachelierJean()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'jean.baptiste@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Baptiste',
            'prenoms' => 'Jean',
            'date_naissance' => '2004-06-14',
            'lieu_naissance' => 'Divo',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_jean_baptiste.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Baptiste',
                'prenoms' => 'Jean',
                'date_naissance' => '2004-06-14',
                'lieu_naissance' => 'Divo',
                'numero' => 'CI-963147852-11'
            ],
            'telephone_eleve' => '+2250709631478',
            'telephone_parent' => '+2250709631479',
            'email_eleve' => 'jean.baptiste@example.com',
            'email_parent' => 'papa.baptiste@example.com',
            'region' => 'Lôh-Djiboua',
            'commune' => 'Divo',
            'matricule_bac' => '2024-DIV-009631',
            'serie_bac' => 'C',
            'note_bac' => 16.75,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Technique de Divo',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_jean_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-DIV-009631',
                'serie' => 'C',
                'note' => 16.75,
                'mention' => 'bien',
                'etablissement' => 'Lycée Technique de Divo',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Chauffeur',
            'profession_mere' => 'Coiffeuse',
            'situations_particulieres' => ['bon_eleve'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Passionné par le génie civil et l'architecture, je rêve de construire des infrastructures modernes en Côte d'Ivoire. Mon objectif est de participer à la modernisation de nos villes tout en préservant notre identité culturelle.",
            'motivation_ai_score' => 8.4,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.84,
                'themes' => ['genie_civil', 'architecture', 'infrastructures'],
                'score_global' => 8.4
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-16',
            'bio' => "Futur ingénieur civil spécialisé dans les infrastructures urbaines modernes.",
            'competences' => ['Mathématiques', 'Physique', 'Dessin technique', 'Géométrie', 'Français'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Godié' => 'Maternelle'
            ],
            'photo' => 'photo_jean_baptiste.jpg',
            'cv_path' => 'cv_jean_baptiste.pdf',
            'score_academique' => 83.75,
            'score_geographique' => 86.00,
            'score_socio_economique' => 84.00,
            'score_motivations' => 84.00,
            'score_final_peub' => 84.44,
            'rang_peub' => 145,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 16.75,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 83.75
                ],
                'geographique' => [
                    'region' => 'Lôh-Djiboua',
                    'commune' => 'Divo',
                    'score_region' => 81.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 86.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'chauffeur',
                    'profession_mere' => 'coiffeuse',
                    'situations_particulieres' => ['bon_eleve'],
                    'score_calcule' => 84.00
                ],
                'motivations' => [
                    'score_ia' => 8.4,
                    'longueur_texte' => 220,
                    'themes_identifies' => 3,
                    'score_calcule' => 84.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.90,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 38.9
            ]
        ]);

        $this->command->info('✅ Profil bachelier Jean Baptiste créé avec succès!');
    }

    private function createBachelierAkissi()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'akissi.nadege@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Akissi',
            'prenoms' => 'Nadège',
            'date_naissance' => '2005-10-08',
            'lieu_naissance' => 'Grand-Bassam',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_akissi_nadege.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Akissi',
                'prenoms' => 'Nadège',
                'date_naissance' => '2005-10-08',
                'lieu_naissance' => 'Grand-Bassam',
                'numero' => 'CI-741852963-12'
            ],
            'telephone_eleve' => '+2250707418529',
            'telephone_parent' => '+2250707418530',
            'email_eleve' => 'akissi.nadege@example.com',
            'email_parent' => 'maman.akissi@example.com',
            'region' => 'Sud-Comoé',
            'commune' => 'Grand-Bassam',
            'matricule_bac' => '2024-GBA-007418',
            'serie_bac' => 'A4',
            'note_bac' => 17.80,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Grand-Bassam',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_akissi_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-GBA-007418',
                'serie' => 'A4',
                'note' => 17.80,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de Grand-Bassam',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Pêcheur',
            'profession_mere' => 'Restauratrice',
            'situations_particulieres' => ['boursier_lycee'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Passionnée par les langues et les relations internationales, je veux devenir diplomate pour représenter la Côte d'Ivoire sur la scène mondiale. Mon objectif est de promouvoir la culture ivoirienne et faciliter les échanges commerciaux.",
            'motivation_ai_score' => 8.9,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.89,
                'themes' => ['diplomatie', 'relations_internationales', 'culture'],
                'score_global' => 8.9
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-14',
            'bio' => "Future diplomate dédiée à la promotion de la culture et des échanges ivoiriens.",
            'competences' => ['Français', 'Anglais', 'Histoire-Géographie', 'Philosophie', 'Communication'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Avancé',
                'Espagnol' => 'Intermédiaire',
                'Nzima' => 'Maternelle'
            ],
            'photo' => 'photo_akissi_nadege.jpg',
            'cv_path' => 'cv_akissi_nadege.pdf',
            'score_academique' => 89.00,
            'score_geographique' => 85.00,
            'score_socio_economique' => 88.00,
            'score_motivations' => 89.00,
            'score_final_peub' => 87.75,
            'rang_peub' => 92,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 17.80,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 89.00
                ],
                'geographique' => [
                    'region' => 'Sud-Comoé',
                    'commune' => 'Grand-Bassam',
                    'score_region' => 80.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 85.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'pecheur',
                    'profession_mere' => 'restauratrice',
                    'situations_particulieres' => ['boursier_lycee'],
                    'score_calcule' => 88.00
                ],
                'motivations' => [
                    'score_ia' => 8.9,
                    'longueur_texte' => 250,
                    'themes_identifies' => 3,
                    'score_calcule' => 89.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.92,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 36.4
            ]
        ]);

        $this->command->info('✅ Profil bachelier Akissi Nadège créé avec succès!');
    }

    private function createBachelierAbdoul()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'abdoul.karim@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Karim',
            'prenoms' => 'Abdoul',
            'date_naissance' => '2005-03-22',
            'lieu_naissance' => 'Bondoukou',
            'sexe' => 'M',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'carte_scolaire_abdoul.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Karim',
                'prenoms' => 'Abdoul',
                'date_naissance' => '2005-03-22',
                'lieu_naissance' => 'Bondoukou',
                'numero' => 'CS-159357486-13'
            ],
            'telephone_eleve' => '+2250701593574',
            'telephone_parent' => '+2250701593575',
            'email_eleve' => 'abdoul.karim@example.com',
            'email_parent' => 'papa.karim@example.com',
            'region' => 'Gontougo',
            'commune' => 'Bondoukou',
            'matricule_bac' => '2024-BON-001593',
            'serie_bac' => 'D',
            'note_bac' => 15.25,
            'mention' => 'assez_bien',
            'etablissement_nom' => 'Lycée Moderne de Bondoukou',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_abdoul_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-BON-001593',
                'serie' => 'D',
                'note' => 15.25,
                'mention' => 'assez_bien',
                'etablissement' => 'Lycée Moderne de Bondoukou',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Commerçant',
            'profession_mere' => 'Ménagère',
            'situations_particulieres' => ['boursier_lycee', 'situation_financiere_difficile'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Passionné par les sciences naturelles et l'environnement, je veux devenir agronome pour développer l'agriculture durable dans l'est de la Côte d'Ivoire. Mon rêve est d'aider les agriculteurs à améliorer leurs rendements tout en préservant l'environnement.",
            'motivation_ai_score' => 8.6,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.86,
                'themes' => ['agronomie', 'agriculture_durable', 'environnement'],
                'score_global' => 8.6
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-23',
            'bio' => "Futur agronome spécialisé dans l'agriculture durable et la préservation environnementale.",
            'competences' => ['Biologie', 'Chimie', 'Sciences de la Terre', 'Français', 'Koulango'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Débutant',
                'Koulango' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_abdoul_karim.jpg',
            'cv_path' => 'cv_abdoul_karim.pdf',
            'score_academique' => 76.25,
            'score_geographique' => 93.00,
            'score_socio_economique' => 94.00,
            'score_motivations' => 86.00,
            'score_final_peub' => 87.31,
            'rang_peub' => 99,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 15.25,
                    'mention' => 'assez_bien',
                    'bonus_mention' => 2.0,
                    'score_calcule' => 76.25
                ],
                'geographique' => [
                    'region' => 'Gontougo',
                    'commune' => 'Bondoukou',
                    'score_region' => 88.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 93.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'commercant',
                    'profession_mere' => 'menagere',
                    'situations_particulieres' => ['boursier_lycee', 'situation_financiere_difficile'],
                    'score_calcule' => 94.00
                ],
                'motivations' => [
                    'score_ia' => 8.6,
                    'longueur_texte' => 260,
                    'themes_identifies' => 3,
                    'score_calcule' => 86.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.87,
                'documents_traites' => ['carte_scolaire', 'collante_bac'],
                'temps_traitement' => 42.8
            ]
        ]);

        $this->command->info('✅ Profil bachelier Abdoul Karim créé avec succès!');
    }

    private function createBachelierBinta()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'binta.sangare@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Sangaré',
            'prenoms' => 'Binta',
            'date_naissance' => '2004-11-17',
            'lieu_naissance' => 'Ferkessédougou',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_binta_sangare.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Sangaré',
                'prenoms' => 'Binta',
                'date_naissance' => '2004-11-17',
                'lieu_naissance' => 'Ferkessédougou',
                'numero' => 'CI-357486159-14'
            ],
            'telephone_eleve' => '+2250703574861',
            'telephone_parent' => '+2250703574862',
            'email_eleve' => 'binta.sangare@example.com',
            'email_parent' => 'maman.sangare@example.com',
            'region' => 'Tchologo',
            'commune' => 'Ferkessédougou',
            'matricule_bac' => '2024-FER-003574',
            'serie_bac' => 'A4',
            'note_bac' => 16.30,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Sainte Thérèse de Ferkessédougou',
            'etablissement_type' => 'prive_homologue',
            'collante_bac_file' => 'collante_bac_binta_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-FER-003574',
                'serie' => 'A4',
                'note' => 16.30,
                'mention' => 'bien',
                'etablissement' => 'Lycée Sainte Thérèse de Ferkessédougou',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Transporteur',
            'profession_mere' => 'Commerçante',
            'situations_particulieres' => ['boursier_lycee'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Intéressée par le droit et la justice sociale, je veux devenir avocate pour défendre les droits des plus démunis. Mon objectif est de lutter contre les inégalités et promouvoir l'accès à la justice pour tous les Ivoiriens.",
            'motivation_ai_score' => 8.8,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.88,
                'themes' => ['droit', 'justice_sociale', 'egalite'],
                'score_global' => 8.8
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-12',
            'bio' => "Future avocate engagée pour la justice sociale et l'égalité des droits.",
            'competences' => ['Français', 'Histoire-Géographie', 'Philosophie', 'Expression orale', 'Sénoufo'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Sénoufo' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_binta_sangare.jpg',
            'cv_path' => 'cv_binta_sangare.pdf',
            'score_academique' => 81.50,
            'score_geographique' => 91.00,
            'score_socio_economique' => 89.00,
            'score_motivations' => 88.00,
            'score_final_peub' => 87.38,
            'rang_peub' => 96,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 16.30,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 81.50
                ],
                'geographique' => [
                    'region' => 'Tchologo',
                    'commune' => 'Ferkessédougou',
                    'score_region' => 86.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 91.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'transporteur',
                    'profession_mere' => 'commercante',
                    'situations_particulieres' => ['boursier_lycee'],
                    'score_calcule' => 89.00
                ],
                'motivations' => [
                    'score_ia' => 8.8,
                    'longueur_texte' => 230,
                    'themes_identifies' => 3,
                    'score_calcule' => 88.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.91,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 37.6
            ]
        ]);

        $this->command->info('✅ Profil bachelier Binta Sangaré créé avec succès!');
    }

    private function createBachelierPatrick()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'patrick.yao@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Yao',
            'prenoms' => 'Patrick',
            'date_naissance' => '2005-08-29',
            'lieu_naissance' => 'Soubré',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_patrick_yao.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Yao',
                'prenoms' => 'Patrick',
                'date_naissance' => '2005-08-29',
                'lieu_naissance' => 'Soubré',
                'numero' => 'CI-486159357-15'
            ],
            'telephone_eleve' => '+2250704861593',
            'telephone_parent' => '+2250704861594',
            'email_eleve' => 'patrick.yao@example.com',
            'email_parent' => 'papa.yao@example.com',
            'region' => 'Nawa',
            'commune' => 'Soubré',
            'matricule_bac' => '2024-SOU-004861',
            'serie_bac' => 'C',
            'note_bac' => 18.20,
            'mention' => 'tres_bien',
            'etablissement_nom' => 'Lycée Moderne de Soubré',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_patrick_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-SOU-004861',
                'serie' => 'C',
                'note' => 18.20,
                'mention' => 'tres_bien',
                'etablissement' => 'Lycée Moderne de Soubré',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Planteur de cacao',
            'profession_mere' => 'Institutrice',
            'situations_particulieres' => ['excellent_eleve'],
            'possede_ordinateur' => true,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => true,
            'motivation' => "Passionné par les technologies agricoles et l'agro-industrie, je veux devenir ingénieur agronome spécialisé dans la transformation du cacao. Mon objectif est d'aider les planteurs à valoriser leur production et développer une industrie chocolatière locale.",
            'motivation_ai_score' => 9.0,
            'motivation_ai_analysis' => [
                'sentiment' => 'tres_positif',
                'confiance' => 0.90,
                'themes' => ['agro_industrie', 'cacao', 'valorisation'],
                'score_global' => 9.0
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-11',
            'bio' => "Futur ingénieur agronome spécialisé dans la transformation et valorisation du cacao.",
            'competences' => ['Mathématiques', 'Physique', 'Chimie', 'Agronomie', 'Bété'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Avancé',
                'Bété' => 'Maternelle'
            ],
            'photo' => 'photo_patrick_yao.jpg',
            'cv_path' => 'cv_patrick_yao.pdf',
            'score_academique' => 91.00,
            'score_geographique' => 89.00,
            'score_socio_economique' => 78.00,
            'score_motivations' => 90.00,
            'score_final_peub' => 87.00,
            'rang_peub' => 38,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 18.20,
                    'mention' => 'tres_bien',
                    'bonus_mention' => 10.0,
                    'score_calcule' => 91.00
                ],
                'geographique' => [
                    'region' => 'Nawa',
                    'commune' => 'Soubré',
                    'score_region' => 84.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 89.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'planteur_cacao',
                    'profession_mere' => 'institutrice',
                    'situations_particulieres' => ['excellent_eleve'],
                    'score_calcule' => 78.00
                ],
                'motivations' => [
                    'score_ia' => 9.0,
                    'longueur_texte' => 270,
                    'themes_identifies' => 3,
                    'score_calcule' => 90.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.93,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 34.2
            ]
        ]);

        $this->command->info('✅ Profil bachelier Patrick Yao créé avec succès!');
    }

    private function createBachelierAminata()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'aminata.cisse@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Cissé',
            'prenoms' => 'Aminata',
            'date_naissance' => '2005-05-06',
            'lieu_naissance' => 'Séguéla',
            'sexe' => 'F',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'carte_scolaire_aminata.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Cissé',
                'prenoms' => 'Aminata',
                'date_naissance' => '2005-05-06',
                'lieu_naissance' => 'Séguéla',
                'numero' => 'CS-159357486-16'
            ],
            'telephone_eleve' => '+2250701593574',
            'telephone_parent' => '+2250701593576',
            'email_eleve' => 'aminata.cisse@example.com',
            'email_parent' => 'maman.cisse@example.com',
            'region' => 'Worodougou',
            'commune' => 'Séguéla',
            'matricule_bac' => '2024-SEG-001593',
            'serie_bac' => 'D',
            'note_bac' => 17.45,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Séguéla',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_aminata_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-SEG-001593',
                'serie' => 'D',
                'note' => 17.45,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de Séguéla',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Mineur',
            'profession_mere' => 'Couturière',
            'situations_particulieres' => ['boursier_lycee', 'orpheline_mere'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Ayant perdu ma mère très tôt, je veux devenir pharmacienne pour améliorer l'accès aux médicaments dans les zones rurales. Mon rêve est d'ouvrir des pharmacies communautaires et former des agents de santé locale.",
            'motivation_ai_score' => 9.2,
            'motivation_ai_analysis' => [
                'sentiment' => 'tres_positif',
                'confiance' => 0.92,
                'themes' => ['pharmacie', 'sante_rurale', 'acces_medicaments'],
                'score_global' => 9.2
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-13',
            'bio' => "Future pharmacienne dédiée à l'amélioration de l'accès aux soins en milieu rural.",
            'competences' => ['Biologie', 'Chimie', 'Physique', 'Mathématiques', 'Malinké'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Malinké' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_aminata_cisse.jpg',
            'cv_path' => 'cv_aminata_cisse.pdf',
            'score_academique' => 87.25,
            'score_geographique' => 94.00,
            'score_socio_economique' => 96.00,
            'score_motivations' => 92.00,
            'score_final_peub' => 92.31,
            'rang_peub' => 18,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 17.45,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 87.25
                ],
                'geographique' => [
                    'region' => 'Worodougou',
                    'commune' => 'Séguéla',
                    'score_region' => 89.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 94.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'mineur',
                    'profession_mere' => 'couturiere',
                    'situations_particulieres' => ['boursier_lycee', 'orpheline_mere'],
                    'score_calcule' => 96.00
                ],
                'motivations' => [
                    'score_ia' => 9.2,
                    'longueur_texte' => 220,
                    'themes_identifies' => 3,
                    'score_calcule' => 92.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.90,
                'documents_traites' => ['carte_scolaire', 'collante_bac'],
                'temps_traitement' => 40.1
            ]
        ]);

        $this->command->info('✅ Profil bachelier Aminata Cissé créé avec succès!');
    }

    private function createBachelierEmmanuel()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'emmanuel.kone@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Koné',
            'prenoms' => 'Emmanuel',
            'date_naissance' => '2004-09-11',
            'lieu_naissance' => 'Katiola',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_emmanuel_kone.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Koné',
                'prenoms' => 'Emmanuel',
                'date_naissance' => '2004-09-11',
                'lieu_naissance' => 'Katiola',
                'numero' => 'CI-753951486-17'
            ],
            'telephone_eleve' => '+2250707539514',
            'telephone_parent' => '+2250707539515',
            'email_eleve' => 'emmanuel.kone@example.com',
            'email_parent' => 'papa.kone@example.com',
            'region' => 'Hambol',
            'commune' => 'Katiola',
            'matricule_bac' => '2024-KAT-007539',
            'serie_bac' => 'C',
            'note_bac' => 16.90,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Katiola',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_emmanuel_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-KAT-007539',
                'serie' => 'C',
                'note' => 16.90,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de Katiola',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Gendarme',
            'profession_mere' => 'Secrétaire',
            'situations_particulieres' => ['bon_eleve'],
            'possede_ordinateur' => true,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Inspiré par mon père gendarme, je veux devenir ingénieur en cybersécurité pour protéger les infrastructures numériques de la Côte d'Ivoire. Mon objectif est de contribuer à la sécurité informatique des institutions et entreprises nationales.",
            'motivation_ai_score' => 8.3,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.83,
                'themes' => ['cybersecurite', 'infrastructure_numerique', 'securite'],
                'score_global' => 8.3
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-15',
            'bio' => "Futur ingénieur cybersécurité, passionné par la protection des infrastructures numériques.",
            'competences' => ['Mathématiques', 'Physique', 'Informatique', 'Réseaux', 'Français'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Avancé',
                'Malinké' => 'Maternelle'
            ],
            'photo' => 'photo_emmanuel_kone.jpg',
            'cv_path' => 'cv_emmanuel_kone.pdf',
            'score_academique' => 84.50,
            'score_geographique' => 87.00,
            'score_socio_economique' => 80.00,
            'score_motivations' => 83.00,
            'score_final_peub' => 83.63,
            'rang_peub' => 173,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 16.90,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 84.50
                ],
                'geographique' => [
                    'region' => 'Hambol',
                    'commune' => 'Katiola',
                    'score_region' => 82.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 87.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'gendarme',
                    'profession_mere' => 'secretaire',
                    'situations_particulieres' => ['bon_eleve'],
                    'score_calcule' => 80.00
                ],
                'motivations' => [
                    'score_ia' => 8.3,
                    'longueur_texte' => 240,
                    'themes_identifies' => 3,
                    'score_calcule' => 83.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.88,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 41.7
            ]
        ]);

        $this->command->info('✅ Profil bachelier Emmanuel Koné créé avec succès!');
    }

    private function createBachelierCeline()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'celine.bamba@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Bamba',
            'prenoms' => 'Céline',
            'date_naissance' => '2005-12-04',
            'lieu_naissance' => 'Danané',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_celine_bamba.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Bamba',
                'prenoms' => 'Céline',
                'date_naissance' => '2005-12-04',
                'lieu_naissance' => 'Danané',
                'numero' => 'CI-159357486-18'
            ],
            'telephone_eleve' => '+2250701593574',
            'telephone_parent' => '+2250701593577',
            'email_eleve' => 'celine.bamba@example.com',
            'email_parent' => 'papa.bamba@example.com',
            'region' => 'Tonkpi',
            'commune' => 'Danané',
            'matricule_bac' => '2024-DAN-001593',
            'serie_bac' => 'A4',
            'note_bac' => 16.85,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Danané',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_celine_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-DAN-001593',
                'serie' => 'A4',
                'note' => 16.85,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de Danané',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Garde forestier',
            'profession_mere' => 'Tisserande',
            'situations_particulieres' => ['boursier_lycee'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Passionnée par l'écologie et la conservation, je veux devenir ingénieure environnementale pour protéger les forêts de l'ouest ivoirien. Mon père garde forestier m'a sensibilisée à l'importance de préserver notre patrimoine naturel.",
            'motivation_ai_score' => 8.7,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.87,
                'themes' => ['ecologie', 'conservation', 'forets'],
                'score_global' => 8.7
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-21',
            'bio' => "Future ingénieure environnementale engagée pour la conservation forestière.",
            'competences' => ['Sciences naturelles', 'Écologie', 'Français', 'Géographie', 'Dan'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Dan' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_celine_bamba.jpg',
            'cv_path' => 'cv_celine_bamba.pdf',
            'score_academique' => 84.25,
            'score_geographique' => 92.00,
            'score_socio_economique' => 90.00,
            'score_motivations' => 87.00,
            'score_final_peub' => 88.31,
            'rang_peub' => 81,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 16.85,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 84.25
                ],
                'geographique' => [
                    'region' => 'Tonkpi',
                    'commune' => 'Danané',
                    'score_region' => 87.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 92.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'garde_forestier',
                    'profession_mere' => 'tisserande',
                    'situations_particulieres' => ['boursier_lycee'],
                    'score_calcule' => 90.00
                ],
                'motivations' => [
                    'score_ia' => 8.7,
                    'longueur_texte' => 240,
                    'themes_identifies' => 3,
                    'score_calcule' => 87.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.89,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 39.3
            ]
        ]);

        $this->command->info('✅ Profil bachelier Céline Bamba créé avec succès!');
    }

    private function createBachelierIbrahim()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'ibrahim.ouattara@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Ouattara',
            'prenoms' => 'Ibrahim',
            'date_naissance' => '2004-07-26',
            'lieu_naissance' => 'Tengréla',
            'sexe' => 'M',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'carte_scolaire_ibrahim.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Ouattara',
                'prenoms' => 'Ibrahim',
                'date_naissance' => '2004-07-26',
                'lieu_naissance' => 'Tengréla',
                'numero' => 'CS-357486159-19'
            ],
            'telephone_eleve' => '+2250703574861',
            'telephone_parent' => '+2250703574863',
            'email_eleve' => 'ibrahim.ouattara@example.com',
            'email_parent' => 'maman.ouattara@example.com',
            'region' => 'Bagoué',
            'commune' => 'Tengréla',
            'matricule_bac' => '2024-TEN-003574',
            'serie_bac' => 'C',
            'note_bac' => 17.15,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Tengréla',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_ibrahim_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-TEN-003574',
                'serie' => 'C',
                'note' => 17.15,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de Tengréla',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Maçon',
            'profession_mere' => 'Ménagère',
            'situations_particulieres' => ['boursier_lycee', 'famille_nombreuse'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Passionné par l'architecture et les constructions durables, je veux devenir architecte pour concevoir des habitations adaptées au climat tropical. Mon père maçon m'a appris l'importance d'une construction solide et respectueuse de l'environnement.",
            'motivation_ai_score' => 8.5,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.85,
                'themes' => ['architecture', 'construction_durable', 'environnement'],
                'score_global' => 8.5
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-20',
            'bio' => "Futur architecte spécialisé dans les constructions durables et climatiquement adaptées.",
            'competences' => ['Mathématiques', 'Physique', 'Dessin technique', 'Géométrie', 'Sénoufo'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Débutant',
                'Sénoufo' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_ibrahim_ouattara.jpg',
            'cv_path' => 'cv_ibrahim_ouattara.pdf',
            'score_academique' => 85.75,
            'score_geographique' => 95.00,
            'score_socio_economique' => 93.00,
            'score_motivations' => 85.00,
            'score_final_peub' => 89.69,
            'rang_peub' => 62,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 17.15,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 85.75
                ],
                'geographique' => [
                    'region' => 'Bagoué',
                    'commune' => 'Tengréla',
                    'score_region' => 90.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 95.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'macon',
                    'profession_mere' => 'menagere',
                    'situations_particulieres' => ['boursier_lycee', 'famille_nombreuse'],
                    'score_calcule' => 93.00
                ],
                'motivations' => [
                    'score_ia' => 8.5,
                    'longueur_texte' => 250,
                    'themes_identifies' => 3,
                    'score_calcule' => 85.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.86,
                'documents_traites' => ['carte_scolaire', 'collante_bac'],
                'temps_traitement' => 41.4
            ]
        ]);

        $this->command->info('✅ Profil bachelier Ibrahim Ouattara créé avec succès!');
    }

    // Ajout simplifié des 20 profils restants avec données plus concises
    private function createBachelierNancy()
    {
        $user = $this->createUserIfNotExists(['email' => 'nancy.koffi@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id, 'nom' => 'Koffi', 'prenoms' => 'Nancy', 'date_naissance' => '2005-01-15', 'lieu_naissance' => 'Issia', 'sexe' => 'F',
            'piece_identite_type' => 'cni', 'piece_identite_file' => 'cni_nancy_koffi.pdf', 'telephone_eleve' => '+2250704861593', 'telephone_parent' => '+2250704861594', 
            'email_eleve' => 'nancy.koffi@example.com', 'email_parent' => 'papa.koffi@example.com', 'region' => 'Haut-Sassandra', 'commune' => 'Issia',
            'matricule_bac' => '2024-ISS-004861', 'serie_bac' => 'D', 'note_bac' => 18.60, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Sainte Marie d\'Issia',
            'etablissement_type' => 'prive_homologue', 'collante_bac_file' => 'collante_bac_nancy_2024.pdf', 'annee_bac' => 2024, 
            'pensionnaire_internat' => false, 'bourse_scolaire_lycee' => false, 'profession_pere' => 'Pharmacien', 'profession_mere' => 'Médecin',
            'possede_ordinateur' => true, 'connexion_internet' => 'fibre', 'acces_smartphone' => true, 'acces_ia' => false,
            'motivation' => "Inspirée par mes parents professionnels de santé, je veux devenir médecin pédiatre pour m'occuper des enfants malades.",
            'motivation_ai_score' => 9.4, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 84.75, 'rang_peub' => 12,
            'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()
        ]);
        $this->command->info('✅ Profil bachelier Nancy Koffi créé avec succès!');
    }

    private function createBachelierMamadou()
    {
        $user = $this->createUserIfNotExists(['email' => 'mamadou.diarra@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id, 'nom' => 'Diarra', 'prenoms' => 'Mamadou', 'date_naissance' => '2004-10-19', 'lieu_naissance' => 'Minignan', 'sexe' => 'M',
            'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250701593574', 'email_eleve' => 'mamadou.diarra@example.com', 'region' => 'Folon', 'commune' => 'Minignan',
            'matricule_bac' => '2024-MIN-001593', 'serie_bac' => 'C', 'note_bac' => 15.95, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne de Minignan',
            'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Orpailleur', 'profession_mere' => 'Vendeuse de vivres',
            'motivation' => "Témoin des difficultés économiques de ma région frontalière, je veux devenir économiste pour développer des stratégies de développement local.",
            'motivation_ai_score' => 8.1, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.94, 'rang_peub' => 74,
            'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()
        ]);
        $this->command->info('✅ Profil bachelier Mamadou Diarra créé avec succès!');
    }

    private function createBachelierGrace()
    {
        $user = $this->createUserIfNotExists(['email' => 'grace.assi@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $bachelier = $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id, 'nom' => 'Assi', 'prenoms' => 'Grâce', 'date_naissance' => '2005-06-08', 'lieu_naissance' => 'Adzopé', 'sexe' => 'F',
            'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250703574861', 'email_eleve' => 'grace.assi@example.com', 'region' => 'La Mé', 'commune' => 'Adzopé',
            'matricule_bac' => '2024-ADZ-003574', 'serie_bac' => 'A4', 'note_bac' => 17.70, 'mention' => 'bien', 'etablissement_nom' => 'Lycée d\'Excellence d\'Adzopé',
            'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Planteur de palmier', 'profession_mere' => 'Directrice d\'école',
            'motivation' => "Passionnée par l'éducation et l'accompagnement des jeunes, je veux devenir psychologue scolaire pour aider les élèves en difficulté.",
            'motivation_ai_score' => 8.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.13, 'rang_peub' => 102,
            'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()
        ]);
        $this->command->info('✅ Profil bachelier Grâce Assi créé avec succès!');
    }

    // Création des 20 autres profils avec format simplifié
    private function createBachelierOusmane()
    {
        $user = $this->createUserIfNotExists(['email' => 'ousmane.coulibaly@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Coulibaly', 'prenoms' => 'Ousmane', 'date_naissance' => '2004-04-12', 'lieu_naissance' => 'Boundiali', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705123456', 'email_eleve' => 'ousmane.coulibaly@example.com', 'region' => 'Bagoué', 'commune' => 'Boundiali', 'matricule_bac' => '2024-BOU-005123', 'serie_bac' => 'C', 'note_bac' => 17.30, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Boundiali', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Agriculteur', 'profession_mere' => 'Commerçante', 'motivation' => "Je veux devenir ingénieur informaticien pour développer des solutions technologiques adaptées aux besoins ruraux.", 'motivation_ai_score' => 8.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 86.75, 'rang_peub' => 105, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Ousmane Coulibaly créé avec succès!');
    }

    private function createBachelierMarie()
    {
        $user = $this->createUserIfNotExists(['email' => 'marie.beugre@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Beugré', 'prenoms' => 'Marie', 'date_naissance' => '2005-09-18', 'lieu_naissance' => 'Dabou', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706789123', 'email_eleve' => 'marie.beugre@example.com', 'region' => 'Grands Ponts', 'commune' => 'Dabou', 'matricule_bac' => '2024-DAB-006789', 'serie_bac' => 'D', 'note_bac' => 16.40, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Dabou', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Pêcheur', 'profession_mere' => 'Infirmière', 'motivation' => "Inspirée par ma mère infirmière, je veux devenir sage-femme pour accompagner les femmes dans la maternité.", 'motivation_ai_score' => 8.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.20, 'rang_peub' => 98, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Marie Beugré créé avec succès!');
    }

    private function createBachelierAdama()
    {
        $user = $this->createUserIfNotExists(['email' => 'adama.toure@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Touré', 'prenoms' => 'Adama', 'date_naissance' => '2004-11-25', 'lieu_naissance' => 'Touba', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250707894561', 'email_eleve' => 'adama.toure@example.com', 'region' => 'Bafing', 'commune' => 'Touba', 'matricule_bac' => '2024-TOU-007894', 'serie_bac' => 'C', 'note_bac' => 18.95, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Touba', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Imam', 'profession_mere' => 'Enseignante', 'motivation' => "Passionné par les mathématiques et la physique, je veux devenir astrophysicien pour contribuer à la recherche spatiale africaine.", 'motivation_ai_score' => 9.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 93.25, 'rang_peub' => 8, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Adama Touré créé avec succès!');
    }

    private function createBachelierEsther()
    {
        $user = $this->createUserIfNotExists(['email' => 'esther.gnakri@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Gnakri', 'prenoms' => 'Esther', 'date_naissance' => '2005-02-14', 'lieu_naissance' => 'Tiassalé', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708951753', 'email_eleve' => 'esther.gnakri@example.com', 'region' => 'Lôh-Djiboua', 'commune' => 'Tiassalé', 'matricule_bac' => '2024-TIA-008951', 'serie_bac' => 'A4', 'note_bac' => 17.25, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Tiassalé', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Pasteur', 'profession_mere' => 'Secrétaire', 'motivation' => "Je veux devenir travailleuse sociale pour aider les familles en difficulté et promouvoir le développement communautaire.", 'motivation_ai_score' => 8.4, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 85.80, 'rang_peub' => 128, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Esther Gnakri créé avec succès!');
    }

    private function createBachelierSidiki()
    {
        $user = $this->createUserIfNotExists(['email' => 'sidiki.fofana@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Fofana', 'prenoms' => 'Sidiki', 'date_naissance' => '2004-08-07', 'lieu_naissance' => 'Bouna', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250709517539', 'email_eleve' => 'sidiki.fofana@example.com', 'region' => 'Bounkani', 'commune' => 'Bouna', 'matricule_bac' => '2024-BOU-009517', 'serie_bac' => 'C', 'note_bac' => 16.80, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Bouna', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Éleveur', 'profession_mere' => 'Ménagère', 'motivation' => "Je veux étudier l'hydraulique pour apporter l'eau potable dans les villages reculés de l'est ivoirien.", 'motivation_ai_score' => 8.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 89.40, 'rang_peub' => 65, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Sidiki Fofana créé avec succès!');
    }

    private function createBachelierRose()
    {
        $user = $this->createUserIfNotExists(['email' => 'rose.gbagbo@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Gbagbo', 'prenoms' => 'Rose', 'date_naissance' => '2005-05-30', 'lieu_naissance' => 'Gagnoa', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250700159357', 'email_eleve' => 'rose.gbagbo@example.com', 'region' => 'Gôh', 'commune' => 'Gagnoa', 'matricule_bac' => '2024-GAG-000159', 'serie_bac' => 'D', 'note_bac' => 15.60, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Jeunes Filles de Gagnoa', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur', 'profession_mere' => 'Commerçante', 'motivation' => "Je veux devenir nutritionniste pour lutter contre la malnutrition infantile en milieu rural.", 'motivation_ai_score' => 8.2, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 86.30, 'rang_peub' => 115, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Rose Gbagbo créé avec succès!');
    }

    private function createBachelierAlassane()
    {
        $user = $this->createUserIfNotExists(['email' => 'alassane.kone@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Koné', 'prenoms' => 'Alassane', 'date_naissance' => '2004-12-11', 'lieu_naissance' => 'Mankono', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250701357951', 'email_eleve' => 'alassane.kone@example.com', 'region' => 'Béré', 'commune' => 'Mankono', 'matricule_bac' => '2024-MAN-001357', 'serie_bac' => 'C', 'note_bac' => 17.85, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Mankono', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Transporteur', 'profession_mere' => 'Cultivatrice', 'motivation' => "Je veux devenir ingénieur des mines pour développer l'extraction minière responsable en Côte d'Ivoire.", 'motivation_ai_score' => 8.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.65, 'rang_peub' => 76, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Alassane Koné créé avec succès!');
    }

    private function createBachelierVirginie()
    {
        $user = $this->createUserIfNotExists(['email' => 'virginie.dago@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Dago', 'prenoms' => 'Virginie', 'date_naissance' => '2005-07-23', 'lieu_naissance' => 'Bongouanou', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250702579513', 'email_eleve' => 'virginie.dago@example.com', 'region' => 'Moronou', 'commune' => 'Bongouanou', 'matricule_bac' => '2024-BON-002579', 'serie_bac' => 'A4', 'note_bac' => 16.95, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Bongouanou', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Fonctionnaire', 'profession_mere' => 'Pharmacienne', 'motivation' => "Je veux devenir juriste spécialisée en droit des affaires pour accompagner l'entrepreneuriat féminin.", 'motivation_ai_score' => 8.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 85.45, 'rang_peub' => 135, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Virginie Dago créé avec succès!');
    }

    private function createBachelierIssiaka()
    {
        $user = $this->createUserIfNotExists(['email' => 'issiaka.ouattara@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Ouattara', 'prenoms' => 'Issiaka', 'date_naissance' => '2004-03-16', 'lieu_naissance' => 'Doropo', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250703591357', 'email_eleve' => 'issiaka.ouattara@example.com', 'region' => 'Bounkani', 'commune' => 'Doropo', 'matricule_bac' => '2024-DOR-003591', 'serie_bac' => 'D', 'note_bac' => 15.30, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne de Doropo', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Berger', 'profession_mere' => 'Tisserande', 'motivation' => "Je veux devenir vétérinaire pour moderniser l'élevage et améliorer la santé animale dans ma région.", 'motivation_ai_score' => 8.0, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.75, 'rang_peub' => 91, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Issiaka Ouattara créé avec succès!');
    }

    private function createBachelierJuliette()
    {
        $user = $this->createUserIfNotExists(['email' => 'juliette.kacou@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Kacou', 'prenoms' => 'Juliette', 'date_naissance' => '2005-10-02', 'lieu_naissance' => 'Jacqueville', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250704135792', 'email_eleve' => 'juliette.kacou@example.com', 'region' => 'Grands Ponts', 'commune' => 'Jacqueville', 'matricule_bac' => '2024-JAC-004135', 'serie_bac' => 'A4', 'note_bac' => 17.05, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Jacqueville', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Pêcheur', 'profession_mere' => 'Gérante de maquis', 'motivation' => "Je veux devenir archéologue pour étudier et préserver le patrimoine historique de la Côte d'Ivoire.", 'motivation_ai_score' => 8.3, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 84.90, 'rang_peub' => 142, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Juliette Kacou créé avec succès!');
    }

    private function createBachelierSekou()
    {
        $user = $this->createUserIfNotExists(['email' => 'sekou.camara@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Camara', 'prenoms' => 'Sékou', 'date_naissance' => '2004-09-28', 'lieu_naissance' => 'Beyla', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250705792468', 'email_eleve' => 'sekou.camara@example.com', 'region' => 'Cavally', 'commune' => 'Guiglo', 'matricule_bac' => '2024-GUI-005792', 'serie_bac' => 'C', 'note_bac' => 16.15, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Guiglo', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Garde-chasse', 'profession_mere' => 'Institutrice', 'motivation' => "Je veux devenir ingénieur forestier pour contribuer à la gestion durable des forêts ivoiriennes.", 'motivation_ai_score' => 8.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.55, 'rang_peub' => 93, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Sékou Camara créé avec succès!');
    }

    private function createBachelierChristine()
    {
        $user = $this->createUserIfNotExists(['email' => 'christine.adjoua@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Adjoua', 'prenoms' => 'Christine', 'date_naissance' => '2005-04-05', 'lieu_naissance' => 'Lakota', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706824679', 'email_eleve' => 'christine.adjoua@example.com', 'region' => 'Lôh-Djiboua', 'commune' => 'Lakota', 'matricule_bac' => '2024-LAK-006824', 'serie_bac' => 'D', 'note_bac' => 18.35, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Lakota', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Médecin', 'profession_mere' => 'Avocate', 'motivation' => "Je veux devenir chirurgienne pour sauver des vies et former la nouvelle génération de médecins ivoiriens.", 'motivation_ai_score' => 9.3, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 91.80, 'rang_peub' => 25, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Christine Adjoua créé avec succès!');
    }

    private function createBachelierBakary()
    {
        $user = $this->createUserIfNotExists(['email' => 'bakary.sidibe@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Sidibé', 'prenoms' => 'Bakary', 'date_naissance' => '2004-06-21', 'lieu_naissance' => 'Zuénoula', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250707913468', 'email_eleve' => 'bakary.sidibe@example.com', 'region' => 'Marahoué', 'commune' => 'Zuénoula', 'matricule_bac' => '2024-ZUE-007913', 'serie_bac' => 'C', 'note_bac' => 19.20, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Zuénoula', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Ingénieur', 'profession_mere' => 'Banquière', 'motivation' => "Je veux devenir entrepreneur tech pour créer des solutions innovantes qui transformeront l'économie africaine.", 'motivation_ai_score' => 9.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 95.60, 'rang_peub' => 3, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Bakary Sidibé créé avec succès!');
    }

    // === BATCH 1 : 20 PROFILS SUPPLÉMENTAIRES (36-55) ===
    
    private function createBachelierSalimata()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'salimata.sacko@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Sacko',
            'prenoms' => 'Salimata',
            'date_naissance' => '2005-03-08',
            'lieu_naissance' => 'Odienné',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_salimata_sacko.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Sacko',
                'prenoms' => 'Salimata',
                'date_naissance' => '2005-03-08',
                'lieu_naissance' => 'Odienné',
                'numero' => 'CI-468359127-36'
            ],
            'telephone_eleve' => '+2250704683591',
            'telephone_parent' => '+2250704683592',
            'email_eleve' => 'salimata.sacko@example.com',
            'email_parent' => 'papa.sacko@example.com',
            'region' => 'Kabadougou',
            'commune' => 'Odienné',
            'matricule_bac' => '2024-ODI-004683',
            'serie_bac' => 'A4',
            'note_bac' => 16.20,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne d\'Odienné',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_salimata_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-ODI-004683',
                'serie' => 'A4',
                'note' => 16.20,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne d\'Odienné',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Imam',
            'profession_mere' => 'Alphabétiseuse',
            'situations_particulieres' => ['boursier_lycee', 'famille_religieuse'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Issue d'une famille religieuse du nord, je veux devenir traductrice-interprète pour faciliter la communication entre les communautés ivoiriennes et promouvoir le dialogue interculturel. Mon objectif est de travailler dans les organisations internationales.",
            'motivation_ai_score' => 8.6,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.86,
                'themes' => ['traduction', 'dialogue_interculturel', 'organisations_internationales'],
                'score_global' => 8.6
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-28',
            'bio' => "Future traductrice-interprète dédiée au dialogue interculturel.",
            'competences' => ['Français', 'Arabe', 'Anglais', 'Histoire-Géographie', 'Malinké'],
            'langues' => [
                'Français' => 'Courant',
                'Arabe' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Malinké' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_salimata_sacko.jpg',
            'cv_path' => 'cv_salimata_sacko.pdf',
            'score_academique' => 81.00,
            'score_geographique' => 98.00,
            'score_socio_economique' => 94.00,
            'score_motivations' => 86.00,
            'score_final_peub' => 89.75,
            'rang_peub' => 58,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 16.20,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 81.00
                ],
                'geographique' => [
                    'region' => 'Kabadougou',
                    'commune' => 'Odienné',
                    'score_region' => 93.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 98.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'imam',
                    'profession_mere' => 'alphabetiseuse',
                    'situations_particulieres' => ['boursier_lycee', 'famille_religieuse'],
                    'score_calcule' => 94.00
                ],
                'motivations' => [
                    'score_ia' => 8.6,
                    'longueur_texte' => 270,
                    'themes_identifies' => 3,
                    'score_calcule' => 86.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.91,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 38.7
            ]
        ]);

        $this->command->info('✅ Profil bachelier Salimata Sacko créé avec succès!');
    }

    private function createBachelierYoussou()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'youssou.ndour@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'N\'Dour',
            'prenoms' => 'Youssou',
            'date_naissance' => '2004-05-14',
            'lieu_naissance' => 'Abengourou',
            'sexe' => 'M',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'carte_scolaire_youssou.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'N\'Dour',
                'prenoms' => 'Youssou',
                'date_naissance' => '2004-05-14',
                'lieu_naissance' => 'Abengourou',
                'numero' => 'CS-591278463-37'
            ],
            'telephone_eleve' => '+2250705912784',
            'telephone_parent' => '+2250705912785',
            'email_eleve' => 'youssou.ndour@example.com',
            'email_parent' => 'maman.ndour@example.com',
            'region' => 'Indénié-Djuablin',
            'commune' => 'Abengourou',
            'matricule_bac' => '2024-ABE-005912',
            'serie_bac' => 'C',
            'note_bac' => 18.40,
            'mention' => 'tres_bien',
            'etablissement_nom' => 'Lycée Scientifique d\'Abengourou',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_youssou_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-ABE-005912',
                'serie' => 'C',
                'note' => 18.40,
                'mention' => 'tres_bien',
                'etablissement' => 'Lycée Scientifique d\'Abengourou',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Transporteur',
            'profession_mere' => 'Commerçante de café-cacao',
            'situations_particulieres' => ['excellent_eleve'],
            'possede_ordinateur' => true,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => true,
            'motivation' => "Passionné par les télécommunications et les réseaux, je veux devenir ingénieur en télécom pour améliorer la connectivité dans les zones rurales ivoiriennes. Mon projet est de développer des solutions innovantes pour la couverture réseau.",
            'motivation_ai_score' => 9.1,
            'motivation_ai_analysis' => [
                'sentiment' => 'tres_positif',
                'confiance' => 0.91,
                'themes' => ['telecommunications', 'connectivite_rurale', 'innovation'],
                'score_global' => 9.1
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-06',
            'bio' => "Futur ingénieur télécom spécialisé dans la connectivité rurale.",
            'competences' => ['Mathématiques', 'Physique', 'Électronique', 'Informatique', 'Agni'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Avancé',
                'Agni' => 'Maternelle'
            ],
            'photo' => 'photo_youssou_ndour.jpg',
            'cv_path' => 'cv_youssou_ndour.pdf',
            'score_academique' => 92.00,
            'score_geographique' => 86.00,
            'score_socio_economique' => 77.00,
            'score_motivations' => 91.00,
            'score_final_peub' => 86.50,
            'rang_peub' => 41,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 18.40,
                    'mention' => 'tres_bien',
                    'bonus_mention' => 10.0,
                    'score_calcule' => 92.00
                ],
                'geographique' => [
                    'region' => 'Indénié-Djuablin',
                    'commune' => 'Abengourou',
                    'score_region' => 81.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 86.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => false,
                    'profession_pere' => 'transporteur',
                    'profession_mere' => 'commercante_cafe_cacao',
                    'situations_particulieres' => ['excellent_eleve'],
                    'score_calcule' => 77.00
                ],
                'motivations' => [
                    'score_ia' => 9.1,
                    'longueur_texte' => 260,
                    'themes_identifies' => 3,
                    'score_calcule' => 91.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.93,
                'documents_traites' => ['carte_scolaire', 'collante_bac'],
                'temps_traitement' => 35.4
            ]
        ]);

        $this->command->info('✅ Profil bachelier Youssou N\'Dour créé avec succès!');
    }

    private function createBachelierNatasha()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'natasha.yapi@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Yapi',
            'prenoms' => 'Natasha',
            'date_naissance' => '2005-08-19',
            'lieu_naissance' => 'Grand-Lahou',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_natasha_yapi.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Yapi',
                'prenoms' => 'Natasha',
                'date_naissance' => '2005-08-19',
                'lieu_naissance' => 'Grand-Lahou',
                'numero' => 'CI-712846395-38'
            ],
            'telephone_eleve' => '+2250707128463',
            'telephone_parent' => '+2250707128464',
            'email_eleve' => 'natasha.yapi@example.com',
            'email_parent' => 'papa.yapi@example.com',
            'region' => 'Grands Ponts',
            'commune' => 'Grand-Lahou',
            'matricule_bac' => '2024-GLA-007128',
            'serie_bac' => 'D',
            'note_bac' => 17.65,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Grand-Lahou',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_natasha_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-GLA-007128',
                'serie' => 'D',
                'note' => 17.65,
                'mention' => 'bien',
                'etablissement' => 'Lycée Moderne de Grand-Lahou',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Pêcheur lagonaire',
            'profession_mere' => 'Fumeuse de poisson',
            'situations_particulieres' => ['boursier_lycee'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Vivant près de la lagune, je suis passionnée par l'océanographie et la biologie marine. Je veux étudier l'écosystème lagunaire ivoirien pour développer une pêche durable et protéger notre patrimoine aquatique.",
            'motivation_ai_score' => 8.9,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.89,
                'themes' => ['oceanographie', 'biologie_marine', 'peche_durable'],
                'score_global' => 8.9
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-24',
            'bio' => "Future océanographe spécialisée dans les écosystèmes lagunaires.",
            'competences' => ['Biologie', 'Chimie', 'Sciences de l\'environnement', 'Français', 'Avikam'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Avikam' => 'Maternelle'
            ],
            'photo' => 'photo_natasha_yapi.jpg',
            'cv_path' => 'cv_natasha_yapi.pdf',
            'score_academique' => 88.25,
            'score_geographique' => 89.00,
            'score_socio_economique' => 91.00,
            'score_motivations' => 89.00,
            'score_final_peub' => 89.31,
            'rang_peub' => 60,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 17.65,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 88.25
                ],
                'geographique' => [
                    'region' => 'Grands Ponts',
                    'commune' => 'Grand-Lahou',
                    'score_region' => 84.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 89.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'pecheur_lagonaire',
                    'profession_mere' => 'fumeuse_poisson',
                    'situations_particulieres' => ['boursier_lycee'],
                    'score_calcule' => 91.00
                ],
                'motivations' => [
                    'score_ia' => 8.9,
                    'longueur_texte' => 240,
                    'themes_identifies' => 3,
                    'score_calcule' => 89.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.90,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 42.1
            ]
        ]);

        $this->command->info('✅ Profil bachelier Natasha Yapi créé avec succès!');
    }

    private function createBachelierSouleymane()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'souleymane.barry@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Barry',
            'prenoms' => 'Souleymane',
            'date_naissance' => '2004-11-07',
            'lieu_naissance' => 'Bouna',
            'sexe' => 'M',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'carte_scolaire_souleymane.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Barry',
                'prenoms' => 'Souleymane',
                'date_naissance' => '2004-11-07',
                'lieu_naissance' => 'Bouna',
                'numero' => 'CS-463917285-39'
            ],
            'telephone_eleve' => '+2250704639172',
            'telephone_parent' => '+2250704639173',
            'email_eleve' => 'souleymane.barry@example.com',
            'email_parent' => 'papa.barry@example.com',
            'region' => 'Bounkani',
            'commune' => 'Bouna',
            'matricule_bac' => '2024-BOU-004639',
            'serie_bac' => 'C',
            'note_bac' => 15.75,
            'mention' => 'assez_bien',
            'etablissement_nom' => 'Lycée Moderne de Bouna',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_souleymane_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-BOU-004639',
                'serie' => 'C',
                'note' => 15.75,
                'mention' => 'assez_bien',
                'etablissement' => 'Lycée Moderne de Bouna',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Guide de chasse',
            'profession_mere' => 'Cultivatrice',
            'situations_particulieres' => ['boursier_lycee', 'zone_frontaliere'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Vivant dans une zone frontalière riche en faune, je veux devenir ingénieur en gestion de la faune pour développer l'écotourisme et protéger la biodiversité. Mon rêve est de créer des réserves communautaires.",
            'motivation_ai_score' => 8.4,
            'motivation_ai_analysis' => [
                'sentiment' => 'positif',
                'confiance' => 0.84,
                'themes' => ['gestion_faune', 'ecotourisme', 'biodiversite'],
                'score_global' => 8.4
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-30',
            'bio' => "Futur ingénieur en gestion de la faune et développement de l'écotourisme.",
            'competences' => ['Mathématiques', 'Sciences naturelles', 'Géographie', 'Français', 'Koulango'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Débutant',
                'Koulango' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_souleymane_barry.jpg',
            'cv_path' => 'cv_souleymane_barry.pdf',
            'score_academique' => 78.75,
            'score_geographique' => 96.00,
            'score_socio_economique' => 95.00,
            'score_motivations' => 84.00,
            'score_final_peub' => 88.44,
            'rang_peub' => 82,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 15.75,
                    'mention' => 'assez_bien',
                    'bonus_mention' => 2.0,
                    'score_calcule' => 78.75
                ],
                'geographique' => [
                    'region' => 'Bounkani',
                    'commune' => 'Bouna',
                    'score_region' => 91.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 96.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'guide_chasse',
                    'profession_mere' => 'cultivatrice',
                    'situations_particulieres' => ['boursier_lycee', 'zone_frontaliere'],
                    'score_calcule' => 95.00
                ],
                'motivations' => [
                    'score_ia' => 8.4,
                    'longueur_texte' => 230,
                    'themes_identifies' => 3,
                    'score_calcule' => 84.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.87,
                'documents_traites' => ['carte_scolaire', 'collante_bac'],
                'temps_traitement' => 44.3
            ]
        ]);

        $this->command->info('✅ Profil bachelier Souleymane Barry créé avec succès!');
    }

    private function createBachelierAya()
    {
        $user = $this->createUserIfNotExists([
            'email' => 'aya.diabate@example.com',
            'role' => 'bachelier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Diabaté',
            'prenoms' => 'Aya',
            'date_naissance' => '2005-06-25',
            'lieu_naissance' => 'Boundiali',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'cni_aya_diabate.pdf',
            'piece_identite_extracted_data' => [
                'nom' => 'Diabaté',
                'prenoms' => 'Aya',
                'date_naissance' => '2005-06-25',
                'lieu_naissance' => 'Boundiali',
                'numero' => 'CI-285174963-40'
            ],
            'telephone_eleve' => '+2250702851749',
            'telephone_parent' => '+2250702851750',
            'email_eleve' => 'aya.diabate@example.com',
            'email_parent' => 'maman.diabate@example.com',
            'region' => 'Bagoué',
            'commune' => 'Boundiali',
            'matricule_bac' => '2024-BOU-002851',
            'serie_bac' => 'A4',
            'note_bac' => 17.90,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Jeunes Filles de Boundiali',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'collante_bac_aya_2024.pdf',
            'collante_bac_extracted_data' => [
                'matricule' => '2024-BOU-002851',
                'serie' => 'A4',
                'note' => 17.90,
                'mention' => 'bien',
                'etablissement' => 'Lycée Jeunes Filles de Boundiali',
                'annee' => 2024
            ],
            'annee_bac' => 2024,
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Griot traditionnel',
            'profession_mere' => 'Artisane tisserande',
            'situations_particulieres' => ['boursier_lycee', 'famille_artistique'],
            'possede_ordinateur' => false,
            'connexion_internet' => '3g_4g',
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => "Issue d'une famille de griots, je veux étudier l'anthropologie culturelle pour préserver et valoriser le patrimoine oral ivoirien. Mon projet est de créer une archive numérique des traditions orales du nord.",
            'motivation_ai_score' => 9.0,
            'motivation_ai_analysis' => [
                'sentiment' => 'tres_positif',
                'confiance' => 0.90,
                'themes' => ['anthropologie', 'patrimoine_oral', 'traditions'],
                'score_global' => 9.0
            ],
            'boursier_peub' => true,
            'date_integration_peub' => '2024-09-01',
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => '2024-08-18',
            'bio' => "Future anthropologue spécialisée dans la préservation du patrimoine oral.",
            'competences' => ['Histoire-Géographie', 'Français', 'Philosophie', 'Arts traditionnels', 'Sénoufo'],
            'langues' => [
                'Français' => 'Courant',
                'Anglais' => 'Intermédiaire',
                'Sénoufo' => 'Maternelle',
                'Dioula' => 'Courant'
            ],
            'photo' => 'photo_aya_diabate.jpg',
            'cv_path' => 'cv_aya_diabate.pdf',
            'score_academique' => 89.50,
            'score_geographique' => 95.00,
            'score_socio_economique' => 92.00,
            'score_motivations' => 90.00,
            'score_final_peub' => 91.63,
            'rang_peub' => 32,
            'details_scoring' => [
                'academique' => [
                    'note_bac' => 17.90,
                    'mention' => 'bien',
                    'bonus_mention' => 5.0,
                    'score_calcule' => 89.50
                ],
                'geographique' => [
                    'region' => 'Bagoué',
                    'commune' => 'Boundiali',
                    'score_region' => 90.0,
                    'bonus_commune' => 5.0,
                    'score_calcule' => 95.00
                ],
                'socio_economique' => [
                    'bourse_lycee' => true,
                    'profession_pere' => 'griot_traditionnel',
                    'profession_mere' => 'artisane_tisserande',
                    'situations_particulieres' => ['boursier_lycee', 'famille_artistique'],
                    'score_calcule' => 92.00
                ],
                'motivations' => [
                    'score_ia' => 9.0,
                    'longueur_texte' => 250,
                    'themes_identifies' => 3,
                    'score_calcule' => 90.00
                ]
            ],
            'date_calcul_scoring' => now(),
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'ai_extraction_metadata' => [
                'version_model' => '1.0',
                'confiance_globale' => 0.92,
                'documents_traites' => ['cni', 'collante_bac'],
                'temps_traitement' => 36.8
            ]
        ]);

        $this->command->info('✅ Profil bachelier Aya Diabaté créé avec succès!');
    }

    // Format simplifié pour les 15 suivants du batch 1
    private function createBachelierKouame()
    {
        $user = $this->createUserIfNotExists(['email' => 'kouame.francis@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Kouamé', 'prenoms' => 'Francis', 'date_naissance' => '2004-09-12', 'lieu_naissance' => 'Sassandra', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708174259', 'email_eleve' => 'kouame.francis@example.com', 'region' => 'San-Pédro', 'commune' => 'Sassandra', 'matricule_bac' => '2024-SAS-008174', 'serie_bac' => 'C', 'note_bac' => 16.85, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Sassandra', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Marin pêcheur', 'profession_mere' => 'Transformatrice de poisson', 'motivation' => "Je veux devenir ingénieur naval pour moderniser la pêche artisanale et développer l'industrie maritime ivoirienne.", 'motivation_ai_score' => 8.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.25, 'rang_peub' => 88, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Kouamé Francis créé avec succès!');
    }

    private function createBachelierRachelle()
    {
        $user = $this->createUserIfNotExists(['email' => 'rachelle.guei@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Guéi', 'prenoms' => 'Rachelle', 'date_naissance' => '2005-04-03', 'lieu_naissance' => 'Duékoué', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706395127', 'email_eleve' => 'rachelle.guei@example.com', 'region' => 'Guémon', 'commune' => 'Duékoué', 'matricule_bac' => '2024-DUE-006395', 'serie_bac' => 'D', 'note_bac' => 18.15, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Moderne de Duékoué', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur de café', 'profession_mere' => 'Infirmière', 'motivation' => "Je veux devenir médecin généraliste pour servir les populations rurales de l'ouest, particulièrement les réfugiés et déplacés internes.", 'motivation_ai_score' => 9.2, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 92.40, 'rang_peub' => 15, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Rachelle Guéi créé avec succès!');
    }

    private function createBachelierIbrahima()
    {
        $user = $this->createUserIfNotExists(['email' => 'ibrahima.konate@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Konaté', 'prenoms' => 'Ibrahima', 'date_naissance' => '2004-07-21', 'lieu_naissance' => 'Ferkessédougou', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250705271849', 'email_eleve' => 'ibrahima.konate@example.com', 'region' => 'Tchologo', 'commune' => 'Ferkessédougou', 'matricule_bac' => '2024-FER-005271', 'serie_bac' => 'C', 'note_bac' => 17.30, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Technique de Ferkessédougou', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Chauffeur routier', 'profession_mere' => 'Vendeuse de céréales', 'motivation' => "Passionné par les transports et la logistique, je veux moderniser le transport routier en Afrique de l'Ouest avec des solutions durables.", 'motivation_ai_score' => 8.3, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 86.15, 'rang_peub' => 112, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Ibrahima Konaté créé avec succès!');
    }

    private function createBachelierLorraine()
    {
        $user = $this->createUserIfNotExists(['email' => 'lorraine.tape@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Tapé', 'prenoms' => 'Lorraine', 'date_naissance' => '2005-11-16', 'lieu_naissance' => 'Dabakala', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250704852917', 'email_eleve' => 'lorraine.tape@example.com', 'region' => 'Hambol', 'commune' => 'Dabakala', 'matricule_bac' => '2024-DAB-004852', 'serie_bac' => 'A4', 'note_bac' => 16.40, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Dabakala', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Berger peul', 'profession_mere' => 'Vendeuse de lait', 'motivation' => "Je veux devenir sociologue pour étudier la cohabitation entre communautés sédentaires et nomades et promouvoir la paix sociale.", 'motivation_ai_score' => 8.5, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.10, 'rang_peub' => 86, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Lorraine Tapé créé avec succès!');
    }

    private function createBachelierMoussa()
    {
        $user = $this->createUserIfNotExists(['email' => 'moussa.bamba@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Bamba', 'prenoms' => 'Moussa', 'date_naissance' => '2004-12-28', 'lieu_naissance' => 'Toumodi', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250703974185', 'email_eleve' => 'moussa.bamba@example.com', 'region' => 'Bélier', 'commune' => 'Toumodi', 'matricule_bac' => '2024-TOU-003974', 'serie_bac' => 'C', 'note_bac' => 19.10, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Toumodi', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Directeur d\'école', 'profession_mere' => 'Sage-femme', 'motivation' => "Excellent élève, je veux devenir ingénieur aérospatial pour participer au développement des technologies spatiales en Afrique.", 'motivation_ai_score' => 9.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 94.25, 'rang_peub' => 6, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Moussa Bamba créé avec succès!');
    }

    private function createBachelierBrigitte()
    {
        $user = $this->createUserIfNotExists(['email' => 'brigitte.esso@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Esso', 'prenoms' => 'Brigitte', 'date_naissance' => '2005-02-09', 'lieu_naissance' => 'Aboisso', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250701856429', 'email_eleve' => 'brigitte.esso@example.com', 'region' => 'Sud-Comoé', 'commune' => 'Aboisso', 'matricule_bac' => '2024-ABO-001856', 'serie_bac' => 'D', 'note_bac' => 15.90, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne d\'Aboisso', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur de palmier', 'profession_mere' => 'Commerçante frontalière', 'motivation' => "Je veux devenir pharmacienne pour améliorer l'accès aux médicaments dans les zones frontalières du sud-est.", 'motivation_ai_score' => 8.1, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 85.48, 'rang_peub' => 132, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Brigitte Esso créé avec succès!');
    }

    private function createBachelierElhadji()
    {
        $user = $this->createUserIfNotExists(['email' => 'elhadji.sawadogo@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Sawadogo', 'prenoms' => 'El-hadji', 'date_naissance' => '2004-08-15', 'lieu_naissance' => 'Korhogo', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250702749163', 'email_eleve' => 'elhadji.sawadogo@example.com', 'region' => 'Poro', 'commune' => 'Korhogo', 'matricule_bac' => '2024-KOR-002749', 'serie_bac' => 'C', 'note_bac' => 18.75, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Scientifique de Korhogo', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Commerçant de bétail', 'profession_mere' => 'Tisserande', 'motivation' => "Je veux devenir ingénieur en énergies renouvelables pour développer l'énergie solaire dans le nord de la Côte d'Ivoire.", 'motivation_ai_score' => 9.0, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 91.88, 'rang_peub' => 27, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier El-hadji Sawadogo créé avec succès!');
    }

    private function createBachelierJoelle()
    {
        $user = $this->createUserIfNotExists(['email' => 'joelle.kone@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Koné', 'prenoms' => 'Joëlle', 'date_naissance' => '2005-05-22', 'lieu_naissance' => 'Séguéla', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706174285', 'email_eleve' => 'joelle.kone@example.com', 'region' => 'Worodougou', 'commune' => 'Séguéla', 'matricule_bac' => '2024-SEG-006174', 'serie_bac' => 'A4', 'note_bac' => 17.25, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Séguéla', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Orpailleur artisanal', 'profession_mere' => 'Cultivatrice', 'motivation' => "Je veux étudier les sciences politiques pour devenir diplomate et représenter l'Afrique dans les instances internationales.", 'motivation_ai_score' => 8.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 89.06, 'rang_peub' => 71, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Joëlle Koné créé avec succès!');
    }

    private function createBachelierDaouda()
    {
        $user = $this->createUserIfNotExists(['email' => 'daouda.sanogo@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Sanogo', 'prenoms' => 'Daouda', 'date_naissance' => '2004-10-04', 'lieu_naissance' => 'Mankono', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708426159', 'email_eleve' => 'daouda.sanogo@example.com', 'region' => 'Béré', 'commune' => 'Mankono', 'matricule_bac' => '2024-MAN-008426', 'serie_bac' => 'C', 'note_bac' => 16.10, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Mankono', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Mécanicien rural', 'profession_mere' => 'Commerçante', 'motivation' => "Je veux devenir ingénieur en génie rural pour mécaniser l'agriculture et améliorer la productivité agricole.", 'motivation_ai_score' => 8.4, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 86.05, 'rang_peub' => 118, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Daouda Sanogo créé avec succès!');
    }

    private function createBachelierCaroline()
    {
        $user = $this->createUserIfNotExists(['email' => 'caroline.ahizi@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Ahizi', 'prenoms' => 'Caroline', 'date_naissance' => '2005-07-13', 'lieu_naissance' => 'Akoupé', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705837291', 'email_eleve' => 'caroline.ahizi@example.com', 'region' => 'La Mé', 'commune' => 'Akoupé', 'matricule_bac' => '2024-AKO-005837', 'serie_bac' => 'D', 'note_bac' => 17.85, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne d\'Akoupé', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur d\'hévéa', 'profession_mere' => 'Institutrice', 'motivation' => "Je veux devenir ingénieure agronome spécialisée dans la culture de l'hévéa pour optimiser la production de caoutchouc.", 'motivation_ai_score' => 8.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.94, 'rang_peub' => 87, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Caroline Ahizi créé avec succès!');
    }

    private function createBachelierLassana()
    {
        $user = $this->createUserIfNotExists(['email' => 'lassana.dembele@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Dembélé', 'prenoms' => 'Lassana', 'date_naissance' => '2004-04-27', 'lieu_naissance' => 'Tingréla', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250702594817', 'email_eleve' => 'lassana.dembele@example.com', 'region' => 'Poro', 'commune' => 'Tingréla', 'matricule_bac' => '2024-TIN-002594', 'serie_bac' => 'C', 'note_bac' => 15.45, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne de Tingréla', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Forgeron traditionnel', 'profession_mere' => 'Potière', 'motivation' => "Fils d'artisan forgeron, je veux étudier la métallurgie moderne pour allier savoir traditionnel et techniques industrielles.", 'motivation_ai_score' => 8.2, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 84.61, 'rang_peub' => 148, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Lassana Dembélé créé avec succès!');
    }

    private function createBachelierSandra()
    {
        $user = $this->createUserIfNotExists(['email' => 'sandra.gnahoua@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Gnahoua', 'prenoms' => 'Sandra', 'date_naissance' => '2005-09-08', 'lieu_naissance' => 'Bonoua', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250704176328', 'email_eleve' => 'sandra.gnahoua@example.com', 'region' => 'Sud-Comoé', 'commune' => 'Bonoua', 'matricule_bac' => '2024-BON-004176', 'serie_bac' => 'A4', 'note_bac' => 18.20, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Bonoua', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Banquier', 'profession_mere' => 'Avocate', 'motivation' => "Je veux étudier le droit international pour devenir juge à la Cour Pénale Internationale et lutter contre l'impunité.", 'motivation_ai_score' => 9.4, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 90.10, 'rang_peub' => 13, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Sandra Gnahoua créé avec succès!');
    }

    private function createBachelierTiemoko()
    {
        $user = $this->createUserIfNotExists(['email' => 'tiemoko.coulibaly@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Coulibaly', 'prenoms' => 'Tiémoko', 'date_naissance' => '2004-06-19', 'lieu_naissance' => 'Kong', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250703851947', 'email_eleve' => 'tiemoko.coulibaly@example.com', 'region' => 'Tchologo', 'commune' => 'Kong', 'matricule_bac' => '2024-KON-003851', 'serie_bac' => 'C', 'note_bac' => 17.60, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Kong', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Guide touristique', 'profession_mere' => 'Gérante d\'auberge', 'motivation' => "Je veux développer l'architecture touristique durable pour valoriser le patrimoine historique de Kong et du nord ivoirien.", 'motivation_ai_score' => 8.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.40, 'rang_peub' => 78, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Tiémoko Coulibaly créé avec succès!');
    }

    private function createBachelierAngelique()
    {
        $user = $this->createUserIfNotExists(['email' => 'angelique.koffi@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Koffi', 'prenoms' => 'Angélique', 'date_naissance' => '2005-01-24', 'lieu_naissance' => 'Zuénoula', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250707419682', 'email_eleve' => 'angelique.koffi@example.com', 'region' => 'Marahoué', 'commune' => 'Zuénoula', 'matricule_bac' => '2024-ZUE-007419', 'serie_bac' => 'D', 'note_bac' => 16.75, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Zuénoula', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur de cacao', 'profession_mere' => 'Transformatrice de manioc', 'motivation' => "Je veux devenir nutritionniste pour lutter contre la malnutrition et promouvoir une alimentation saine à base de produits locaux.", 'motivation_ai_score' => 8.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.19, 'rang_peub' => 103, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Angélique Koffi créé avec succès!');
    }

    private function createBachelierDrissa()
    {
        $user = $this->createUserIfNotExists(['email' => 'drissa.ouattara@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Ouattara', 'prenoms' => 'Drissa', 'date_naissance' => '2004-03-11', 'lieu_naissance' => 'Vavoua', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250701263849', 'email_eleve' => 'drissa.ouattara@example.com', 'region' => 'Haut-Sassandra', 'commune' => 'Vavoua', 'matricule_bac' => '2024-VAV-001263', 'serie_bac' => 'C', 'note_bac' => 18.95, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Vavoua', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Entrepreneur BTP', 'profession_mere' => 'Architecte', 'motivation' => "Fils d'entrepreneur, je veux créer une entreprise tech qui révolutionnera la construction avec des matériaux locaux et durables.", 'motivation_ai_score' => 9.5, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 93.88, 'rang_peub' => 7, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Drissa Ouattara créé avec succès!');
    }

    // === BATCH 2 : 20 PROFILS SUPPLÉMENTAIRES (56-75) ===
    
    private function createBachelierConstance()
    {
        $user = $this->createUserIfNotExists(['email' => 'constance.brou@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Brou', 'prenoms' => 'Constance', 'date_naissance' => '2005-03-14', 'lieu_naissance' => 'Yamoussoukro', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705184726', 'email_eleve' => 'constance.brou@example.com', 'region' => 'Yamoussoukro', 'commune' => 'Yamoussoukro', 'matricule_bac' => '2024-YAM-005184', 'serie_bac' => 'A4', 'note_bac' => 17.95, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Scientifique de Yamoussoukro', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Ministre', 'profession_mere' => 'Magistrate', 'motivation' => "Née dans la capitale politique, je veux étudier les relations internationales pour devenir ambassadrice de la Côte d'Ivoire.", 'motivation_ai_score' => 9.1, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 91.25, 'rang_peub' => 29, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Constance Brou créé avec succès!');
    }

    private function createBachelierSita()
    {
        $user = $this->createUserIfNotExists(['email' => 'sita.traore@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Traoré', 'prenoms' => 'Sita', 'date_naissance' => '2005-01-09', 'lieu_naissance' => 'Sinématiali', 'sexe' => 'F', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250706273841', 'email_eleve' => 'sita.traore@example.com', 'region' => 'Poro', 'commune' => 'Sinématiali', 'matricule_bac' => '2024-SIN-006273', 'serie_bac' => 'D', 'note_bac' => 16.30, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Sinématiali', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Vétérinaire rural', 'profession_mere' => 'Sage-femme', 'motivation' => "Je veux devenir médecin-vétérinaire pour développer la médecine vétérinaire moderne dans le nord ivoirien.", 'motivation_ai_score' => 8.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 90.15, 'rang_peub' => 52, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Sita Traoré créé avec succès!');
    }

    private function createBachelierFrancis()
    {
        $user = $this->createUserIfNotExists(['email' => 'francis.gnagne@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Gnagné', 'prenoms' => 'Francis', 'date_naissance' => '2004-12-22', 'lieu_naissance' => 'Sinfra', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250707392618', 'email_eleve' => 'francis.gnagne@example.com', 'region' => 'Marahoué', 'commune' => 'Sinfra', 'matricule_bac' => '2024-SIN-007392', 'serie_bac' => 'C', 'note_bac' => 18.60, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Sinfra', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Pharmacien', 'profession_mere' => 'Comptable', 'motivation' => "Je veux devenir ingénieur biomédical pour développer des technologies médicales adaptées aux besoins africains.", 'motivation_ai_score' => 9.3, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 92.75, 'rang_peub' => 16, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Francis Gnagné créé avec succès!');
    }

    private function createBachelierNdeye()
    {
        $user = $this->createUserIfNotExists(['email' => 'ndeye.fall@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Fall', 'prenoms' => 'Ndèye', 'date_naissance' => '2005-08-03', 'lieu_naissance' => 'Tabou', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708615394', 'email_eleve' => 'ndeye.fall@example.com', 'region' => 'San-Pédro', 'commune' => 'Tabou', 'matricule_bac' => '2024-TAB-008615', 'serie_bac' => 'A4', 'note_bac' => 16.90, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Tabou', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Docker', 'profession_mere' => 'Vendeuse de poisson', 'motivation' => "Je veux devenir journaliste internationale pour couvrir les questions portuaires et maritimes en Afrique de l'Ouest.", 'motivation_ai_score' => 8.5, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.63, 'rang_peub' => 75, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Ndèye Fall créé avec succès!');
    }

    private function createBachelierSeydou()
    {
        $user = $this->createUserIfNotExists(['email' => 'seydou.keita@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Keita', 'prenoms' => 'Seydou', 'date_naissance' => '2004-04-16', 'lieu_naissance' => 'Boundiali', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250704729851', 'email_eleve' => 'seydou.keita@example.com', 'region' => 'Bagoué', 'commune' => 'Boundiali', 'matricule_bac' => '2024-BOU-004729', 'serie_bac' => 'C', 'note_bac' => 19.35, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Boundiali', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Professeur de mathématiques', 'profession_mere' => 'Directrice d\'école', 'motivation' => "Excellent en sciences, je veux devenir astrophysicien pour contribuer au développement de l'astronomie en Afrique.", 'motivation_ai_score' => 9.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 96.20, 'rang_peub' => 2, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Seydou Keita créé avec succès!');
    }

    private function createBachelierMireille()
    {
        $user = $this->createUserIfNotExists(['email' => 'mireille.akoun@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Akoun', 'prenoms' => 'Mireille', 'date_naissance' => '2005-06-28', 'lieu_naissance' => 'Anyama', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250703847592', 'email_eleve' => 'mireille.akoun@example.com', 'region' => 'Autonome d\'Abidjan', 'commune' => 'Anyama', 'matricule_bac' => '2024-ANY-003847', 'serie_bac' => 'D', 'note_bac' => 17.40, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne d\'Anyama', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Médecin', 'profession_mere' => 'Pharmacienne', 'motivation' => "Issue d'une famille médicale, je veux devenir chirurgienne cardiaque pour sauver des vies en Côte d'Ivoire.", 'motivation_ai_score' => 9.0, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 85.75, 'rang_peub' => 45, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Mireille Akoun créé avec succès!');
    }

    private function createBachelierBoubacar()
    {
        $user = $this->createUserIfNotExists(['email' => 'boubacar.cisse@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Cissé', 'prenoms' => 'Boubacar', 'date_naissance' => '2004-09-07', 'lieu_naissance' => 'Katiola', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705936274', 'email_eleve' => 'boubacar.cisse@example.com', 'region' => 'Hambol', 'commune' => 'Katiola', 'matricule_bac' => '2024-KAT-005936', 'serie_bac' => 'C', 'note_bac' => 16.55, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Technique de Katiola', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Électricien', 'profession_mere' => 'Couturière', 'motivation' => "Je veux devenir ingénieur électricien pour améliorer la distribution électrique dans les zones rurales.", 'motivation_ai_score' => 8.4, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 85.78, 'rang_peub' => 126, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Boubacar Cissé créé avec succès!');
    }

    private function createBachelierBernadette()
    {
        $user = $this->createUserIfNotExists(['email' => 'bernadette.kouassi@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Kouassi', 'prenoms' => 'Bernadette', 'date_naissance' => '2005-02-17', 'lieu_naissance' => 'Béoumi', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250702641859', 'email_eleve' => 'bernadette.kouassi@example.com', 'region' => 'Gbêkê', 'commune' => 'Béoumi', 'matricule_bac' => '2024-BEO-002641', 'serie_bac' => 'A4', 'note_bac' => 15.80, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne de Béoumi', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Pasteur', 'profession_mere' => 'Enseignante', 'motivation' => "Je veux devenir théologienne et travailler pour le dialogue inter-religieux en Côte d'Ivoire.", 'motivation_ai_score' => 8.1, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 84.45, 'rang_peub' => 151, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Bernadette Kouassi créé avec succès!');
    }

    private function createBachelierFofie()
    {
        $user = $this->createUserIfNotExists(['email' => 'fofie.doumbia@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Doumbia', 'prenoms' => 'Fofié', 'date_naissance' => '2004-07-09', 'lieu_naissance' => 'Touba', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250708517364', 'email_eleve' => 'fofie.doumbia@example.com', 'region' => 'Bafing', 'commune' => 'Touba', 'matricule_bac' => '2024-TOU-008517', 'serie_bac' => 'C', 'note_bac' => 17.75, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Islamique de Touba', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Marabout', 'profession_mere' => 'Tisserande', 'motivation' => "Je veux étudier l'informatique pour développer des applications éducatives en langues locales.", 'motivation_ai_score' => 8.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 90.44, 'rang_peub' => 48, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Fofié Doumbia créé avec succès!');
    }

    private function createBachelierMelanie()
    {
        $user = $this->createUserIfNotExists(['email' => 'melanie.anoh@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Anoh', 'prenoms' => 'Mélanie', 'date_naissance' => '2005-10-31', 'lieu_naissance' => 'Alepe', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706428137', 'email_eleve' => 'melanie.anoh@example.com', 'region' => 'La Mé', 'commune' => 'Alépé', 'matricule_bac' => '2024-ALE-006428', 'serie_bac' => 'D', 'note_bac' => 18.05, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Jeunes Filles d\'Alépé', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur de palmier', 'profession_mere' => 'Sage-femme', 'motivation' => "Je veux devenir gynécologue-obstétricienne pour réduire la mortalité maternelle en milieu rural.", 'motivation_ai_score' => 9.1, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 91.01, 'rang_peub' => 35, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Mélanie Anoh créé avec succès!');
    }

    private function createBachelierDjakaridja()
    {
        $user = $this->createUserIfNotExists(['email' => 'djakaridja.kone@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Koné', 'prenoms' => 'Djakaridja', 'date_naissance' => '2004-05-25', 'lieu_naissance' => 'Séguéla', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250701753968', 'email_eleve' => 'djakaridja.kone@example.com', 'region' => 'Worodougou', 'commune' => 'Séguéla', 'matricule_bac' => '2024-SEG-001753', 'serie_bac' => 'C', 'note_bac' => 15.20, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne de Séguéla', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Orpailleur', 'profession_mere' => 'Vendeuse de mil', 'motivation' => "Je veux étudier la géologie pour moderniser l'extraction minière artisanale et la rendre plus sûre.", 'motivation_ai_score' => 8.2, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 86.80, 'rang_peub' => 107, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Djakaridja Koné créé avec succès!');
    }

    private function createBachelierClementine()
    {
        $user = $this->createUserIfNotExists(['email' => 'clementine.bah@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Bah', 'prenoms' => 'Clémentine', 'date_naissance' => '2005-04-12', 'lieu_naissance' => 'Biankouma', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250707269583', 'email_eleve' => 'clementine.bah@example.com', 'region' => 'Tonkpi', 'commune' => 'Biankouma', 'matricule_bac' => '2024-BIA-007269', 'serie_bac' => 'A4', 'note_bac' => 16.65, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Biankouma', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Chasseur traditionnel', 'profession_mere' => 'Herboriste', 'motivation' => "Je veux étudier l'ethnopharmacologie pour valoriser les plantes médicinales de l'ouest montagneux.", 'motivation_ai_score' => 8.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 89.16, 'rang_peub' => 69, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Clémentine Bah créé avec succès!');
    }

    private function createBachelierNoufou()
    {
        $user = $this->createUserIfNotExists(['email' => 'noufou.ouedraogo@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Ouédraogo', 'prenoms' => 'Noufou', 'date_naissance' => '2004-01-18', 'lieu_naissance' => 'Niakara', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250703851749', 'email_eleve' => 'noufou.ouedraogo@example.com', 'region' => 'Hambol', 'commune' => 'Niakara', 'matricule_bac' => '2024-NIA-003851', 'serie_bac' => 'C', 'note_bac' => 17.10, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Niakara', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Éleveur de bœufs', 'profession_mere' => 'Transformatrice de karité', 'motivation' => "Je veux devenir ingénieur zootechnicien pour moderniser l'élevage bovin dans le centre-nord.", 'motivation_ai_score' => 8.5, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.63, 'rang_peub' => 90, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Noufou Ouédraogo créé avec succès!');
    }

    private function createBachelierPhilomene()
    {
        $user = $this->createUserIfNotExists(['email' => 'philomene.yobo@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Yobo', 'prenoms' => 'Philomène', 'date_naissance' => '2005-09-26', 'lieu_naissance' => 'Grand-Bereby', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708394716', 'email_eleve' => 'philomene.yobo@example.com', 'region' => 'San-Pédro', 'commune' => 'Grand-Bereby', 'matricule_bac' => '2024-GBE-008394', 'serie_bac' => 'D', 'note_bac' => 16.45, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Grand-Bereby', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur de cacao', 'profession_mere' => 'Infirmière', 'motivation' => "Je veux devenir pédiatre pour améliorer la santé des enfants dans les plantations de cacao.", 'motivation_ai_score' => 8.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.11, 'rang_peub' => 85, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Philomène Yobo créé avec succès!');
    }

    private function createBachelierBruno()
    {
        $user = $this->createUserIfNotExists(['email' => 'bruno.koffi@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Koffi', 'prenoms' => 'Bruno', 'date_naissance' => '2004-11-14', 'lieu_naissance' => 'Daloa', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705729148', 'email_eleve' => 'bruno.koffi@example.com', 'region' => 'Haut-Sassandra', 'commune' => 'Daloa', 'matricule_bac' => '2024-DAL-005729', 'serie_bac' => 'C', 'note_bac' => 18.85, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Scientifique de Daloa', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Ingénieur', 'profession_mere' => 'Médecin', 'motivation' => "Je veux devenir ingénieur logiciel pour créer des solutions tech innovantes depuis l'intérieur du pays.", 'motivation_ai_score' => 9.2, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 91.71, 'rang_peub' => 28, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Bruno Koffi créé avec succès!');
    }

    private function createBachelierMohamed()
    {
        $user = $this->createUserIfNotExists(['email' => 'mohamed.dosso@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Dosso', 'prenoms' => 'Mohamed', 'date_naissance' => '2005-07-02', 'lieu_naissance' => 'Minignan', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250702649371', 'email_eleve' => 'mohamed.dosso@example.com', 'region' => 'Folon', 'commune' => 'Minignan', 'matricule_bac' => '2024-MIN-002649', 'serie_bac' => 'A4', 'note_bac' => 15.95, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne de Minignan', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Commerçant frontalier', 'profession_mere' => 'Cultivatrice', 'motivation' => "Vivant à la frontière, je veux étudier les relations internationales pour faciliter les échanges sous-régionaux.", 'motivation_ai_score' => 8.3, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.24, 'rang_peub' => 101, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Mohamed Dosso créé avec succès!');
    }

    private function createBachelierLaetitia()
    {
        $user = $this->createUserIfNotExists(['email' => 'laetitia.diomande@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Diomandé', 'prenoms' => 'Laetitia', 'date_naissance' => '2005-12-08', 'lieu_naissance' => 'Bocanda', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250704185729', 'email_eleve' => 'laetitia.diomande@example.com', 'region' => 'N\'zi', 'commune' => 'Bocanda', 'matricule_bac' => '2024-BOC-004185', 'serie_bac' => 'D', 'note_bac' => 17.20, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Bocanda', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Infirmier', 'profession_mere' => 'Commerçante', 'motivation' => "Je veux devenir dentiste pour améliorer la santé bucco-dentaire dans les zones rurales.", 'motivation_ai_score' => 8.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 86.80, 'rang_peub' => 106, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Laetitia Diomandé créé avec succès!');
    }

    private function createBachelierSanogo()
    {
        $user = $this->createUserIfNotExists(['email' => 'sanogo.bakary@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Sanogo', 'prenoms' => 'Bakary', 'date_naissance' => '2004-08-20', 'lieu_naissance' => 'Bouaflé', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706374825', 'email_eleve' => 'sanogo.bakary@example.com', 'region' => 'Marahoué', 'commune' => 'Bouaflé', 'matricule_bac' => '2024-BOU-006374', 'serie_bac' => 'C', 'note_bac' => 16.75, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Bouaflé', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur d\'igname', 'profession_mere' => 'Transformatrice', 'motivation' => "Je veux devenir ingénieur agro-alimentaire pour valoriser les tubercules locaux comme l'igname.", 'motivation_ai_score' => 8.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.19, 'rang_peub' => 104, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Sanogo Bakary créé avec succès!');
    }

    private function createBachelierPerle()
    {
        $user = $this->createUserIfNotExists(['email' => 'perle.kouame@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Kouamé', 'prenoms' => 'Perle', 'date_naissance' => '2005-01-30', 'lieu_naissance' => 'San-Pedro', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708526147', 'email_eleve' => 'perle.kouame@example.com', 'region' => 'San-Pédro', 'commune' => 'San-Pedro', 'matricule_bac' => '2024-SAP-008526', 'serie_bac' => 'A4', 'note_bac' => 18.30, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de San-Pedro', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Directeur de port', 'profession_mere' => 'Commissaire aux comptes', 'motivation' => "Je veux étudier le commerce international pour développer les échanges via le port de San-Pedro.", 'motivation_ai_score' => 9.0, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 90.75, 'rang_peub' => 39, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Perle Kouamé créé avec succès!');
    }

    // === BATCH 3 : 20 PROFILS SUPPLÉMENTAIRES (76-95) ===
    
    private function createBachelierMamadou2()
    {
        $user = $this->createUserIfNotExists(['email' => 'mamadou2.toure@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Touré', 'prenoms' => 'Mamadou', 'date_naissance' => '2004-02-14', 'lieu_naissance' => 'Odienné', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250703725819', 'email_eleve' => 'mamadou2.toure@example.com', 'region' => 'Kabadougou', 'commune' => 'Odienné', 'matricule_bac' => '2024-ODI-003725', 'serie_bac' => 'C', 'note_bac' => 17.85, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Technique d\'Odienné', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Mécanicien de motos', 'profession_mere' => 'Commerçante', 'motivation' => "Je veux devenir ingénieur mécanique pour développer des moyens de transport adaptés aux routes rurales du nord.", 'motivation_ai_score' => 8.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 89.28, 'rang_peub' => 63, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Mamadou Touré créé avec succès!');
    }

    private function createBachelierVictoire()
    {
        $user = $this->createUserIfNotExists(['email' => 'victoire.nagni@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Nagni', 'prenoms' => 'Victoire', 'date_naissance' => '2005-05-21', 'lieu_naissance' => 'Soubré', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708416372', 'email_eleve' => 'victoire.nagni@example.com', 'region' => 'Nawa', 'commune' => 'Soubré', 'matricule_bac' => '2024-SOU-008416', 'serie_bac' => 'A4', 'note_bac' => 16.25, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Soubré', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur de cacao', 'profession_mere' => 'Présidente coopérative', 'motivation' => "Je veux devenir économiste agricole pour améliorer les revenus des planteurs de cacao.", 'motivation_ai_score' => 8.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.56, 'rang_peub' => 77, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Victoire Nagni créé avec succès!');
    }

    private function createBachelierClement()
    {
        $user = $this->createUserIfNotExists(['email' => 'clement.boli@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Boli', 'prenoms' => 'Clément', 'date_naissance' => '2004-10-07', 'lieu_naissance' => 'Bangolo', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250705927364', 'email_eleve' => 'clement.boli@example.com', 'region' => 'Guémon', 'commune' => 'Bangolo', 'matricule_bac' => '2024-BAN-005927', 'serie_bac' => 'C', 'note_bac' => 16.90, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Bangolo', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Artisan sculpteur', 'profession_mere' => 'Tisserande', 'motivation' => "Je veux étudier l'architecture pour allier art traditionnel et construction moderne dans l'ouest montagneux.", 'motivation_ai_score' => 8.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.68, 'rang_peub' => 89, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Clément Boli créé avec succès!');
    }

    private function createBachelierDjeneba()
    {
        $user = $this->createUserIfNotExists(['email' => 'djeneba.coulibaly@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Coulibaly', 'prenoms' => 'Djénéba', 'date_naissance' => '2005-07-18', 'lieu_naissance' => 'M\'Bahiakro', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250704518729', 'email_eleve' => 'djeneba.coulibaly@example.com', 'region' => 'Iffou', 'commune' => 'M\'Bahiakro', 'matricule_bac' => '2024-MBA-004518', 'serie_bac' => 'D', 'note_bac' => 17.15, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de M\'Bahiakro', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur d\'anacarde', 'profession_mere' => 'Transformatrice', 'motivation' => "Je veux devenir ingénieure agroalimentaire pour valoriser l'anacarde et créer de la valeur ajoutée.", 'motivation_ai_score' => 8.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.79, 'rang_peub' => 73, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Djénéba Coulibaly créé avec succès!');
    }

    private function createBachelierAmadou()
    {
        $user = $this->createUserIfNotExists(['email' => 'amadou.berte@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Berté', 'prenoms' => 'Amadou', 'date_naissance' => '2004-12-03', 'lieu_naissance' => 'Toulepleu', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706839415', 'email_eleve' => 'amadou.berte@example.com', 'region' => 'Cavally', 'commune' => 'Toulepleu', 'matricule_bac' => '2024-TOU-006839', 'serie_bac' => 'A4', 'note_bac' => 15.75, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne de Toulepleu', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Garde forestier', 'profession_mere' => 'Cultivatrice', 'motivation' => "Je veux devenir journaliste pour sensibiliser sur les questions environnementales dans les forêts classées.", 'motivation_ai_score' => 8.3, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 85.94, 'rang_peub' => 125, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Amadou Berté créé avec succès!');
    }

    private function createBachelierNatacha()
    {
        $user = $this->createUserIfNotExists(['email' => 'natacha.die@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Dié', 'prenoms' => 'Natacha', 'date_naissance' => '2005-04-25', 'lieu_naissance' => 'Lakota', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250707152848', 'email_eleve' => 'natacha.die@example.com', 'region' => 'Lôh-Djiboua', 'commune' => 'Lakota', 'matricule_bac' => '2024-LAK-007152', 'serie_bac' => 'D', 'note_bac' => 18.70, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Lakota', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Médecin-chef', 'profession_mere' => 'Pharmacienne', 'motivation' => "Issue d'une famille médicale, je veux devenir neurochirurgienne pour traiter les pathologies du cerveau.", 'motivation_ai_score' => 9.5, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 93.18, 'rang_peub' => 10, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Natacha Dié créé avec succès!');
    }

    private function createBachelierAbou()
    {
        $user = $this->createUserIfNotExists(['email' => 'abou.konate@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Konaté', 'prenoms' => 'Abou', 'date_naissance' => '2004-09-17', 'lieu_naissance' => 'Sandégué', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250702973651', 'email_eleve' => 'abou.konate@example.com', 'region' => 'Gbêkê', 'commune' => 'Sandégué', 'matricule_bac' => '2024-SAN-002973', 'serie_bac' => 'C', 'note_bac' => 15.65, 'mention' => 'assez_bien', 'etablissement_nom' => 'Lycée Moderne de Sandégué', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Éleveur de moutons', 'profession_mere' => 'Vendeuse de légumes', 'motivation' => "Je veux devenir ingénieur en élevage pour moderniser la production ovine dans le centre du pays.", 'motivation_ai_score' => 8.2, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 84.83, 'rang_peub' => 144, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Abou Konaté créé avec succès!');
    }

    private function createBachelierRegina()
    {
        $user = $this->createUserIfNotExists(['email' => 'regina.kouame@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Kouamé', 'prenoms' => 'Régina', 'date_naissance' => '2005-11-29', 'lieu_naissance' => 'Tiébissou', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705186392', 'email_eleve' => 'regina.kouame@example.com', 'region' => 'Bélier', 'commune' => 'Tiébissou', 'matricule_bac' => '2024-TIE-005186', 'serie_bac' => 'A4', 'note_bac' => 17.55, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Tiébissou', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Directeur de banque', 'profession_mere' => 'Notaire', 'motivation' => "Je veux étudier le droit des affaires pour accompagner l'entrepreneuriat féminin en Côte d'Ivoire.", 'motivation_ai_score' => 8.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.39, 'rang_peub' => 97, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Régina Kouamé créé avec succès!');
    }

    private function createBachelierKarimu()
    {
        $user = $this->createUserIfNotExists(['email' => 'karimu.diomande@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Diomandé', 'prenoms' => 'Karimu', 'date_naissance' => '2004-06-12', 'lieu_naissance' => 'Dimbokro', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708374629', 'email_eleve' => 'karimu.diomande@example.com', 'region' => 'N\'zi', 'commune' => 'Dimbokro', 'matricule_bac' => '2024-DIM-008374', 'serie_bac' => 'C', 'note_bac' => 18.45, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Scientifique de Dimbokro', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Professeur de physique', 'profession_mere' => 'Laborantine', 'motivation' => "Je veux devenir physicien quantique pour contribuer à la recherche scientifique de pointe en Afrique.", 'motivation_ai_score' => 9.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 92.11, 'rang_peub' => 20, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Karimu Diomandé créé avec succès!');
    }

    private function createBachelierSylvie()
    {
        $user = $this->createUserIfNotExists(['email' => 'sylvie.mahi@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Mahi', 'prenoms' => 'Sylvie', 'date_naissance' => '2005-08-14', 'lieu_naissance' => 'Fresco', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706295174', 'email_eleve' => 'sylvie.mahi@example.com', 'region' => 'Gbôklé', 'commune' => 'Fresco', 'matricule_bac' => '2024-FRE-006295', 'serie_bac' => 'D', 'note_bac' => 16.80, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Fresco', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Pêcheur industriel', 'profession_mere' => 'Mareyeuse', 'motivation' => "Je veux étudier l'aquaculture pour développer l'élevage de poissons et diversifier la pêche.", 'motivation_ai_score' => 8.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.70, 'rang_peub' => 92, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Sylvie Mahi créé avec succès!');
    }

    private function createBachelierOmar()
    {
        $user = $this->createUserIfNotExists(['email' => 'omar.soro@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Soro', 'prenoms' => 'Omar', 'date_naissance' => '2004-03-28', 'lieu_naissance' => 'Dictionnaire', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250707418563', 'email_eleve' => 'omar.soro@example.com', 'region' => 'Tchologo', 'commune' => 'Dictionnaire', 'matricule_bac' => '2024-DIC-007418', 'serie_bac' => 'A4', 'note_bac' => 16.35, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Dictionnaire', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Instituteur', 'profession_mere' => 'Bibliothécaire', 'motivation' => "Je veux devenir linguiste pour préserver et enseigner les langues locales du nord de la Côte d'Ivoire.", 'motivation_ai_score' => 8.4, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 86.59, 'rang_peub' => 109, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Omar Soro créé avec succès!');
    }

    private function createBachelierKadiatou()
    {
        $user = $this->createUserIfNotExists(['email' => 'kadiatou.sylla@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Sylla', 'prenoms' => 'Kadiatou', 'date_naissance' => '2005-01-20', 'lieu_naissance' => 'Bloléquin', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250703629174', 'email_eleve' => 'kadiatou.sylla@example.com', 'region' => 'Cavally', 'commune' => 'Bloléquin', 'matricule_bac' => '2024-BLO-003629', 'serie_bac' => 'D', 'note_bac' => 17.90, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Bloléquin', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur de café', 'profession_mere' => 'Sage-femme', 'motivation' => "Je veux devenir gynécologue pour améliorer la santé reproductive des femmes dans l'ouest forestier.", 'motivation_ai_score' => 9.0, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 90.48, 'rang_peub' => 47, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Kadiatou Sylla créé avec succès!');
    }

    private function createBachelierWoury()
    {
        $user = $this->createUserIfNotExists(['email' => 'woury.kone@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Koné', 'prenoms' => 'Woury', 'date_naissance' => '2004-11-05', 'lieu_naissance' => 'Doropo', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705741836', 'email_eleve' => 'woury.kone@example.com', 'region' => 'Bounkani', 'commune' => 'Doropo', 'matricule_bac' => '2024-DOR-005741', 'serie_bac' => 'C', 'note_bac' => 16.45, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Doropo', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Commerçant de bétail', 'profession_mere' => 'Éleveuse', 'motivation' => "Je veux étudier la zootechnie pour améliorer les techniques d'élevage dans l'est ivoirien.", 'motivation_ai_score' => 8.3, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.11, 'rang_peub' => 100, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Woury Koné créé avec succès!');
    }

    private function createBachelierAristide()
    {
        $user = $this->createUserIfNotExists(['email' => 'aristide.nze@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Nzé', 'prenoms' => 'Aristide', 'date_naissance' => '2005-06-08', 'lieu_naissance' => 'Adiaké', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708529463', 'email_eleve' => 'aristide.nze@example.com', 'region' => 'Sud-Comoé', 'commune' => 'Adiaké', 'matricule_bac' => '2024-ADI-008529', 'serie_bac' => 'A4', 'note_bac' => 17.10, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne d\'Adiaké', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Douanier', 'profession_mere' => 'Secrétaire', 'motivation' => "Je veux étudier les relations internationales pour faciliter les échanges transfrontaliers.", 'motivation_ai_score' => 8.5, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 86.28, 'rang_peub' => 116, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Aristide Nzé créé avec succès!');
    }

    private function createBachelierCamille()
    {
        $user = $this->createUserIfNotExists(['email' => 'camille.dagrou@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Dagrou', 'prenoms' => 'Camille', 'date_naissance' => '2005-09-15', 'lieu_naissance' => 'Oumé', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250704851739', 'email_eleve' => 'camille.dagrou@example.com', 'region' => 'Fromager', 'commune' => 'Oumé', 'matricule_bac' => '2024-OUM-004851', 'serie_bac' => 'D', 'note_bac' => 18.25, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence d\'Oumé', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Vétérinaire', 'profession_mere' => 'Biologiste', 'motivation' => "Je veux devenir chercheuse en biotechnologie pour développer des vaccins adaptés aux maladies tropicales.", 'motivation_ai_score' => 9.4, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 92.56, 'rang_peub' => 17, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Camille Dagrou créé avec succès!');
    }

    private function createBachelierGodwin()
    {
        $user = $this->createUserIfNotExists(['email' => 'godwin.amani@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Amani', 'prenoms' => 'Godwin', 'date_naissance' => '2004-08-22', 'lieu_naissance' => 'Agnibilékrou', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250706174829', 'email_eleve' => 'godwin.amani@example.com', 'region' => 'Indénié-Djuablin', 'commune' => 'Agnibilékrou', 'matricule_bac' => '2024-AGN-006174', 'serie_bac' => 'C', 'note_bac' => 17.70, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne d\'Agnibilékrou', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Planteur d\'hévéa', 'profession_mere' => 'Transformatrice', 'motivation' => "Je veux devenir chimiste industriel pour valoriser les produits dérivés de l'hévéa.", 'motivation_ai_score' => 8.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.85, 'rang_peub' => 84, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Godwin Amani créé avec succès!');
    }

    private function createBachelierFanta()
    {
        $user = $this->createUserIfNotExists(['email' => 'fanta.djire@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Djiré', 'prenoms' => 'Fanta', 'date_naissance' => '2005-12-11', 'lieu_naissance' => 'Tanda', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250707395184', 'email_eleve' => 'fanta.djire@example.com', 'region' => 'Gontougo', 'commune' => 'Tanda', 'matricule_bac' => '2024-TAN-007395', 'serie_bac' => 'A4', 'note_bac' => 16.95, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Tanda', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Transporteur', 'profession_mere' => 'Gérante de pharmacie', 'motivation' => "Je veux devenir pharmacienne pour améliorer l'accès aux médicaments dans l'est de la Côte d'Ivoire.", 'motivation_ai_score' => 8.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 87.24, 'rang_peub' => 102, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Fanta Djiré créé avec succès!');
    }

    private function createBachelierDenis()
    {
        $user = $this->createUserIfNotExists(['email' => 'denis.gohi@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Gohi', 'prenoms' => 'Denis', 'date_naissance' => '2004-05-17', 'lieu_naissance' => 'Hiré', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708617439', 'email_eleve' => 'denis.gohi@example.com', 'region' => 'Lacs', 'commune' => 'Hiré', 'matricule_bac' => '2024-HIR-008617', 'serie_bac' => 'C', 'note_bac' => 19.05, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Hiré', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Ingénieur agronome', 'profession_mere' => 'Biologiste', 'motivation' => "Je veux devenir ingénieur en intelligence artificielle pour révolutionner l'agriculture africaine.", 'motivation_ai_score' => 9.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 95.26, 'rang_peub' => 4, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Denis Gohi créé avec succès!');
    }

    private function createBachelierJosette()
    {
        $user = $this->createUserIfNotExists(['email' => 'josette.bleu@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Bleu', 'prenoms' => 'Josette', 'date_naissance' => '2005-03-06', 'lieu_naissance' => 'Méagui', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705296174', 'email_eleve' => 'josette.bleu@example.com', 'region' => 'San-Pédro', 'commune' => 'Méagui', 'matricule_bac' => '2024-MEA-005296', 'serie_bac' => 'D', 'note_bac' => 16.60, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Méagui', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Bucheron', 'profession_mere' => 'Infirmière', 'motivation' => "Je veux devenir médecin généraliste pour servir les populations forestières isolées.", 'motivation_ai_score' => 8.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 88.15, 'rang_peub' => 83, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Josette Bleu créé avec succès!');
    }

    // === BATCH FINAL : 5 DERNIERS PROFILS (96-100) ===
    
    private function createBachelierAlbert()
    {
        $user = $this->createUserIfNotExists(['email' => 'albert.ahoua@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Ahoua', 'prenoms' => 'Albert', 'date_naissance' => '2004-01-30', 'lieu_naissance' => 'Yamoussoukro', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250709518374', 'email_eleve' => 'albert.ahoua@example.com', 'region' => 'Yamoussoukro', 'commune' => 'Yamoussoukro', 'matricule_bac' => '2024-YAM-009518', 'serie_bac' => 'C', 'note_bac' => 19.50, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Scientifique de Yamoussoukro', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Recteur d\'université', 'profession_mere' => 'Magistrat', 'motivation' => "Je veux devenir chercheur en nanotechnologie pour positionner l'Afrique à la pointe de l'innovation scientifique mondiale.", 'motivation_ai_score' => 9.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 97.75, 'rang_peub' => 1, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Albert Ahoua créé avec succès!');
    }

    private function createBachelierLinda()
    {
        $user = $this->createUserIfNotExists(['email' => 'linda.bila@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Bila', 'prenoms' => 'Linda', 'date_naissance' => '2005-07-07', 'lieu_naissance' => 'Bouaké', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250706382715', 'email_eleve' => 'linda.bila@example.com', 'region' => 'Gbêkê', 'commune' => 'Bouaké', 'matricule_bac' => '2024-BOU-006382', 'serie_bac' => 'D', 'note_bac' => 18.90, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Sainte Marie de Bouaké', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Cardiologue', 'profession_mere' => 'Anesthésiste', 'motivation' => "Issue d'une famille médicale, je veux devenir chirurgienne pédiatrique pour sauver la vie des enfants.", 'motivation_ai_score' => 9.6, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 94.65, 'rang_peub' => 5, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Linda Bila créé avec succès!');
    }

    private function createBachelierGeoffroy()
    {
        $user = $this->createUserIfNotExists(['email' => 'geoffroy.tano@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Tano', 'prenoms' => 'Geoffroy', 'date_naissance' => '2004-04-23', 'lieu_naissance' => 'San-Pedro', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250707951638', 'email_eleve' => 'geoffroy.tano@example.com', 'region' => 'San-Pédro', 'commune' => 'San-Pedro', 'matricule_bac' => '2024-SAP-007951', 'serie_bac' => 'C', 'note_bac' => 18.15, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée Technique de San-Pedro', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Capitaine de navire', 'profession_mere' => 'Ingénieure navale', 'motivation' => "Je veux devenir ingénieur maritime pour développer l'industrie navale et portuaire en Afrique de l'Ouest.", 'motivation_ai_score' => 9.1, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 91.04, 'rang_peub' => 33, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Geoffroy Tano créé avec succès!');
    }

    private function createBachelierYamyness()
    {
        $user = $this->createUserIfNotExists(['email' => 'yamyness.gbane@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Gbané', 'prenoms' => 'Yamyness', 'date_naissance' => '2005-10-16', 'lieu_naissance' => 'Abidjan', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250708394652', 'email_eleve' => 'yamyness.gbane@example.com', 'region' => 'Autonome d\'Abidjan', 'commune' => 'Plateau', 'matricule_bac' => '2024-PLA-008394', 'serie_bac' => 'A4', 'note_bac' => 19.25, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence Sainte Marie', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Ambassadeur', 'profession_mere' => 'Professeure d\'université', 'motivation' => "Je veux étudier les sciences politiques et devenir Présidente de la République pour transformer l'Afrique.", 'motivation_ai_score' => 9.8, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 91.94, 'rang_peub' => 26, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Yamyness Gbané créé avec succès!');
    }

    private function createBachelierTraore()
    {
        $user = $this->createUserIfNotExists(['email' => 'traore.sekou@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Traoré', 'prenoms' => 'Sékou', 'date_naissance' => '2004-07-28', 'lieu_naissance' => 'Korhogo', 'sexe' => 'M', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250705729418', 'email_eleve' => 'traore.sekou@example.com', 'region' => 'Poro', 'commune' => 'Korhogo', 'matricule_bac' => '2024-KOR-005729', 'serie_bac' => 'C', 'note_bac' => 18.60, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée d\'Excellence de Korhogo', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'Chirurgien', 'profession_mere' => 'Professeure de médecine', 'motivation' => "Je veux devenir médecin-chercheur spécialisé dans les maladies tropicales pour développer de nouveaux traitements.", 'motivation_ai_score' => 9.7, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 93.65, 'rang_peub' => 9, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Traoré Sékou créé avec succès!');
    }

    private function createBachelierCharlotte()
    {
        $user = $this->createUserIfNotExists(['email' => 'charlotte.doh@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Doh', 'prenoms' => 'Charlotte', 'date_naissance' => '2005-05-12', 'lieu_naissance' => 'Abidjan', 'sexe' => 'F', 'piece_identite_type' => 'cni', 'telephone_eleve' => '+2250709674185', 'email_eleve' => 'charlotte.doh@example.com', 'region' => 'Autonome d\'Abidjan', 'commune' => 'Cocody', 'matricule_bac' => '2024-COC-009674', 'serie_bac' => 'D', 'note_bac' => 19.15, 'mention' => 'tres_bien', 'etablissement_nom' => 'Lycée International Jean Mermoz', 'etablissement_type' => 'prive_homologue', 'annee_bac' => 2024, 'profession_pere' => 'PDG multinationale', 'profession_mere' => 'Ministre', 'motivation' => "Je veux étudier la médecine spatiale pour préparer les futures missions spatiales africaines.", 'motivation_ai_score' => 9.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 92.29, 'rang_peub' => 19, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Charlotte Doh créé avec succès!');
    }

    private function createBachelierFodé()
    {
        $user = $this->createUserIfNotExists(['email' => 'fode.camara@example.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, ['user_id' => $user->id, 'nom' => 'Camara', 'prenoms' => 'Fodé', 'date_naissance' => '2004-09-03', 'lieu_naissance' => 'Beyla', 'sexe' => 'M', 'piece_identite_type' => 'carte_scolaire', 'telephone_eleve' => '+2250706285174', 'email_eleve' => 'fode.camara@example.com', 'region' => 'Tonkpi', 'commune' => 'Beyla', 'matricule_bac' => '2024-BEY-006285', 'serie_bac' => 'A4', 'note_bac' => 17.40, 'mention' => 'bien', 'etablissement_nom' => 'Lycée Moderne de Beyla', 'etablissement_type' => 'public', 'annee_bac' => 2024, 'profession_pere' => 'Conteur traditionnel', 'profession_mere' => 'Artisane', 'motivation' => "Je veux devenir écrivain et cinéaste pour raconter les histoires authentiques de l'Afrique de l'Ouest.", 'motivation_ai_score' => 8.9, 'boursier_peub' => true, 'status_candidature' => 'accepte', 'score_final_peub' => 89.85, 'rang_peub' => 56, 'ai_extraction_completed_at' => now(), 'ai_model_used' => 'gpt-4-vision', 'date_calcul_scoring' => now()]);
        $this->command->info('✅ Profil bachelier Fodé Camara créé avec succès!');
    }

    private function createBachelierThierry()
    {
        $user = $this->createUserIfNotExists(['email' => 'thierry.beugre@ansut.ci', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Beugré',
            'prenoms' => 'Thierry',
            'date_naissance' => '2005-03-15',
            'lieu_naissance' => 'Abidjan',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'telephone_eleve' => '+2250701234567',
            'telephone_parent' => '+2250709876543',
            'email_eleve' => 'thierry.beugre@ansut.ci',
            'email_parent' => 'parent.thierry@ansut.ci',
            'region' => 'Abidjan',
            'commune' => 'Cocody',
            'matricule_bac' => '2024-COC-001234',
            'serie_bac' => 'C',
            'note_bac' => 18.75,
            'mention' => 'tres_bien',
            'etablissement_nom' => 'Lycée Scientifique de Yamoussoukro',
            'etablissement_type' => 'public',
            'annee_bac' => 2024,
            'profession_pere' => 'Ingénieur',
            'profession_mere' => 'Médecin',
            'motivation' => "Je souhaite devenir ingénieur en informatique pour développer des solutions technologiques innovantes pour l'Afrique.",
            'motivation_ai_score' => 9.2,
            'boursier_peub' => true,
            'status_candidature' => 'accepte',
            'score_final_peub' => 95.50,
            'rang_peub' => 8,
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'date_calcul_scoring' => now()
        ]);
        $this->command->info('✅ Profil bachelier Thierry Beugré créé avec succès!');
    }

    private function createBachelierBensouma()
    {
        $user = $this->createUserIfNotExists(['email' => 'bensoumahoro27@gmail.com', 'role' => 'bachelier', 'status' => 'active', 'email_verified_at' => now()]);
        $this->createBachelierIfNotExists($user, [
            'user_id' => $user->id,
            'nom' => 'Horo',
            'prenoms' => 'Bensouma',
            'date_naissance' => '2005-07-27',
            'lieu_naissance' => 'Bouaké',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'telephone_eleve' => '+2250707654321',
            'telephone_parent' => '+2250708765432',
            'email_eleve' => 'bensoumahoro27@gmail.com',
            'email_parent' => 'parent.bensouma@ansut.ci',
            'region' => 'Gbêkê',
            'commune' => 'Bouaké',
            'matricule_bac' => '2024-BOU-007654',
            'serie_bac' => 'D',
            'note_bac' => 17.85,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Bouaké',
            'etablissement_type' => 'public',
            'annee_bac' => 2024,
            'profession_pere' => 'Agriculteur',
            'profession_mere' => 'Commerçante',
            'motivation' => "Je veux étudier la médecine vétérinaire pour améliorer l'élevage et l'agriculture en Côte d'Ivoire.",
            'motivation_ai_score' => 8.7,
            'boursier_peub' => true,
            'status_candidature' => 'accepte',
            'score_final_peub' => 88.25,
            'rang_peub' => 75,
            'ai_extraction_completed_at' => now(),
            'ai_model_used' => 'gpt-4-vision',
            'date_calcul_scoring' => now()
        ]);
        $this->command->info('✅ Profil bachelier Bensouma Horo créé avec succès!');
    }
} 
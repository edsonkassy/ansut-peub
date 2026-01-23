<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bachelier;
use App\Models\Partenaire;
use App\Models\Opportunite;
use App\Models\Dotation;
use App\Models\Candidature;
use App\Models\Favori;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Alerte;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appeler les seeders spécifiques
        $this->call([
            BachelierSeeder::class,
            ArticleSeeder::class,
            AdminPermissionsSeeder::class,
            LibrarySeeder::class,
            ForumCategorySeeder::class,
            ForumContentSeeder::class,
        ]);

        // Admin User principal
        $adminUser = User::create([
            'email' => 'admin@ansut.ci',
            'email_verified_at' => now(),
            'role' => 'admin',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        // Admins supplémentaires
        $additionalAdmins = [
            'lamine.barro@etudesk.org',
            'peub@ansut.ci',
            'marius.kanga@ansut.ci',
            'mamadou.soumahoro@ansut.ci',
            'serge.yao@ansut.ci',
            'anicet.korandji@ansut.ci',
            'ghislain.memel@ansut.ci',
            'mohamad.kone@ansut.ci',
            'sarrah.coulibaly@ansut.ci',
            'bagnogona.traore@ansut.ci',
            'salime.toure@ansut.ci'
        ];

        foreach ($additionalAdmins as $email) {
            User::create([
                'email' => $email,
                'email_verified_at' => now(),
                'role' => 'admin',
                'status' => 'active',
                'last_login_at' => now(),
            ]);
        }

        // Assigner le rôle super_admin à tous les admins
        $superAdminRole = \App\Models\AdminRole::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $adminUsers = \App\Models\User::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                $admin->assignAdminRole($superAdminRole);
            }
        }

        // Bachelier User et profil
        $bachelierUser = User::create([
            'email' => 'kouame.jean@gmail.com',
            'email_verified_at' => now(),
            'role' => 'bachelier',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        Bachelier::create([
            'user_id' => $bachelierUser->id,
            'nom' => 'KOUAME',
            'prenoms' => 'Jean Baptiste',
            'date_naissance' => Carbon::parse('2006-03-15'),
            'lieu_naissance' => 'Abidjan',
            'sexe' => 'M',
            'piece_identite_type' => 'cni',
            'piece_identite_file' => 'documents/cni/kouame_jean_cni.jpg',
            'telephone_eleve' => '+225 07 12 34 56 78',
            'telephone_parent' => '+225 05 98 76 54 32',
            'email_eleve' => 'kouame.jean@gmail.com',
            'email_parent' => 'kouame.papa@yahoo.fr',
            'region' => 'Abidjan',
            'commune' => 'Cocody',
            'matricule_bac' => 'CI2024001234',
            'serie_bac' => 'C',
            'note_bac' => 16.85,
            'mention' => 'bien',
            'etablissement_nom' => 'Lycée Moderne de Cocody',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'documents/bac/kouame_jean_collante.jpg',
            'annee_bac' => 2024,
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'Ingénieur informatique',
            'profession_mere' => 'Enseignante',
            'situations_particulieres' => ['excellent_eleve'],
            'possede_ordinateur' => true,
            'connexion_internet' => 'fibre',
            'acces_smartphone' => true,
            'acces_ia' => true,
            'motivation' => 'Passionné par les technologies et l\'innovation, je souhaite devenir ingénieur en intelligence artificielle pour contribuer au développement technologique de la Côte d\'Ivoire. Mon objectif est d\'intégrer une grande école d\'ingénieurs pour acquérir les compétences nécessaires.',
            'boursier_peub' => true,
            'date_integration_peub' => Carbon::parse('2024-09-01'),
            'status_candidature' => 'accepte',
            'status_profil' => 'verifie',
            'date_verification' => Carbon::parse('2024-08-15'),
            'bio' => 'Bachelier série C passionné par l\'informatique et les mathématiques. Président du club de programmation de mon lycée.',
            'competences' => ['Programmation Python', 'Mathématiques avancées', 'Leadership', 'Anglais courant'],
            'langues' => ['Français (natif)', 'Anglais (courant)', 'Espagnol (intermédiaire)'],
            'photo' => 'photos/kouame_jean.jpg',
            'cv_path' => 'cv/kouame_jean_cv.pdf',
        ]);

        // Partenaire User et organisation
        $partenaireUser = User::create([
            'email' => 'contact@orange.ci',
            'email_verified_at' => now(),
            'role' => 'partenaire',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        $partenaire = Partenaire::create([
            'user_id' => $partenaireUser->id,
            'nom_organisation' => 'Orange Côte d\'Ivoire',
            'type_organisation' => 'entreprise',
            'secteur_activite' => 'telecoms_services_numeriques',
            'region' => 'Abidjan',
            'commune' => 'Plateau',
            'adresse' => '8ème étage Immeuble CCIA, Avenue Franchet d\'Esperey, Plateau',
            'telephone' => '+225 20 30 95 00',
            'site_web' => 'https://www.orange.ci',
            'description' => 'Orange Côte d\'Ivoire est l\'opérateur leader des télécommunications en Côte d\'Ivoire. Nous proposons des services de téléphonie mobile, internet, et solutions digitales pour particuliers et entreprises. Notre engagement pour l\'éducation et la formation des jeunes nous amène à offrir des bourses d\'études et des stages professionnels.',
            'personne_contact_nom' => 'DIABATE Mariama',
            'personne_contact_fonction' => 'Directrice des Ressources Humaines',
            'personne_contact_telephone' => '+225 07 45 67 89 12',
            'personne_contact_email' => 'contact@orange.ci',
            'status_verification' => 'verified',
            'date_verification' => Carbon::parse('2024-01-15'),
        ]);

        // Deuxième partenaire
        $partenaireUser2 = User::create([
            'email' => 'bourses@nestleci.com',
            'email_verified_at' => now(),
            'role' => 'partenaire',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        $partenaire2 = Partenaire::create([
            'user_id' => $partenaireUser2->id,
            'nom_organisation' => 'Nestlé Côte d\'Ivoire',
            'type_organisation' => 'entreprise',
            'secteur_activite' => 'agro_agroalimentaire',
            'region' => 'Abidjan',
            'commune' => 'Yopougon',
            'adresse' => 'Zone Industrielle de Yopougon, Boulevard du Cameroun',
            'telephone' => '+225 23 46 81 00',
            'site_web' => 'https://www.nestle.ci',
            'description' => 'Nestlé Côte d\'Ivoire, filiale du groupe Nestlé, est un leader de l\'industrie agroalimentaire en Afrique de l\'Ouest. Nous nous engageons pour le développement durable et la formation des jeunes talents ivoiriens à travers notre programme de bourses d\'études internationales.',
            'personne_contact_nom' => 'KONAN Yves',
            'personne_contact_fonction' => 'Responsable Formation et Développement',
            'personne_contact_telephone' => '+225 05 67 89 12 34',
            'personne_contact_email' => 'bourses@nestleci.com',
            'status_verification' => 'verified',
            'date_verification' => Carbon::parse('2024-02-10'),
        ]);

        // Deuxième bachelier
        $bachelierUser2 = User::create([
            'email' => 'traore.fatou@yahoo.fr',
            'email_verified_at' => now(),
            'role' => 'bachelier',
            'status' => 'active',
            'last_login_at' => Carbon::parse('2024-12-01'),
        ]);

        Bachelier::create([
            'user_id' => $bachelierUser2->id,
            'nom' => 'TRAORE',
            'prenoms' => 'Fatou Bintou',
            'date_naissance' => Carbon::parse('2005-07-22'),
            'lieu_naissance' => 'Bouaké',
            'sexe' => 'F',
            'piece_identite_type' => 'carte_scolaire',
            'piece_identite_file' => 'documents/carte_scolaire/traore_fatou_carte.jpg',
            'telephone_eleve' => '+225 01 23 45 67 89',
            'telephone_parent' => '+225 07 65 43 21 09',
            'email_eleve' => 'traore.fatou@yahoo.fr',
            'email_parent' => 'traore.maman@gmail.com',
            'region' => 'Gbêkê',
            'commune' => 'Bouaké',
            'matricule_bac' => 'CI2024005678',
            'serie_bac' => 'D',
            'note_bac' => 18.25,
            'mention' => 'tres_bien',
            'etablissement_nom' => 'Lycée Moderne de Bouaké',
            'etablissement_type' => 'public',
            'collante_bac_file' => 'documents/bac/traore_fatou_collante.jpg',
            'annee_bac' => 2024,
            'pensionnaire_internat' => true,
            'bourse_scolaire_lycee' => true,
            'profession_pere' => 'Agriculteur',
            'profession_mere' => 'Commerçante',
            'situations_particulieres' => ['situation_financiere_difficile', 'excellent_eleve'],
            'possede_ordinateur' => true,
            'connexion_internet' => 'aucune',
            'acces_smartphone' => true,
            'acces_ia' => true,
            'motivation' => 'Issue d\'une famille modeste, j\'aspire à devenir médecin pour servir ma communauté. Excellence en sciences depuis le collège, je rêve d\'intégrer une faculté de médecine prestigieuse pour acquérir les meilleures compétences et revenir soigner les populations rurales de ma région.',
            'boursier_peub' => false,
            'status_candidature' => 'en_attente',
            'status_profil' => 'complet',
            'bio' => 'Bachelière série D avec mention Très Bien, passionnée par les sciences biologiques et la médecine.',
            'competences' => ['Biologie', 'Chimie', 'Mathématiques', 'Recherche scientifique'],
            'langues' => ['Français (natif)', 'Anglais (intermédiaire)', 'Dioula (natif)'],
            'photo' => 'photos/traore_fatou.jpg',
        ]);

        // Créer quelques opportunités
        Opportunite::create([
            'partenaire_id' => $partenaire->id,
            'titre' => 'Bourse d\'Excellence Orange Digital Academy',
            'type' => 'bourse',
            'description' => 'Bourse complète pour formation en développement mobile et intelligence artificielle dans les meilleures universités européennes. Cette bourse couvre tous les frais de scolarité, d\'hébergement et de subsistance.',
            'competences_requises' => ['Programmation', 'Mathématiques', 'Anglais', 'Innovation'],
            'criteres_eligibilite' => ['Bac série C ou D avec mention Bien minimum', 'Excellent niveau en mathématiques et informatique', 'Maîtrise de l\'anglais', 'Projet professionnel cohérent'],
            'pays' => 'France',
            'ville' => 'Paris, Lyon, Marseille',
            'duree' => '3 ans',
            'remuneration' => '45 000 EUR/an',
            'date_debut' => Carbon::parse('2025-09-01'),
            'date_fin' => Carbon::parse('2028-06-30'),
            'date_limite_candidature' => Carbon::parse('2026-03-31'),
            'nombre_places' => 10,
            'niveau_etude_requis' => 'Baccalauréat',
            'series_acceptees' => ['C', 'D'],
            'moyenne_minimum' => 14.00,
            'regions_ciblees' => ['Abidjan', 'Bouaké', 'Yamoussoukro'],
            'documents_requis' => ['Relevé de notes du Bac', 'CV détaillé', 'Lettre de motivation', 'Certificat d\'anglais', 'Projet professionnel'],
            'contact_email' => 'bourses@orange.ci',
            'contact_telephone' => '+225 07 45 67 89 12',
            'lien_externe' => 'https://www.orange.ci/bourses',
            'status' => 'published',
            'vues' => 156,
            'candidatures_count' => 23,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire2->id,
            'titre' => 'Programme de Bourses Nestlé Agro-Business',
            'type' => 'bourse',
            'description' => 'Formation complète en agronomie moderne et gestion agroalimentaire dans les universités partenaires en Suisse et au Canada. Programme incluant stages pratiques et mentorat professionnel.',
            'competences_requises' => ['Sciences biologiques', 'Chimie', 'Gestion', 'Innovation agricole'],
            'criteres_eligibilite' => ['Bac série C ou D', 'Passion pour l\'agriculture et l\'innovation', 'Projet de retour en Côte d\'Ivoire', 'Leadership démontré'],
            'pays' => 'Suisse',
            'ville' => 'Genève, Zurich',
            'duree' => '4 ans',
            'remuneration' => '50 000 CHF/an',
            'date_debut' => Carbon::parse('2025-09-15'),
            'date_fin' => Carbon::parse('2029-07-15'),
            'date_limite_candidature' => Carbon::parse('2026-04-15'),
            'nombre_places' => 5,
            'niveau_etude_requis' => 'Baccalauréat',
            'series_acceptees' => ['C', 'D'],
            'moyenne_minimum' => 15.00,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['Dossier scolaire complet', 'Projet professionnel détaillé', 'Lettres de recommandation', 'Essai sur l\'agriculture'],
            'contact_email' => 'bourses@nestleci.com',
            'contact_telephone' => '+225 05 67 89 12 34',
            'lien_externe' => 'https://www.nestle.ci/carrieres/bourses',
            'status' => 'published',
            'vues' => 89,
            'candidatures_count' => 12,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire->id,
            'titre' => 'Stage de Développement Orange Summer Tech',
            'type' => 'stage',
            'description' => 'Stage de 6 mois dans nos équipes de développement avec possibilité d\'embauche. Accompagnement par des seniors et projets concrets sur nos applications.',
            'competences_requises' => ['Programmation mobile', 'APIs REST', 'Base de données', 'Travail en équipe'],
            'criteres_eligibilite' => ['Étudiant en informatique niveau L2/L3', 'Connaissance en programmation', 'Motivation forte', 'Disponibilité 6 mois'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '6 mois',
            'remuneration' => '200 000 XOF/mois',
            'date_debut' => Carbon::parse('2025-10-01'),
            'date_fin' => Carbon::parse('2025-11-30'),
            'date_limite_candidature' => Carbon::parse('2026-02-28'),
            'nombre_places' => 8,
            'niveau_etude_requis' => 'Licence en cours',
            'series_acceptees' => ['C', 'D', 'Toutes'],
            'moyenne_minimum' => 12.00,
            'regions_ciblees' => ['Abidjan', 'Bouaké', 'San-Pédro'],
            'documents_requis' => ['CV détaillé', 'Portfolio de projets', 'Lettre de motivation', 'Attestation d\'inscription'],
            'contact_email' => 'stages@orange.ci',
            'contact_telephone' => '+225 07 45 67 89 12',
            'lien_externe' => 'https://www.orange.ci/carrieres/stages',
            'status' => 'published',
            'vues' => 234,
            'candidatures_count' => 45,
        ]);

        // Créer des partenaires supplémentaires pour diversifier les opportunités
        $partenaireUser3 = User::create([
            'email' => 'rh@mtn.ci',
            'email_verified_at' => now(),
            'role' => 'partenaire',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        $partenaire3 = Partenaire::create([
            'user_id' => $partenaireUser3->id,
            'nom_organisation' => 'MTN Côte d\'Ivoire',
            'type_organisation' => 'entreprise',
            'secteur_activite' => 'telecoms_services_numeriques',
            'region' => 'Abidjan',
            'commune' => 'Plateau',
            'adresse' => 'Immeuble MTN, Rue Jesse Owens, Plateau',
            'telephone' => '+225 05 05 05 05',
            'site_web' => 'https://www.mtn.ci',
            'description' => 'MTN Côte d\'Ivoire est un acteur majeur des télécommunications mobiles en Côte d\'Ivoire. Nous investissons massivement dans la formation des jeunes talents en TIC et offrons des opportunités d\'emploi et de stage dans le domaine du digital.',
            'personne_contact_nom' => 'KONE Aminata',
            'personne_contact_fonction' => 'Directrice Ressources Humaines',
            'personne_contact_telephone' => '+225 07 89 12 34 56',
            'personne_contact_email' => 'rh@mtn.ci',
            'status_verification' => 'verified',
            'date_verification' => Carbon::parse('2024-03-01'),
        ]);

        $partenaireUser4 = User::create([
            'email' => 'recrutement@abidjannet.ci',
            'email_verified_at' => now(),
            'role' => 'partenaire',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        $partenaire4 = Partenaire::create([
            'user_id' => $partenaireUser4->id,
            'nom_organisation' => 'AbidjanNet - Orange Business',
            'type_organisation' => 'entreprise',
            'secteur_activite' => 'telecoms_services_numeriques',
            'region' => 'Abidjan',
            'commune' => 'Cocody',
            'adresse' => 'Zone 4C, Cocody, Abidjan',
            'telephone' => '+225 27 22 55 44 33',
            'site_web' => 'https://www.abidjannet.ci',
            'description' => 'AbidjanNet est une filiale d\'Orange spécialisée dans les solutions internet et télécom pour entreprises. Nous proposons des formations professionnelles en réseau et cybersécurité.',
            'personne_contact_nom' => 'OUATTARA Ibrahim',
            'personne_contact_fonction' => 'Responsable Formation',
            'personne_contact_telephone' => '+225 01 02 03 04 05',
            'personne_contact_email' => 'recrutement@abidjannet.ci',
            'status_verification' => 'verified',
            'date_verification' => Carbon::parse('2024-03-15'),
        ]);

        $partenaireUser5 = User::create([
            'email' => 'contact@sodeci.ci',
            'email_verified_at' => now(),
            'role' => 'partenaire',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        $partenaire5 = Partenaire::create([
            'user_id' => $partenaireUser5->id,
            'nom_organisation' => 'SODECI - Société de Distribution d\'Eau',
            'type_organisation' => 'entreprise',
            'secteur_activite' => 'energie_environnement',
            'region' => 'Abidjan',
            'commune' => 'Treichville',
            'adresse' => 'Boulevard de la République, Treichville',
            'telephone' => '+225 21 24 17 00',
            'site_web' => 'https://www.sodeci.ci',
            'description' => 'SODECI est la société de distribution d\'eau en Côte d\'Ivoire. Nous offrons des opportunités de formation et d\'emploi dans les métiers de l\'eau, de l\'environnement et du génie civil.',
            'personne_contact_nom' => 'BAMBA Salimata',
            'personne_contact_fonction' => 'Chef du Personnel',
            'personne_contact_telephone' => '+225 05 44 55 66 77',
            'personne_contact_email' => 'contact@sodeci.ci',
            'status_verification' => 'verified',
            'date_verification' => Carbon::parse('2024-02-20'),
        ]);

        $partenaireUser6 = User::create([
            'email' => 'emploi@bancatlantique.ci',
            'email_verified_at' => now(),
            'role' => 'partenaire',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        $partenaire6 = Partenaire::create([
            'user_id' => $partenaireUser6->id,
            'nom_organisation' => 'Banque Atlantique Côte d\'Ivoire',
            'type_organisation' => 'entreprise',
            'secteur_activite' => 'banque_finance',
            'region' => 'Abidjan',
            'commune' => 'Plateau',
            'adresse' => 'Avenue Chardy, Plateau',
            'telephone' => '+225 20 20 00 20',
            'site_web' => 'https://www.bancatlantique.ci',
            'description' => 'Banque Atlantique CI fait partie du Groupe Banque Atlantique présent dans 13 pays africains. Nous recrutons des jeunes talents en finance, comptabilité et informatique bancaire.',
            'personne_contact_nom' => 'DIABATE Moussa',
            'personne_contact_fonction' => 'DRH Adjoint',
            'personne_contact_telephone' => '+225 07 11 22 33 44',
            'personne_contact_email' => 'emploi@bancatlantique.ci',
            'status_verification' => 'verified',
            'date_verification' => Carbon::parse('2024-01-30'),
        ]);

        // OPPORTUNITÉS COMPLÈTES PAR CATÉGORIE

        // === BOURSES (2 nouveaux) ===
        Opportunite::create([
            'partenaire_id' => $partenaire3->id,
            'titre' => 'Bourse MTN Excellence Télécoms 2025',
            'type' => 'bourse',
            'description' => 'Programme de bourse complète pour études supérieures en télécommunications et réseaux dans les meilleures universités africaines et européennes. Formation en 5G, fibre optique, et technologies mobiles avancées avec stage garanti chez MTN.',
            'competences_requises' => ['Mathématiques', 'Physique', 'Informatique', 'Anglais technique'],
            'criteres_eligibilite' => ['Bac série C, D ou F3 avec mention Bien minimum', 'Passion pour les télécoms', 'Projet professionnel dans les TIC', 'Engagement de retour en CI après formation'],
            'pays' => 'Maroc',
            'ville' => 'Casablanca, Rabat',
            'duree' => '5 ans',
            'remuneration' => '8 000 000 XOF/an',
            'date_debut' => Carbon::parse('2025-10-01'),
            'date_fin' => Carbon::parse('2030-07-30'),
            'date_limite_candidature' => Carbon::parse('2025-09-15'),
            'nombre_places' => 3,
            'niveau_etude_requis' => 'Baccalauréat',
            'series_acceptees' => ['C', 'D', 'F3'],
            'moyenne_minimum' => 14.50,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['Relevé de notes BAC', 'CV', 'Lettre de motivation', 'Projet professionnel détaillé', 'Certificat médical'],
            'contact_email' => 'bourses@mtn.ci',
            'contact_telephone' => '+225 05 05 05 05',
            'lien_externe' => 'https://www.mtn.ci/carrieres/bourses',
            'status' => 'published',
            'vues' => 67,
            'candidatures_count' => 8,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire6->id,
            'titre' => 'Programme de Bourses Banque Atlantique Finance',
            'type' => 'bourse',
            'description' => 'Formation complète en finance, banque et économie dans les universités partenaires en France et au Sénégal. Spécialisation en banque digitale, fintech et inclusion financière avec garantie d\'emploi au retour.',
            'competences_requises' => ['Mathématiques', 'Économie', 'Informatique', 'Communication'],
            'criteres_eligibilite' => ['Bac série C, D ou G2 avec mention Bien', 'Intérêt pour la finance', 'Maîtrise du français et bases en anglais', 'Leadership et esprit d\'équipe'],
            'pays' => 'Sénégal',
            'ville' => 'Dakar',
            'duree' => '3 ans',
            'remuneration' => '6 000 000 XOF/an',
            'date_debut' => Carbon::parse('2025-11-01'),
            'date_fin' => Carbon::parse('2028-08-31'),
            'date_limite_candidature' => Carbon::parse('2025-10-30'),
            'nombre_places' => 4,
            'niveau_etude_requis' => 'Baccalauréat',
            'series_acceptees' => ['C', 'D', 'G2'],
            'moyenne_minimum' => 14.00,
            'regions_ciblees' => ['Abidjan', 'Bouaké', 'Daloa', 'Korhogo'],
            'documents_requis' => ['Dossier scolaire complet', 'Lettre de motivation', 'Projet de carrière en banque', 'Recommandations', 'Test psychotechnique'],
            'contact_email' => 'bourses@bancatlantique.ci',
            'contact_telephone' => '+225 20 20 00 20',
            'lien_externe' => 'https://www.bancatlantique.ci/carrieres',
            'status' => 'published',
            'vues' => 112,
            'candidatures_count' => 15,
        ]);

        // === STAGES (2 nouveaux) ===
        Opportunite::create([
            'partenaire_id' => $partenaire4->id,
            'titre' => 'Stage Professionnel Cybersécurité AbidjanNet',
            'type' => 'stage',
            'description' => 'Stage de 6 mois en cybersécurité et administration réseau dans notre centre technique. Formation sur les dernières technologies de sécurité, firewall, monitoring réseau et réponse aux incidents.',
            'competences_requises' => ['Réseau informatique', 'Sécurité IT', 'Linux/Windows', 'Analyse de logs'],
            'criteres_eligibilite' => ['Étudiant en informatique/télécoms niveau L3 ou Master', 'Connaissances réseau de base', 'Passion pour la cybersécurité', 'Disponibilité 6 mois minimum'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '6 mois',
            'remuneration' => '150 000 XOF/mois',
            'date_debut' => Carbon::parse('2025-11-01'),
            'date_fin' => Carbon::parse('2025-12-31'),
            'date_limite_candidature' => Carbon::parse('2026-04-30'),
            'nombre_places' => 4,
            'niveau_etude_requis' => 'Licence en cours ou obtenue',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 12.00,
            'regions_ciblees' => ['Abidjan', 'Yamoussoukro'],
            'documents_requis' => ['CV détaillé', 'Relevés de notes université', 'Portfolio technique', 'Lettre de motivation', 'Attestation de scolarité'],
            'contact_email' => 'stages@abidjannet.ci',
            'contact_telephone' => '+225 27 22 55 44 33',
            'lien_externe' => 'https://www.abidjannet.ci/carrieres',
            'status' => 'published',
            'vues' => 89,
            'candidatures_count' => 12,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire5->id,
            'titre' => 'Stage Ingénierie Hydraulique SODECI',
            'type' => 'stage',
            'description' => 'Stage en génie civil et hydraulique dans nos stations de traitement et réseaux de distribution. Participation aux projets d\'extension du réseau et modernisation des infrastructures.',
            'competences_requises' => ['Génie civil', 'Hydraulique', 'AutoCAD', 'Gestion de projet'],
            'criteres_eligibilite' => ['Étudiant ingénieur en génie civil/hydraulique', 'Niveau L3 minimum', 'Maîtrise des logiciels de dessin technique', 'Motivation pour le service public'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '4 mois',
            'remuneration' => '180 000 XOF/mois',
            'date_debut' => Carbon::parse('2025-10-15'),
            'date_fin' => Carbon::parse('2025-10-15'),
            'date_limite_candidature' => Carbon::parse('2026-03-31'),
            'nombre_places' => 6,
            'niveau_etude_requis' => 'Licence en cours',
            'series_acceptees' => ['C', 'D', 'F1', 'F3'],
            'moyenne_minimum' => 13.00,
            'regions_ciblees' => ['Abidjan', 'Bouaké', 'San-Pédro'],
            'documents_requis' => ['CV', 'Relevés de notes', 'Lettre de motivation', 'Portfolio projets techniques', 'Recommandation professeur'],
            'contact_email' => 'stages@sodeci.ci',
            'contact_telephone' => '+225 21 24 17 00',
            'lien_externe' => 'https://www.sodeci.ci/emploi',
            'status' => 'published',
            'vues' => 156,
            'candidatures_count' => 28,
        ]);

        // === EMPLOI (2 nouveaux) ===
        Opportunite::create([
            'partenaire_id' => $partenaire3->id,
            'titre' => 'Ingénieur Réseau Mobile Junior - MTN',
            'type' => 'emploi',
            'description' => 'Poste d\'ingénieur réseau mobile pour déploiement et maintenance de l\'infrastructure 4G/5G. Responsabilité de l\'optimisation réseau et support technique niveau 2.',
            'competences_requises' => ['Télécommunications', 'Réseau mobile', 'Optimisation RF', 'Protocoles GSM/LTE'],
            'criteres_eligibilite' => ['Diplôme ingénieur télécoms/électronique', 'Max 2 ans d\'expérience', 'Maîtrise anglais technique', 'Mobilité géographique'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => 'CDI',
            'remuneration' => '450 000 XOF/mois + avantages',
            'date_debut' => Carbon::parse('2026-04-01'),
            'date_fin' => null,
            'date_limite_candidature' => Carbon::parse('2026-02-28'),
            'nombre_places' => 3,
            'niveau_etude_requis' => 'Master/Ingénieur',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 12.00,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['CV détaillé', 'Diplômes certifiés', 'Lettre de motivation', 'Références professionnelles', 'Certificats formations'],
            'contact_email' => 'recrutement@mtn.ci',
            'contact_telephone' => '+225 05 05 05 05',
            'lien_externe' => 'https://www.mtn.ci/carrieres',
            'status' => 'published',
            'vues' => 234,
            'candidatures_count' => 47,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire6->id,
            'titre' => 'Chargé de Clientèle Entreprises - Banque Atlantique',
            'type' => 'emploi',
            'description' => 'Gestion d\'un portefeuille de clients entreprises, développement commercial et conseil en solutions bancaires. Formation complète aux produits bancaires et suivi personnalisé.',
            'competences_requises' => ['Relation client', 'Vente', 'Finance', 'Communication', 'Négociation'],
            'criteres_eligibilite' => ['Formation commerciale/finance Bac+3 minimum', 'Expérience commerciale appréciée', 'Excellente présentation', 'Dynamisme et autonomie'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => 'CDI',
            'remuneration' => '350 000 XOF/mois + commissions',
            'date_debut' => Carbon::parse('2026-03-15'),
            'date_fin' => null,
            'date_limite_candidature' => Carbon::parse('2026-02-15'),
            'nombre_places' => 5,
            'niveau_etude_requis' => 'Licence',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 11.00,
            'regions_ciblees' => ['Abidjan', 'Bouaké', 'Daloa'],
            'documents_requis' => ['CV actualisé', 'Lettre de motivation', 'Diplômes', 'Photo d\'identité', 'Casier judiciaire'],
            'contact_email' => 'recrutement@bancatlantique.ci',
            'contact_telephone' => '+225 20 20 00 20',
            'lien_externe' => 'https://www.bancatlantique.ci/emploi',
            'status' => 'published',
            'vues' => 187,
            'candidatures_count' => 52,
        ]);

        // === FORMATION (2 nouveaux) ===
        Opportunite::create([
            'partenaire_id' => $partenaire4->id,
            'titre' => 'Formation Certifiante Cisco CCNA - AbidjanNet',
            'type' => 'formation',
            'description' => 'Formation intensive de 3 mois pour obtenir la certification CISCO CCNA (Routing & Switching). Programme complet avec travaux pratiques sur équipements réels et préparation à l\'examen officiel.',
            'competences_requises' => ['Bases informatique', 'Logique réseau', 'Anglais technique de base'],
            'criteres_eligibilite' => ['Bac+2 en informatique minimum', 'Motivation forte pour les réseaux', 'Disponibilité temps plein', 'Test technique d\'entrée'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '3 mois',
            'remuneration' => 'Formation gratuite + certification',
            'date_debut' => Carbon::parse('2025-09-01'),
            'date_fin' => Carbon::parse('2025-11-31'),
            'date_limite_candidature' => Carbon::parse('2026-03-15'),
            'nombre_places' => 15,
            'niveau_etude_requis' => 'BTS/DUT',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 11.00,
            'regions_ciblees' => ['Abidjan', 'Yamoussoukro'],
            'documents_requis' => ['CV', 'Diplômes', 'Lettre de motivation', 'Test technique', 'Engagement formation'],
            'contact_email' => 'formation@abidjannet.ci',
            'contact_telephone' => '+225 27 22 55 44 33',
            'lien_externe' => 'https://www.abidjannet.ci/formation',
            'status' => 'published',
            'vues' => 145,
            'candidatures_count' => 38,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire5->id,
            'titre' => 'Formation Maintenance Industrielle - SODECI',
            'type' => 'formation',
            'description' => 'Programme de formation en maintenance préventive et curative des équipements de production d\'eau. Formation sur pompes, automatismes, électrotechnique et gestion de maintenance assistée par ordinateur (GMAO).',
            'competences_requises' => ['Électrotechnique', 'Mécanique industrielle', 'Automatismes', 'Lecture de plans'],
            'criteres_eligibilite' => ['Formation technique Bac+2 minimum', 'Expérience maintenance souhaitée', 'Aptitude physique', 'Rigueur et sécurité'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '2 mois',
            'remuneration' => '100 000 XOF/mois + certificat',
            'date_debut' => Carbon::parse('2025-12-01'),
            'date_fin' => Carbon::parse('2025-09-30'),
            'date_limite_candidature' => Carbon::parse('2025-10-15'),
            'nombre_places' => 12,
            'niveau_etude_requis' => 'BTS/DUT',
            'series_acceptees' => ['F1', 'F2', 'F3', 'C'],
            'moyenne_minimum' => 10.00,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['CV', 'Diplômes techniques', 'Certificat médical', 'Lettre de motivation', 'Références'],
            'contact_email' => 'formation@sodeci.ci',
            'contact_telephone' => '+225 21 24 17 00',
            'lien_externe' => 'https://www.sodeci.ci/formation',
            'status' => 'published',
            'vues' => 98,
            'candidatures_count' => 22,
        ]);

        // === CONCOURS (2 nouveaux) ===
        Opportunite::create([
            'partenaire_id' => $partenaire3->id,
            'titre' => 'Concours Innovation Digital MTN 2025',
            'type' => 'concours',
            'description' => 'Concours d\'innovation pour développer des solutions digitales répondant aux défis de connectivité en Afrique. Prix : 5 millions XOF + incubation dans MTN Innovation Hub.',
            'competences_requises' => ['Développement mobile', 'Innovation', 'Business model', 'Présentation'],
            'criteres_eligibilite' => ['Étudiant ou jeune diplômé -30 ans', 'Équipe 2-4 personnes', 'Projet innovant télécoms/fintech', 'Prototype fonctionnel'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '6 mois (développement + finale)',
            'remuneration' => '5 000 000 XOF + incubation',
            'date_debut' => Carbon::parse('2026-03-01'),
            'date_fin' => Carbon::parse('2025-12-31'),
            'date_limite_candidature' => Carbon::parse('2026-02-15'),
            'nombre_places' => 1,
            'niveau_etude_requis' => 'Bac+2 minimum',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 10.00,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['Dossier de candidature', 'Pitch deck', 'Prototype/MVP', 'CV équipe', 'Business plan'],
            'contact_email' => 'innovation@mtn.ci',
            'contact_telephone' => '+225 05 05 05 05',
            'lien_externe' => 'https://www.mtn.ci/innovation',
            'status' => 'published',
            'vues' => 267,
            'candidatures_count' => 43,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire6->id,
            'titre' => 'Challenge Finance Durable Banque Atlantique',
            'type' => 'concours',
            'description' => 'Concours de cas d\'étude en finance durable et inclusion financière. Les équipes doivent proposer des solutions innovantes pour l\'accès au crédit des PME rurales.',
            'competences_requises' => ['Finance', 'Analyse de données', 'Microfinance', 'Présentation'],
            'criteres_eligibilite' => ['Étudiant en finance/économie/gestion', 'Équipe 3-5 personnes', 'Passion pour l\'inclusion financière', 'Connaissance du secteur bancaire'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '3 mois',
            'remuneration' => '2 000 000 XOF + stages',
            'date_debut' => Carbon::parse('2026-04-01'),
            'date_fin' => Carbon::parse('2025-10-30'),
            'date_limite_candidature' => Carbon::parse('2026-03-01'),
            'nombre_places' => 1,
            'niveau_etude_requis' => 'Licence en cours',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 12.00,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['Formulaire inscription équipe', 'CV membres', 'Lettre motivation collective', 'Exemple analyse financière'],
            'contact_email' => 'challenge@bancatlantique.ci',
            'contact_telephone' => '+225 20 20 00 20',
            'lien_externe' => 'https://www.bancatlantique.ci/challenge',
            'status' => 'published',
            'vues' => 134,
            'candidatures_count' => 18,
        ]);

        // === EVENT (2 nouveaux) ===
        Opportunite::create([
            'partenaire_id' => $partenaire4->id,
            'titre' => 'Forum Cybersécurité Afrique de l\'Ouest 2025',
            'type' => 'event',
            'description' => 'Grand forum régional sur la cybersécurité avec conférences, ateliers pratiques et networking. Participation de 200+ professionnels et experts internationaux.',
            'competences_requises' => ['Intérêt cybersécurité', 'Niveau technique', 'Networking'],
            'criteres_eligibilite' => ['Étudiant/professionnel IT', 'Intérêt confirmé pour la sécurité', 'Participation active', 'Engagement 2 jours complets'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '2 jours',
            'remuneration' => 'Participation gratuite + certificat',
            'date_debut' => Carbon::parse('2025-09-15'),
            'date_fin' => Carbon::parse('2025-09-16'),
            'date_limite_candidature' => Carbon::parse('2025-12-15'),
            'nombre_places' => 200,
            'niveau_etude_requis' => 'Bac+2 minimum',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 10.00,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['Formulaire inscription', 'CV', 'Lettre de motivation', 'Justificatif études/emploi'],
            'contact_email' => 'forum@abidjannet.ci',
            'contact_telephone' => '+225 27 22 55 44 33',
            'lien_externe' => 'https://www.cybersecurityforum.ci',
            'status' => 'published',
            'vues' => 189,
            'candidatures_count' => 67,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire5->id,
            'titre' => 'Journées Portes Ouvertes SODECI - Métiers de l\'Eau',
            'type' => 'event',
            'description' => 'Découverte des métiers de l\'eau et de l\'assainissement. Visite des installations, démonstrations techniques et rencontres avec les professionnels. Orientation carrière.',
            'competences_requises' => ['Curiosité technique', 'Intérêt environnement'],
            'criteres_eligibilite' => ['Étudiant ou lycéen série scientifique', 'Intérêt pour l\'environnement', 'Projet professionnel en lien'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Abidjan',
            'duree' => '1 jour',
            'remuneration' => 'Gratuit + goodies',
            'date_debut' => Carbon::parse('2025-11-20'),
            'date_fin' => Carbon::parse('2025-11-20'),
            'date_limite_candidature' => Carbon::parse('2025-11-10'),
            'nombre_places' => 150,
            'niveau_etude_requis' => 'Lycée/Supérieur',
            'series_acceptees' => ['C', 'D', 'F1', 'F3'],
            'moyenne_minimum' => 10.00,
            'regions_ciblees' => ['Abidjan', 'Bouaké'],
            'documents_requis' => ['Formulaire inscription', 'Pièce d\'identité', 'Certificat scolarité'],
            'contact_email' => 'communication@sodeci.ci',
            'contact_telephone' => '+225 21 24 17 00',
            'lien_externe' => 'https://www.sodeci.ci/evenements',
            'status' => 'published',
            'vues' => 95,
            'candidatures_count' => 32,
        ]);

        // === PROMOTION (2 nouveaux) ===
        Opportunite::create([
            'partenaire_id' => $partenaire3->id,
            'titre' => 'Offre Spéciale Étudiants MTN Campus Connect',
            'type' => 'promotion',
            'description' => 'Forfait mobile spécial étudiants : 50Go internet + appels illimités MTN + 1000 SMS pour seulement 5000 XOF/mois. Inclus applications éducatives gratuites.',
            'competences_requises' => [],
            'criteres_eligibilite' => ['Statut étudiant vérifié', 'Carte d\'étudiant valide', 'Pièce d\'identité', 'Engagement 12 mois minimum'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Toutes les villes',
            'duree' => '12 mois renouvelable',
            'remuneration' => '5 000 XOF/mois (au lieu de 15 000)',
            'date_debut' => Carbon::parse('2026-02-01'),
            'date_fin' => Carbon::parse('2025-12-31'),
            'date_limite_candidature' => Carbon::parse('2025-11-30'),
            'nombre_places' => 10000,
            'niveau_etude_requis' => 'Étudiant inscrit',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 0.00,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['Carte étudiant', 'Pièce identité', 'Certificat scolarité', 'Photo'],
            'contact_email' => 'campus@mtn.ci',
            'contact_telephone' => '+225 05 05 05 05',
            'lien_externe' => 'https://www.mtn.ci/campus',
            'status' => 'published',
            'vues' => 456,
            'candidatures_count' => 234,
        ]);

        Opportunite::create([
            'partenaire_id' => $partenaire6->id,
            'titre' => 'Compte Jeune Banque Atlantique - Avantages Exclusifs',
            'type' => 'promotion',
            'description' => 'Compte bancaire gratuit pour jeunes 18-25 ans avec carte Visa gratuite, frais de virement réduits, accès mobile banking premium et conseil financier personnalisé.',
            'competences_requises' => [],
            'criteres_eligibilite' => ['Âge 18-25 ans', 'Première ouverture de compte', 'Justificatif revenus/bourse', 'Pas d\'interdiction bancaire'],
            'pays' => 'Côte d\'Ivoire',
            'ville' => 'Toutes les agences',
            'duree' => 'Jusqu\'à 25 ans',
            'remuneration' => 'Compte + carte gratuits',
            'date_debut' => Carbon::parse('2026-01-15'),
            'date_fin' => Carbon::parse('2025-12-31'),
            'date_limite_candidature' => Carbon::parse('2025-12-15'),
            'nombre_places' => 5000,
            'niveau_etude_requis' => 'Aucun',
            'series_acceptees' => ['Toutes'],
            'moyenne_minimum' => 0.00,
            'regions_ciblees' => ['Toutes les régions'],
            'documents_requis' => ['Pièce d\'identité', 'Justificatif domicile', 'Justificatif revenus', 'Photo d\'identité'],
            'contact_email' => 'jeunes@bancatlantique.ci',
            'contact_telephone' => '+225 20 20 00 20',
            'lien_externe' => 'https://www.bancatlantique.ci/jeunes',
            'status' => 'published',
            'vues' => 289,
            'candidatures_count' => 156,
        ]);

        $this->call(DotationSeeder::class);

        $this->command->info('✅ Seeders créés avec succès !');
        $this->command->info('👤 Admin principal: admin@ansut.ci');
        $this->command->info('👥 Admins supplémentaires: 11 comptes créés');
        $this->command->info('   - lamine.barro@etudesk.org');
        $this->command->info('   - peub@ansut.ci');
        $this->command->info('   - marius.kanga@ansut.ci');
        $this->command->info('   - mamadou.soumahoro@ansut.ci');
        $this->command->info('   - serge.yao@ansut.ci');
        $this->command->info('   - anicet.korandji@ansut.ci');
        $this->command->info('   - ghislain.memel@ansut.ci');
        $this->command->info('   - mohamad.kone@ansut.ci');
        $this->command->info('   - sarrah.coulibaly@ansut.ci');
        $this->command->info('   - bagnogona.traore@ansut.ci');
        $this->command->info('   - salime.toure@ansut.ci');
        $this->command->info('🎓 Bachelier 1: kouame.jean@gmail.com (Boursier PEUB)');
        $this->command->info('🎓 Bachelier 2: traore.fatou@yahoo.fr (Candidat)');
        $this->command->info('🏢 Partenaire 1: contact@orange.ci (Orange CI)');
        $this->command->info('🏢 Partenaire 2: bourses@nestleci.com (Nestlé CI)');
        $this->command->info('🏢 Partenaire 3: rh@mtn.ci (MTN CI)');
        $this->command->info('🏢 Partenaire 4: recrutement@abidjannet.ci (AbidjanNet)');
        $this->command->info('🏢 Partenaire 5: contact@sodeci.ci (SODECI)');
        $this->command->info('🏢 Partenaire 6: emploi@bancatlantique.ci (Banque Atlantique)');
        $this->command->info('📊 17 opportunités créées au total');
        $this->command->info('   - 4 Bourses (Orange Digital, Nestlé Agro, MTN Télécoms, Banque Atlantique Finance)');
        $this->command->info('   - 3 Stages (Orange Summer Tech, AbidjanNet Cybersécurité, SODECI Hydraulique)');
        $this->command->info('   - 2 Emplois (MTN Ingénieur Réseau, Banque Atlantique Chargé Clientèle)');
        $this->command->info('   - 2 Formations (AbidjanNet CCNA, SODECI Maintenance)');
        $this->command->info('   - 2 Concours (MTN Innovation Digital, Banque Atlantique Finance Durable)');
        $this->command->info('   - 2 Events (Forum Cybersécurité, Journées Portes Ouvertes SODECI)');
        $this->command->info('   - 2 Promotions (MTN Campus Connect, Banque Atlantique Compte Jeune)');

        $this->command->info('💰 Données de test créées avec succès !');
    }
}

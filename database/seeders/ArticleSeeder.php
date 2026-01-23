<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin temporaire pour les articles si aucun n'existe
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'email' => 'temp.admin@ansut.ci',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        $articles = [
            [
                'titre' => 'Lancement officiel de PEUB 2024 : 500 nouvelles bourses disponibles',
                'contenu' => "Le Programme d'Excellence Universelle pour les Bacheliers (PEUB) annonce officiellement l'ouverture de 500 nouvelles bourses d'études pour l'année académique 2024-2025. Cette initiative majeure s'inscrit dans la volonté du gouvernement de promouvoir l'excellence académique et de favoriser l'accès à l'enseignement supérieur de qualité pour tous les jeunes talents ivoiriens.

                Cette année, le programme a renforcé ses partenariats avec les meilleures universités internationales, offrant ainsi aux boursiers un éventail plus large d'opportunités d'études dans des domaines stratégiques pour le développement du pays.

                Les candidatures sont ouvertes dès maintenant et se clôtureront le 31 mars 2024. Les critères de sélection incluent l'excellence académique, le leadership et l'engagement communautaire.

                Pour plus d'informations sur les modalités de candidature, consultez notre guide complet sur le portail PEUB.",
                'resume' => "Le Programme PEUB annonce l'ouverture de 500 nouvelles bourses pour 2024 avec des partenariats universitaires renforcés.",
                'categorie' => 'annonce',
                'tags' => ['bourses', 'PEUB 2024', 'enseignement supérieur', 'excellence'],
                'status' => 'published',
                'date_publication' => now()->subDays(5),
                'featured' => true,
                'temps_lecture' => 3,
                'meta_description' => "Découvrez les 500 nouvelles bourses PEUB 2024 et les opportunités d'études dans les meilleures universités internationales.",
                'vues' => 1250,
                'ordre_affichage' => 1
            ],
            [
                'titre' => 'Partenariat stratégique avec l\'Université McGill pour l\'ingénierie',
                'contenu' => "L'ANSUT et l'Université McGill du Canada signent un accord de partenariat stratégique pour offrir des programmes d'excellence en ingénierie aux boursiers PEUB.

                Ce partenariat historique permettra aux étudiants ivoiriens d'accéder à des formations de pointe en génie civil, génie informatique, génie électrique et génie mécanique dans l'une des universités les plus prestigieuses au monde.

                Les premiers étudiants bénéficieront de ce programme dès la rentrée 2024, avec un accompagnement personnalisé et des stages en entreprise au Canada.

                Cette collaboration s'inscrit dans la stratégie de l'ANSUT de diversifier les destinations d'études et d'offrir aux jeunes ivoiriens les meilleures opportunités de formation internationale.",
                'resume' => "Nouveau partenariat avec McGill University pour des programmes d'ingénierie d'excellence destinés aux boursiers PEUB.",
                'categorie' => 'partenariat',
                'tags' => ['McGill', 'ingénierie', 'Canada', 'partenariat'],
                'status' => 'published',
                'date_publication' => now()->subDays(8),
                'featured' => false,
                'temps_lecture' => 2,
                'meta_description' => "Découvrez le nouveau partenariat entre l'ANSUT et l'Université McGill pour l'excellence en ingénierie.",
                'vues' => 890,
                'ordre_affichage' => 0
            ],
            [
                'titre' => 'Webinaire d\'orientation : Choisir sa filière d\'études supérieures',
                'contenu' => "L'ANSUT organise un webinaire gratuit d'orientation académique le 15 février 2024 pour aider les futurs candidats au programme PEUB à faire les meilleurs choix de filières d'études.

                Ce webinaire interactif abordera les thématiques suivantes :
                - Les filières porteuses d'emploi en Côte d'Ivoire et à l'international
                - Comment aligner ses passions avec les besoins du marché du travail
                - Les critères de choix d'une université et d'un programme d'études
                - Témoignages d'anciens boursiers PEUB

                Les participants pourront poser leurs questions en direct aux conseillers d'orientation et aux représentants des universités partenaires.

                Inscription gratuite sur notre plateforme en ligne. Places limitées à 500 participants.",
                'resume' => "Webinaire gratuit d'orientation pour guider les futurs candidats dans leurs choix d'études supérieures.",
                'categorie' => 'evenement',
                'tags' => ['orientation', 'webinaire', 'études supérieures', 'conseil'],
                'status' => 'published',
                'date_publication' => now()->subDays(10),
                'featured' => false,
                'temps_lecture' => 2,
                'meta_description' => "Participez au webinaire d'orientation PEUB pour faire les meilleurs choix d'études supérieures.",
                'vues' => 650,
                'ordre_affichage' => 0
            ],
            [
                'titre' => 'Cérémonie de remise des Prix d\'Excellence PEUB 2023',
                'contenu' => "La cérémonie annuelle de remise des Prix d'Excellence PEUB s'est tenue le 20 décembre 2023 au Palais de la Culture d'Abidjan, en présence du Ministre de l'Enseignement Supérieur et de la Recherche Scientifique.

                Cette cérémonie a récompensé les 50 meilleurs étudiants boursiers de la promotion 2023, qui se sont distingués par leurs résultats académiques exceptionnels et leur engagement communautaire.

                Parmi les lauréats, nous comptons :
                - 15 étudiants avec mention Très Bien
                - 20 étudiants avec mention Bien
                - 15 étudiants primés pour leur leadership étudiant

                Chaque lauréat a reçu une bourse d'excellence de 500 000 FCFA ainsi qu'un certificat de reconnaissance.

                Ces résultats confirment la qualité du programme PEUB et l'excellence des jeunes talents ivoiriens.",
                'resume' => "Cérémonie de récompense des 50 meilleurs boursiers PEUB 2023 pour leur excellence académique et leur leadership.",
                'categorie' => 'success',
                'tags' => ['prix', 'excellence', 'cérémonie', 'récompense'],
                'status' => 'published',
                'date_publication' => now()->subDays(15),
                'featured' => true,
                'temps_lecture' => 3,
                'meta_description' => "Découvrez les lauréats des Prix d'Excellence PEUB 2023 et leurs remarquables réalisations académiques.",
                'vues' => 1100,
                'ordre_affichage' => 0
            ],
            [
                'titre' => 'Programme de mentorat : Accompagner la réussite des boursiers',
                'contenu' => "L'ANSUT lance un nouveau programme de mentorat pour accompagner les boursiers PEUB dans leur parcours académique et professionnel.

                Ce programme innovant met en relation chaque boursier avec un mentor expérimenté, professionnel accompli dans son domaine d'études. Les mentors sont des alumni du programme PEUB, des cadres d'entreprises partenaires ou des professeurs d'universités.

                Les objectifs du programme :
                - Orientation académique personnalisée
                - Développement des compétences professionnelles
                - Networking et construction d'un réseau professionnel
                - Préparation à l'insertion professionnelle

                Les premières sessions de mentorat débuteront en mars 2024 avec 200 binômes mentor-boursier.

                Ce programme s'inspire des meilleures pratiques internationales et vise à maximiser les chances de réussite de nos boursiers.",
                'resume' => "Nouveau programme de mentorat pour accompagner personnellement chaque boursier PEUB vers la réussite.",
                'categorie' => 'formation',
                'tags' => ['mentorat', 'accompagnement', 'réussite', 'networking'],
                'status' => 'published',
                'date_publication' => now()->subDays(3),
                'featured' => false,
                'temps_lecture' => 2,
                'meta_description' => "Découvrez le nouveau programme de mentorat PEUB pour maximiser la réussite des boursiers.",
                'vues' => 780,
                'ordre_affichage' => 0
            ],
            [
                'titre' => 'Rapport d\'impact PEUB 2023 : Des résultats encourageants',
                'contenu' => "Le rapport d'impact 2023 du Programme PEUB révèle des résultats très encourageants qui témoignent de la réussite de cette initiative d'excellence.

                Chiffres clés 2023 :
                - 1 200 boursiers actifs dans 15 pays
                - 94% de taux de réussite académique
                - 280 diplômes obtenus avec mention
                - 150 stages effectués dans des entreprises de premier plan
                - 85% d'insertion professionnelle dans les 6 mois

                Répartition par domaines d'études :
                - Ingénierie et technologies : 35%
                - Sciences de la santé : 25%
                - Sciences économiques et gestion : 20%
                - Sciences humaines et sociales : 15%
                - Autres : 5%

                Ces résultats confirment l'impact positif du programme sur le développement du capital humain ivoirien et justifient l'augmentation des investissements pour 2024.",
                'resume' => "Le rapport 2023 révèle un taux de réussite de 94% et une excellente insertion professionnelle des boursiers PEUB.",
                'categorie' => 'actualite',
                'tags' => ['rapport', 'impact', 'statistiques', 'réussite'],
                'status' => 'published',
                'date_publication' => now()->subDays(20),
                'featured' => false,
                'temps_lecture' => 4,
                'meta_description' => "Consultez le rapport d'impact PEUB 2023 avec un taux de réussite exceptionnel de 94%.",
                'vues' => 950,
                'ordre_affichage' => 0
            ]
        ];

        foreach ($articles as $articleData) {
            Article::firstOrCreate(
                ['titre' => $articleData['titre']],
                [
                    'contenu' => $articleData['contenu'],
                    'resume' => $articleData['resume'],
                    'categorie' => $articleData['categorie'],
                    'tags' => $articleData['tags'],
                    'auteur_id' => $admin->id,
                    'status' => $articleData['status'],
                    'date_publication' => $articleData['date_publication'],
                    'featured' => $articleData['featured'],
                    'temps_lecture' => $articleData['temps_lecture'],
                    'meta_description' => $articleData['meta_description'],
                    'vues' => $articleData['vues'],
                    'ordre_affichage' => $articleData['ordre_affichage']
                ]
            );
        }
    }
} 
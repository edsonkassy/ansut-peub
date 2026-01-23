<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LibraryResource;
use App\Models\LibraryCategory;
use App\Models\User;

class LibraryResourceSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les catégories et un utilisateur admin
        $categories = LibraryCategory::all();
        $adminUser = User::where('role', 'admin')->first();

        if (!$adminUser) {
            $this->command->warn('Aucun utilisateur admin trouvé. Création d\'un utilisateur admin par défaut.');
            $adminUser = User::factory()->create([
                'role' => 'admin',
                'name' => 'Administrateur PEUB',
                'email' => 'admin@peub.ci'
            ]);
        }

        $resources = [
            // Informatique & Technologies
            [
                'category' => 'informatique-technologies',
                'title' => 'Guide Complet du Développement Web avec Laravel',
                'description' => 'Un guide exhaustif pour maîtriser le framework Laravel, de l\'installation aux fonctionnalités avancées. Inclut des exemples pratiques, des projets concrets et les meilleures pratiques de développement.',
                'type' => 'pdf',
                'author' => 'Dr. Jean-Baptiste Kouassi',
                'tags' => ['laravel', 'php', 'développement web', 'backend'],
                'level' => 'intermediaire',
                'language' => 'fr',
                'external_url' => 'https://laravel.com/docs',
                'is_featured' => true
            ],
            [
                'category' => 'informatique-technologies',
                'title' => 'Introduction à l\'Intelligence Artificielle',
                'description' => 'Découvrez les concepts fondamentaux de l\'IA, les algorithmes d\'apprentissage automatique et leurs applications pratiques dans différents secteurs.',
                'type' => 'video',
                'author' => 'Prof. Marie Traoré',
                'tags' => ['ia', 'machine learning', 'python', 'algorithmes'],
                'level' => 'debutant',
                'language' => 'fr',
                'duration' => '2h 30min',
                'external_url' => 'https://youtube.com/watch?v=example1',
                'is_featured' => true
            ],

            // Entrepreneuriat & Business
            [
                'category' => 'entrepreneuriat-business',
                'title' => 'Créer son Entreprise en Côte d\'Ivoire - Guide Pratique 2024',
                'description' => 'Guide complet pour créer et développer son entreprise en Côte d\'Ivoire. Démarches administratives, financements, stratégies de croissance et études de cas de succès locaux.',
                'type' => 'pdf',
                'author' => 'Chambre de Commerce d\'Abidjan',
                'tags' => ['entrepreneuriat', 'création entreprise', 'côte d\'ivoire', 'business plan'],
                'level' => 'debutant',
                'language' => 'fr',
                'external_url' => 'https://example.com/guide-entreprise-ci',
                'is_featured' => false
            ],
            [
                'category' => 'entrepreneuriat-business',
                'title' => 'Stratégies de Marketing Digital pour l\'Afrique',
                'description' => 'Webinaire sur les stratégies marketing adaptées au marché africain, avec focus sur les réseaux sociaux, l\'e-commerce et le marketing mobile.',
                'type' => 'video',
                'author' => 'Agence Digital Africa',
                'tags' => ['marketing digital', 'afrique', 'réseaux sociaux', 'e-commerce'],
                'level' => 'intermediaire',
                'language' => 'fr',
                'duration' => '1h 45min',
                'external_url' => 'https://youtube.com/watch?v=example2'
            ],

            // Sciences & Ingénierie
            [
                'category' => 'sciences-ingenierie',
                'title' => 'Mathématiques Appliquées à l\'Ingénierie',
                'description' => 'Cours complet de mathématiques pour ingénieurs : analyse vectorielle, équations différentielles, transformées de Fourier et applications pratiques.',
                'type' => 'pdf',
                'author' => 'Institut National Polytechnique HB',
                'tags' => ['mathématiques', 'ingénierie', 'analyse', 'équations différentielles'],
                'level' => 'avance',
                'language' => 'fr',
                'external_url' => 'https://example.com/maths-ingenierie'
            ],

            // Santé & Médecine
            [
                'category' => 'sante-medecine',
                'title' => 'Pharmacologie Clinique - Médicaments Essentiels',
                'description' => 'Manuel de pharmacologie clinique couvrant les médicaments essentiels, leurs mécanismes d\'action, posologies et effets secondaires.',
                'type' => 'pdf',
                'author' => 'Faculté de Médecine d\'Abidjan',
                'tags' => ['pharmacologie', 'médicaments', 'clinique', 'santé'],
                'level' => 'avance',
                'language' => 'fr',
                'external_url' => 'https://example.com/pharmacologie'
            ],
            [
                'category' => 'sante-medecine',
                'title' => 'Prévention et Gestion des Maladies Tropicales',
                'description' => 'Conférence sur la prévention et la prise en charge des maladies tropicales courantes en Afrique de l\'Ouest.',
                'type' => 'video',
                'author' => 'OMS Afrique',
                'tags' => ['maladies tropicales', 'prévention', 'santé publique', 'afrique'],
                'level' => 'intermediaire',
                'language' => 'fr',
                'duration' => '3h 15min',
                'external_url' => 'https://youtube.com/watch?v=example3'
            ],

            // Économie & Finance
            [
                'category' => 'economie-finance',
                'title' => 'Analyse Financière et Évaluation d\'Entreprise',
                'description' => 'Guide pratique pour analyser la santé financière d\'une entreprise, calculer sa valeur et prendre des décisions d\'investissement éclairées.',
                'type' => 'presentation',
                'author' => 'BCEAO - Banque Centrale',
                'tags' => ['analyse financière', 'évaluation', 'investissement', 'finance'],
                'level' => 'intermediaire',
                'language' => 'fr',
                'external_url' => 'https://example.com/analyse-financiere'
            ],

            // Communication & Marketing
            [
                'category' => 'communication-marketing',
                'title' => 'Communication de Crise à l\'Ère Numérique',
                'description' => 'Stratégies et outils pour gérer efficacement une crise de communication sur les réseaux sociaux et les médias traditionnels.',
                'type' => 'audio',
                'author' => 'École de Journalisme d\'Abidjan',
                'tags' => ['communication de crise', 'réseaux sociaux', 'gestion de crise', 'digital'],
                'level' => 'intermediaire',
                'language' => 'fr',
                'duration' => '45min',
                'external_url' => 'https://example.com/communication-crise'
            ],

            // Agriculture & Environnement
            [
                'category' => 'agriculture-environnement',
                'title' => 'Agriculture Intelligente et Technologies Vertes',
                'description' => 'Document sur les innovations technologiques en agriculture : IoT, drones, intelligence artificielle pour optimiser les rendements tout en préservant l\'environnement.',
                'type' => 'document',
                'author' => 'Centre de Recherche Agricole',
                'tags' => ['agriculture intelligente', 'technologie verte', 'innovation', 'durabilité'],
                'level' => 'avance',
                'language' => 'fr',
                'external_url' => 'https://example.com/agriculture-intelligente',
                'is_featured' => true
            ]
        ];

        foreach ($resources as $resourceData) {
            $category = $categories->where('slug', $resourceData['category'])->first();
            
            if ($category) {
                LibraryResource::create([
                    'library_category_id' => $category->id,
                    'user_id' => $adminUser->id,
                    'title' => $resourceData['title'],
                    'slug' => \Illuminate\Support\Str::slug($resourceData['title']),
                    'description' => $resourceData['description'],
                    'type' => $resourceData['type'],
                    'external_url' => $resourceData['external_url'],
                    'author' => $resourceData['author'],
                    'tags' => $resourceData['tags'],
                    'level' => $resourceData['level'] ?? null,
                    'language' => $resourceData['language'],
                    'duration' => $resourceData['duration'] ?? null,
                    'views_count' => rand(50, 500),
                    'downloads_count' => rand(10, 100),
                    'is_featured' => $resourceData['is_featured'] ?? false,
                    'is_active' => true,
                    'published_at' => now()->subDays(rand(1, 30))
                ]);
            }
        }

        $this->command->info('10 ressources créées avec succès !');
    }
}
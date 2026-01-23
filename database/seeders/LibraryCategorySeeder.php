<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LibraryCategory;

class LibraryCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Informatique & Technologies',
                'slug' => 'informatique-technologies',
                'description' => 'Ressources en programmation, développement web, intelligence artificielle, cybersécurité et nouvelles technologies',
                'icon' => 'code',
                'color' => '#3B82F6',
                'is_active' => true
            ],
            [
                'name' => 'Entrepreneuriat & Business',
                'slug' => 'entrepreneuriat-business',
                'description' => 'Guides pour créer et développer son entreprise, business plan, marketing digital, gestion financière',
                'icon' => 'briefcase',
                'color' => '#10B981',
                'is_active' => true
            ],
            [
                'name' => 'Sciences & Ingénierie',
                'slug' => 'sciences-ingenierie',
                'description' => 'Mathématiques, physique, chimie, génie civil, génie électrique et autres disciplines scientifiques',
                'icon' => 'beaker',
                'color' => '#8B5CF6',
                'is_active' => true
            ],
            [
                'name' => 'Santé & Médecine',
                'slug' => 'sante-medecine',
                'description' => 'Ressources médicales, pharmacie, soins infirmiers, santé publique et recherche biomédicale',
                'icon' => 'heart-pulse',
                'color' => '#EF4444',
                'is_active' => true
            ],
            [
                'name' => 'Économie & Finance',
                'slug' => 'economie-finance',
                'description' => 'Économie, comptabilité, finance, banque, assurance et gestion des investissements',
                'icon' => 'trending-up',
                'color' => '#F59E0B',
                'is_active' => true
            ],
            [
                'name' => 'Droit & Sciences Juridiques',
                'slug' => 'droit-sciences-juridiques',
                'description' => 'Droit des affaires, droit public, droit international, procédures judiciaires',
                'icon' => 'scale',
                'color' => '#6B7280',
                'is_active' => true
            ],
            [
                'name' => 'Communication & Marketing',
                'slug' => 'communication-marketing',
                'description' => 'Communication digitale, relations publiques, publicité, stratégies marketing et branding',
                'icon' => 'megaphone',
                'color' => '#EC4899',
                'is_active' => true
            ],
            [
                'name' => 'Langues & Littérature',
                'slug' => 'langues-litterature',
                'description' => 'Apprentissage des langues, littérature, linguistique, traduction et communication interculturelle',
                'icon' => 'languages',
                'color' => '#06B6D4',
                'is_active' => true
            ],
            [
                'name' => 'Arts & Design',
                'slug' => 'arts-design',
                'description' => 'Arts visuels, design graphique, architecture, photographie, musique et arts du spectacle',
                'icon' => 'palette',
                'color' => '#F97316',
                'is_active' => true
            ],
            [
                'name' => 'Agriculture & Environnement',
                'slug' => 'agriculture-environnement',
                'description' => 'Agriculture moderne, développement durable, gestion environnementale, écologie',
                'icon' => 'leaf',
                'color' => '#84CC16',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            LibraryCategory::firstOrCreate(
                ['slug' => $category['slug']], 
                $category
            );
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumCategory;

class ForumCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Orientation Universitaire',
                'description' => 'Questions sur le choix d\'université, de filières et d\'orientation post-bac.',
                'slug' => 'orientation-universitaire',
                'color' => '#3B82F6',
                'icon' => 'compass',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Vie Étudiante',
                'description' => 'Discussions sur la vie quotidienne à l\'université, logement, transport, etc.',
                'slug' => 'vie-etudiante',
                'color' => '#10B981',
                'icon' => 'home',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Bourses et Financements',
                'description' => 'Informations et questions sur les bourses d\'études, financements et aides.',
                'slug' => 'bourses-financements',
                'color' => '#F59E0B',
                'icon' => 'dollar-sign',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Opportunités Professionnelles',
                'description' => 'Stages, emplois, concours et opportunités de carrière.',
                'slug' => 'opportunites-professionnelles',
                'color' => '#8B5CF6',
                'icon' => 'briefcase',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Études à l\'Étranger',
                'description' => 'Discussions sur les études internationales, échanges universitaires et mobilité.',
                'slug' => 'etudes-etranger',
                'color' => '#EF4444',
                'icon' => 'globe',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Aide aux Études',
                'description' => 'Entraide pour les cours, révisions, méthodes de travail et examens.',
                'slug' => 'aide-etudes',
                'color' => '#06B6D4',
                'icon' => 'help-circle',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Projets Étudiants',
                'description' => 'Présentation et discussion autour de projets étudiants, initiatives et associations.',
                'slug' => 'projets-etudiants',
                'color' => '#EC4899',
                'icon' => 'lightbulb',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Questions Générales',
                'description' => 'Discussions générales qui ne rentrent pas dans les autres catégories.',
                'slug' => 'questions-generales',
                'color' => '#6B7280',
                'icon' => 'message-circle',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Annonces PEUB',
                'description' => 'Annonces officielles et informations importantes du programme PEUB.',
                'slug' => 'annonces-peub',
                'color' => '#52423A',
                'icon' => 'megaphone',
                'sort_order' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ForumCategory::create($category);
        }
    }
}
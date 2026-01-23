<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LibraryResource;
use App\Models\LibraryComment;
use App\Models\LibraryFavorite;
use App\Models\LibraryLike;
use App\Models\Bachelier;
use App\Models\User;

class LibraryInteractionsSeeder extends Seeder
{
    public function run()
    {
        $resources = LibraryResource::all();
        $bacheliers = Bachelier::with('user')->get();
        
        if ($resources->isEmpty() || $bacheliers->isEmpty()) {
            $this->command->warn('Pas assez de ressources ou de bacheliers pour créer des interactions.');
            return;
        }

        // Créer des commentaires réalistes
        $comments = [
            [
                'content' => 'Excellente ressource ! Laravel est vraiment bien expliqué ici. Merci beaucoup pour ce partage.',
                'resource_title' => 'Guide Complet du Développement Web avec Laravel'
            ],
            [
                'content' => 'Cette introduction à l\'IA est parfaite pour débuter. Les concepts sont clairs et les exemples pratiques.',
                'resource_title' => 'Introduction à l\'Intelligence Artificielle'
            ],
            [
                'content' => 'Très utile pour comprendre les démarches administratives en Côte d\'Ivoire. Document à télécharger absolument !',
                'resource_title' => 'Créer son Entreprise en Côte d\'Ivoire - Guide Pratique 2024'
            ],
            [
                'content' => 'Le webinaire est excellent. Les stratégies présentées sont vraiment adaptées au contexte africain.',
                'resource_title' => 'Stratégies de Marketing Digital pour l\'Afrique'
            ],
            [
                'content' => 'Niveau avancé mais très complet. Parfait pour approfondir ses connaissances en mathématiques appliquées.',
                'resource_title' => 'Mathématiques Appliquées à l\'Ingénierie'
            ]
        ];

        $replies = [
            'Merci pour ce retour positif ! N\'hésitez pas à partager avec vos camarades.',
            'Content que ça vous ait plu ! Y a-t-il d\'autres sujets qui vous intéressent ?',
            'Effectivement, c\'est un document très pratique. Bonne chance pour vos projets !',
            'Merci ! Nous préparons d\'autres contenus sur ce thème.',
            'Courage pour les maths ! C\'est effectivement du niveau avancé mais très formateur.'
        ];

        foreach ($comments as $index => $commentData) {
            $resource = $resources->where('title', $commentData['resource_title'])->first();
            $bachelier = $bacheliers->random();
            
            if ($resource && $bachelier->user) {
                // Créer le commentaire principal
                $comment = LibraryComment::create([
                    'library_resource_id' => $resource->id,
                    'user_id' => $bachelier->user->id,
                    'content' => $commentData['content'],
                    'is_approved' => true
                ]);

                // Créer une réponse
                if (isset($replies[$index])) {
                    $replier = $bacheliers->where('id', '!=', $bachelier->id)->random();
                    if ($replier->user) {
                        LibraryComment::create([
                            'library_resource_id' => $resource->id,
                            'user_id' => $replier->user->id,
                            'parent_id' => $comment->id,
                            'content' => $replies[$index],
                            'is_approved' => true
                        ]);
                    }
                }

                // Ajouter quelques likes sur les commentaires
                if (rand(1, 3) == 1) {
                    $liker = $bacheliers->random();
                    if ($liker->user && $liker->user->id !== $bachelier->user->id) {
                        LibraryLike::create([
                            'user_id' => $liker->user->id,
                            'library_resource_id' => $resource->id,
                            'likeable_type' => LibraryComment::class,
                            'likeable_id' => $comment->id
                        ]);
                    }
                }
            }
        }

        // Créer des favoris aléatoires
        foreach ($bacheliers->take(2) as $bachelier) {
            $favoriteResources = $resources->random(rand(2, 4));
            foreach ($favoriteResources as $resource) {
                LibraryFavorite::firstOrCreate([
                    'library_resource_id' => $resource->id,
                    'user_id' => $bachelier->user->id
                ]);
            }
        }

        // Créer des likes sur les ressources
        foreach ($resources as $resource) {
            $likersCount = min(rand(1, 3), $bacheliers->count());
            $likers = $bacheliers->random($likersCount);
            foreach ($likers as $bachelier) {
                if ($bachelier->user) {
                    LibraryLike::firstOrCreate([
                        'library_resource_id' => $resource->id,
                        'user_id' => $bachelier->user->id,
                        'likeable_type' => null,
                        'likeable_id' => null
                    ]);
                }
            }
        }

        // Incrémenter les compteurs de vues de manière réaliste
        foreach ($resources as $resource) {
            $resource->increment('views_count', rand(20, 150));
            $resource->increment('downloads_count', rand(5, 40));
        }

        $this->command->info('Interactions créées avec succès !');
        $this->command->info('- Commentaires avec réponses ajoutés');
        $this->command->info('- Favoris distribués');
        $this->command->info('- Likes ajoutés');
        $this->command->info('- Compteurs de vues et téléchargements mis à jour');
    }
}
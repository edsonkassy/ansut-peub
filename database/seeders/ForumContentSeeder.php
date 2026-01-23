<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use App\Models\ForumReaction;
use App\Models\User;
use Illuminate\Support\Str;

class ForumContentSeeder extends Seeder
{
    public function run(): void
    {
        $bacheliers = User::where('role', 'bachelier')->get();
        $categories = ForumCategory::all();

        if ($bacheliers->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Aucun bachelier ou catégorie trouvé. Exécutez d\'abord les seeders appropriés.');
            return;
        }

        $threads = [
            [
                'category' => 'Annonces PEUB',
                'title' => 'Bienvenue sur le forum des bacheliers PEUB !',
                'content' => "Bonjour à tous et bienvenues sur le forum officiel du Programme d'Excellence Universitaire de Côte d'Ivoire !

Ce forum est votre espace d'échange et d'entraide. Vous pouvez :
- Poser vos questions sur l'orientation universitaire
- Partager vos expériences de vie étudiante
- Échanger sur les opportunités de bourses et de stages
- Vous entraider pour vos études

Quelques règles de bonne conduite :
1. Respectez vos pairs et leurs opinions
2. Utilisez un langage approprié
3. Cherchez avant de poser une question déjà traitée
4. Partagez vos connaissances et expériences

Bonne discussion à tous !

L'équipe PEUB",
                'tags' => ['bienvenue', 'règles', 'présentation'],
                'is_pinned' => true,
                'is_featured' => true,
            ],
            [
                'category' => 'Orientation Universitaire',
                'title' => 'Quelle filière choisir entre informatique et génie électrique ?',
                'content' => "Salut tout le monde !

Je suis en terminale C et j'hésite beaucoup entre deux filières pour l'université :
- Informatique/Génie logiciel
- Génie électrique/Électronique

J'aime bien les maths et la physique, et je me débrouille pas mal en programmation. Côté débouchés, qu'est-ce qui recrute le plus en Côte d'Ivoire actuellement ?

Est-ce que quelqu'un peut me partager son expérience dans ces domaines ? Les stages, les opportunités d'emploi, les difficultés rencontrées...

Merci d'avance pour vos conseils !",
                'tags' => ['orientation', 'informatique', 'génie-électrique', 'débouchés'],
            ],
            [
                'category' => 'Vie Étudiante',
                'title' => 'Logement étudiant à Abidjan : vos bons plans ?',
                'content' => "Hello les amis !

Je vais bientôt intégrer l'université à Abidjan et je cherche des conseils pour le logement. Mon budget est assez serré (environ 50 000 à 80 000 FCFA par mois).

Connaissez-vous des résidences étudiantes abordables ? Des quartiers sympas et pas trop chers ? Des collocations possibles ?

J'ai déjà regardé du côté de :
- Yopougon
- Abobo
- Cocody (mais c'est cher...)

Vos retours d'expérience seraient super utiles ! 🏠",
                'tags' => ['logement', 'abidjan', 'résidence', 'colocation', 'budget'],
            ],
            [
                'category' => 'Bourses et Financements',
                'title' => 'Bourses d\'excellence pour études à l\'étranger 2024',
                'content' => "Bonjour les futurs diplômés !

J'ai compilé une liste des principales bourses d'excellence disponibles pour les étudiants ivoiriens souhaitant poursuivre leurs études à l'étranger :

🇫🇷 **France**
- Bourses Eiffel (Master/Doctorat)
- Bourses d'excellence Major
- Campus France

🇨🇦 **Canada**
- Bourses d'exemption du Québec
- Bourses Vanier (Doctorat)

🇺🇸 **USA**
- Fulbright
- Bourses d'universités privées

🇩🇪 **Allemagne**
- DAAD
- Bourses Heinrich Böll

N'hésitez pas à me MP si vous voulez plus de détails sur l'une d'entre elles. Je peux aussi partager mon retour d'expérience sur le processus de candidature !

Bon courage à tous ! 💪",
                'tags' => ['bourses', 'étranger', 'excellence', 'master', 'doctorat'],
                'is_featured' => true,
            ],
            [
                'category' => 'Opportunités Professionnelles',
                'title' => 'Stage de fin d\'études chez Orange CI - Mon retour',
                'content' => "Salut la communauté !

Je reviens de 6 mois de stage chez Orange Côte d'Ivoire en tant qu'ingénieur réseau et je voulais partager mon expérience avec vous.

**Le processus de recrutement :**
- Candidature en ligne sur leur site
- Test technique (assez corsé !)
- 2 entretiens (RH + technique)
- Délai de réponse : 3 semaines

**L'expérience :**
✅ Très formateur techniquement
✅ Équipe accueillante et professionnelle
✅ Possibilité de stage pré-embauche
✅ Indemnité correcte (120 000 FCFA/mois)

❌ Rythme soutenu (pas toujours facile)
❌ Beaucoup de déplacements

**Conseil :** Préparez bien l'entretien technique, ils testent vraiment vos connaissances !

Des questions ? N'hésitez pas ! 😊",
                'tags' => ['stage', 'orange', 'télécom', 'retour-expérience', 'recrutement'],
            ],
            [
                'category' => 'Études à l\'Étranger',
                'title' => 'Étudier au Canada : démarches et réalité',
                'content' => "Bonjour tout le monde !

Après 2 ans d'études au Canada (Université de Montreal), je voulais partager quelques infos pratiques pour ceux qui s'y intéressent.

**Les démarches :**
1. Admission dans une université
2. CAQ (Certificat d'Acceptation du Québec)
3. Permis d'études
4. Visa (si nécessaire)

**Budget réaliste (par an) :**
- Frais de scolarité : 15 000 - 25 000 CAD
- Logement : 8 000 - 12 000 CAD
- Vie quotidienne : 8 000 - 10 000 CAD
- Total : ~30 000 - 45 000 CAD

**La réalité sur place :**
- L'hiver est VRAIMENT rigoureux ! ❄️
- Système d'éducation excellent
- Opportunités de travail étudiant
- Multiculturalisme enrichissant

**Astuce :** Commencez les démarches au moins 1 an à l'avance !

Posez vos questions, je serai ravi de vous aider ! 🇨🇦",
                'tags' => ['canada', 'immigration', 'université', 'budget', 'démarches'],
            ],
            [
                'category' => 'Aide aux Études',
                'title' => 'Méthodes de révision efficaces pour les partiels',
                'content' => "Hey les étudiants !

Avec les partiels qui approchent, je partage mes techniques de révision qui m'ont aidé à maintenir une bonne moyenne :

**1. La technique Pomodoro 🍅**
- 25 min de révision intense
- 5 min de pause
- Répéter 4 fois puis pause longue (15-30 min)

**2. Les mind maps 🧠**
- Visualiser les connections entre les concepts
- Plus efficace que la lecture linéaire
- Logiciels : XMind, MindMeister

**3. L'étude en groupe 👥**
- Expliquer = mieux retenir
- Questions-réponses entre amis
- Motivation collective

**4. Les fiches de révision 📚**
- Résumer l'essentiel sur une page
- Révision active vs passive

**5. Simulation d'examens ⏰**
- S'entraîner dans les conditions réelles
- Gérer son temps et son stress

Et vous, quelles sont vos techniques ? Partagez vos astuces ! 💡",
                'tags' => ['révision', 'examens', 'méthodes', 'étude', 'conseils'],
            ],
            [
                'category' => 'Projets Étudiants',
                'title' => 'Lancement d\'une association d\'entraide étudiante',
                'content' => "Salut la communauté !

Avec quelques amis, nous lançons une association d'entraide étudiante : **\"Étudiants Solidaires CI\"**.

**Notre mission :**
- Aide aux devoirs et révisions gratuites
- Orientation pour les nouveaux étudiants
- Collecte de matériel scolaire pour les plus démunis
- Organisation d'événements culturels et éducatifs

**Ce qu'on cherche :**
- Des tuteurs bénévoles (toutes matières)
- Des personnes motivées pour l'organisation
- Des partenariats avec d'autres associations
- Du matériel pédagogique (livres, ordinateurs...)

**Nos premiers événements :**
📅 15 mars : Journée d'orientation gratuite
📅 22 mars : Collecte de livres universitaires
📅 5 avril : Conférence \"Réussir ses études\"

Qui veut nous rejoindre ? Contactez-moi en MP ! 

Ensemble, nous pouvons faire la différence ! 💪✨",
                'tags' => ['association', 'entraide', 'bénévolat', 'solidarité', 'étudiants'],
            ],
            [
                'category' => 'Questions Générales',
                'title' => 'Équilibre études-travail : vos expériences ?',
                'content' => "Bonsoir tout le monde !

Je suis en 3ème année d'université et j'ai trouvé un job à temps partiel (20h/semaine) pour aider financièrement ma famille. Mais j'ai peur que ça impacte mes résultats.

Comment vous faites pour concilier travail et études ? Des conseils d'organisation ?

**Mes contraintes :**
- Cours du lundi au vendredi (8h-16h)
- Travail possible le soir et weekend
- Transport assez long (1h aller-retour)

**Mes interrogations :**
- Vaut-il mieux travailler tous les soirs un peu ou concentrer sur le weekend ?
- Comment rester efficace en cours quand on est fatigué ?
- Des jobs étudiants plus compatibles avec les études ?

Merci pour vos retours d'expérience ! 🙏",
                'tags' => ['travail-étudiant', 'organisation', 'temps-partiel', 'équilibre'],
            ],
        ];

        $this->command->info('Création des discussions du forum...');

        foreach ($threads as $threadData) {
            // Trouver la catégorie
            $category = $categories->where('name', $threadData['category'])->first();
            if (!$category) {
                continue;
            }

            // Créer la discussion
            $thread = ForumThread::create([
                'forum_category_id' => $category->id,
                'user_id' => $bacheliers->random()->id,
                'title' => $threadData['title'],
                'content' => $threadData['content'],
                'tags' => $threadData['tags'] ?? null,
                'slug' => Str::slug($threadData['title'] . '-' . time()),
                'is_pinned' => $threadData['is_pinned'] ?? false,
                'is_featured' => $threadData['is_featured'] ?? false,
                'is_locked' => false,
                'views_count' => rand(10, 250),
                'last_activity_at' => now()->subDays(rand(0, 7)),
            ]);

            // Ajouter des réponses à certaines discussions
            if (!in_array($threadData['category'], ['Annonces PEUB'])) {
                $this->addPostsToThread($thread, $bacheliers);
            }

            // Ajouter des réactions
            $this->addReactionsToThread($thread, $bacheliers);
        }

        $this->command->info('Forum peuplé avec succès !');
    }

    private function addPostsToThread(ForumThread $thread, $bacheliers): void
    {
        $postCount = rand(1, 8);
        $responses = $this->getResponsesForCategory($thread->category->name);

        for ($i = 0; $i < $postCount; $i++) {
            $post = ForumPost::create([
                'forum_thread_id' => $thread->id,
                'user_id' => $bacheliers->random()->id,
                'content' => $responses[array_rand($responses)],
                'created_at' => now()->subDays(rand(0, 6)),
            ]);

            // Parfois ajouter une réponse à un post
            if (rand(1, 4) == 1) {
                ForumPost::create([
                    'forum_thread_id' => $thread->id,
                    'user_id' => $bacheliers->random()->id,
                    'parent_id' => $post->id,
                    'content' => "Merci pour ton retour ! " . ["Très utile !", "Je suis d'accord avec toi.", "Intéressant comme point de vue.", "Bonne idée !"][rand(0, 3)],
                    'created_at' => now()->subDays(rand(0, 5)),
                ]);
            }

            // Ajouter des réactions aux posts
            $this->addReactionsToPost($post, $bacheliers);
        }
    }

    private function addReactionsToThread(ForumThread $thread, $bacheliers): void
    {
        $reactionCount = rand(5, 20);
        $reactionTypes = ['like', 'love', 'wow', 'angry'];

        for ($i = 0; $i < $reactionCount; $i++) {
            try {
                ForumReaction::create([
                    'user_id' => $bacheliers->random()->id,
                    'reactable_type' => ForumThread::class,
                    'reactable_id' => $thread->id,
                    'type' => $reactionTypes[array_rand($reactionTypes)],
                ]);
            } catch (\Exception $e) {
                // Ignore les doublons (contrainte unique)
            }
        }
    }

    private function addReactionsToPost(ForumPost $post, $bacheliers): void
    {
        $reactionCount = rand(0, 8);
        $reactionTypes = ['like', 'love', 'wow', 'angry'];

        for ($i = 0; $i < $reactionCount; $i++) {
            try {
                ForumReaction::create([
                    'user_id' => $bacheliers->random()->id,
                    'reactable_type' => ForumPost::class,
                    'reactable_id' => $post->id,
                    'type' => $reactionTypes[array_rand($reactionTypes)],
                ]);
            } catch (\Exception $e) {
                // Ignore les doublons (contrainte unique)
            }
        }
    }

    private function getResponsesForCategory(string $categoryName): array
    {
        $commonResponses = [
            "Merci pour ce partage d'expérience !",
            "C'est exactement ce que je cherchais comme information.",
            "Super utile, je garde ça de côté.",
            "Est-ce que tu peux donner plus de détails ?",
            "J'ai vécu la même chose, je confirme !",
            "Intéressant comme point de vue.",
            "Je n'y avais pas pensé, merci !",
        ];

        $specificResponses = match($categoryName) {
            'Orientation Universitaire' => [
                "J'ai fait informatique et je ne le regrette pas ! Le marché de l'emploi IT est très porteur en Côte d'Ivoire.",
                "Génie électrique offre aussi de belles perspectives, surtout avec les projets d'infrastructure du pays.",
                "As-tu pensé à faire des stages d'observation dans ces domaines ?",
                "Les deux filières se complètent bien d'ailleurs. Beaucoup d'électroniciens font de la programmation.",
                "Regarde aussi les débouchés dans les télécoms, ça recrute beaucoup !",
            ],
            'Vie Étudiante' => [
                "Pour le logement, je recommande vraiment les résidences universitaires si tu peux.",
                "Attention aux quartiers trop éloignés, le transport coûte cher au final.",
                "La colocation, c'est économique mais il faut bien choisir ses colocataires !",
                "N'oublie pas de vérifier la sécurité du quartier avant de t'installer.",
                "Les cités universitaires de Cocody sont bien mais vite remplies.",
            ],
            'Bourses et Financements' => [
                "Excellente compilation ! Peux-tu ajouter les critères d'éligibilité ?",
                "J'ai eu la bourse Eiffel, le processus est long mais ça vaut le coup !",
                "N'oubliez pas les bourses internes des universités, c'est souvent plus accessible.",
                "Commencez vraiment tôt les démarches, les dossiers sont très lourds.",
                "Il y a aussi les bourses de fondations privées, moins connues mais intéressantes.",
            ],
            'Opportunités Professionnelles' => [
                "Merci pour ce retour détaillé ! Orange recrute souvent, c'est bon à savoir.",
                "Le salaire de stage est correct par rapport au marché.",
                "As-tu eu des propositions d'embauche à la fin ?",
                "Les télécoms, c'est l'avenir ! Bravo pour cette expérience.",
                "Peux-tu partager quelques questions techniques qu'ils posent ?",
            ],
            'Études à l\'Étranger' => [
                "Merci pour ces infos pratiques ! Le budget est effectivement conséquent.",
                "L'hiver canadien, c'est un vrai défi pour nous Ivoiriens ! 😅",
                "Le système universitaire canadien est-il très différent du nôtre ?",
                "As-tu eu des difficultés avec la langue (français québécois) ?",
                "Les opportunités de travail après les études sont-elles bonnes ?",
            ],
            'Aide aux Études' => [
                "La technique Pomodoro, ça marche vraiment ! Je confirme.",
                "Moi j'utilise les mind maps aussi, c'est très efficace pour mémoriser.",
                "L'étude en groupe, il faut juste éviter que ça dérive en discussion ! 😄",
                "Merci pour ces conseils, juste ce qu'il faut avant les partiels !",
                "Tu as des applis à recommander pour les mind maps ?",
            ],
            'Projets Étudiants' => [
                "Super initiative ! Comment puis-je vous rejoindre ?",
                "J'aimerais bien aider pour l'orientation, j'ai de l'expérience.",
                "Vous avez pensé aux partenariats avec les entreprises ?",
                "Bravo pour cette démarche solidaire ! L'union fait la force.",
                "Je peux donner des cours de maths si vous voulez !",
            ],
            'Questions Générales' => [
                "C'est tout à fait possible de concilier ! Il faut juste bien s'organiser.",
                "Moi je travaille le weekend, ça me laisse la semaine pour les cours.",
                "Attention à ne pas trop te fatiguer, la santé avant tout !",
                "As-tu regardé les jobs sur le campus ? C'est souvent plus flexible.",
                "Le temps de transport, c'est vraiment à prendre en compte dans ton planning.",
            ],
            default => $commonResponses,
        };

        return array_merge($commonResponses, $specificResponses);
    }
}
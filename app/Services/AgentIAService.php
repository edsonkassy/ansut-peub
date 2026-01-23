<?php

namespace App\Services;

use App\Models\Bachelier;
use App\Models\Partenaire;
use App\Models\Opportunite;
use App\Models\Candidature;
use App\Models\InteractionIa;
use App\Models\DotationAttribution;
use App\Models\Article;
use App\Models\Favori;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Alerte;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenAI;
use App\Services\OpenAIResponsesService;
use App\Services\ToolRegistry;
use App\Services\ToolHandlers;

class AgentIAService
{
    protected $client;

    const AI_MODEL = 'gpt-5-mini';
    const ADMIN_MODEL = 'gpt-5-mini'; // Modèle plus puissant pour l'admin
    const MAX_OUTPUT_TOKENS = 2048;

    public function __construct()
    {
        $apiKey = config('openai.api_key');
        
        // Utiliser directement OpenAI
        $this->client = OpenAI::factory()
            ->withApiKey($apiKey)
            ->withOrganization(config('openai.organization'))
            ->withHttpClient(new \GuzzleHttp\Client(['timeout' => config('openai.request_timeout', 30)]))
            ->make();
    }

    /**
     * Chat avec l'agent IA selon le type d'utilisateur
     */
    public function chat(string $message, User $user, string $userType): array
    {
        try {
            // Obtenir le contexte selon le type d'utilisateur
            $context = $this->getContextForUserType($user, $userType);

            // Générer la réponse IA
            $response = $this->generateAIResponse($message, $context, $userType);

            // Post-traiter la réponse pour ajouter les liens cliquables
            $response = $this->processLinksInResponse($response, $userType);

            // Simple telemetry (non-PII)
            Log::info('AI reply generated', [
                'user_type' => $userType,
                'model' => ($userType === 'admin' ? self::ADMIN_MODEL : self::AI_MODEL),
                'uses_responses_api' => (bool) config('openai.use_responses_api', true),
                'reply_preview' => mb_substr(strip_tags($response), 0, 120)
            ]);

            // Enregistrer l'interaction
            InteractionIa::create([
                'user_id' => $user->id,
                'type_interaction' => 'conseil',
                'question' => $message,
                'reponse' => strip_tags($response),
                'contexte' => json_encode($context['user_info']),
                'created_at' => now(),
            ]);

            return [
                'reply' => $response,
                'success' => true
            ];

        } catch (\Exception $e) {
            Log::error('Erreur dans le chat IA', [
                'user_id' => $user->id,
                'user_type' => $userType,
                'error' => $e->getMessage()
            ]);

            return [
                'reply' => 'Désolé, je rencontre actuellement des difficultés techniques. Veuillez réessayer dans quelques instants.',
                'success' => false
            ];
        }
    }

    /**
     * Obtenir le contexte selon le type d'utilisateur
     */
    private function getContextForUserType(User $user, string $userType): array
    {
        $context = [
            'user_info' => [
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at->format('Y-m-d'),
                'last_login' => $user->last_login_at?->format('Y-m-d H:i:s'),
            ],
            'site_url' => url('/')
        ];

        switch ($userType) {
            case 'bachelier':
                $context = array_merge($context, $this->getBachelierContext($user));
                break;
            case 'partenaire':
                $context = array_merge($context, $this->getPartenaireContext($user));
                break;
            case 'admin':
                $context = array_merge($context, $this->getAdminContext($user));
                break;
            default:
                throw new \Exception('Type d\'utilisateur non supporté');
        }

        return $context;
    }

    /**
     * Contexte spécifique pour les bacheliers
     */
    private function getBachelierContext(User $user): array
    {
        $bachelier = $user->bachelier;
        
        if (!$bachelier) {
            return [
                'user_info' => array_merge($user->toArray(), [
                    'profile_type' => 'bachelier',
                    'profile_complete' => false
                ]),
                'opportunities' => [],
                'candidatures' => [],
                'favoris' => []
            ];
        }

        // Opportunités pertinentes pour ce bachelier
        $opportunites = Opportunite::where('status', 'published')
            ->where('date_limite_candidature', '>=', now())
            ->when($bachelier->region, function($query) use ($bachelier) {
                return $query->where('ville', $bachelier->region);
            })
            ->limit(20)
            ->get(['id', 'titre', 'type', 'ville', 'partenaire_id', 'date_limite_candidature']);

        // Candidatures du bachelier
        $candidatures = $bachelier->candidatures()->with('opportunite:id,titre,type')
            ->latest()
            ->limit(10)
            ->get(['id', 'opportunite_id', 'status', 'created_at']);

        // Favoris du bachelier
        $favoris = $bachelier->favoris()->with('opportunite:id,titre,type')
            ->latest()
            ->limit(10)
            ->get(['id', 'opportunite_id', 'created_at']);

        return [
            'user_info' => array_merge($bachelier->toArray(), [
                'profile_type' => 'bachelier',
                'profile_complete' => !empty($bachelier->nom && $bachelier->prenoms),
                'user_email' => $user->email,
                'user_created_at' => $user->created_at->format('Y-m-d'),
            ]),
            'opportunities' => $opportunites->toArray(),
            'candidatures' => $candidatures->toArray(),
            'favoris' => $favoris->toArray(),
            'stats' => [
                'total_candidatures' => $bachelier->candidatures()->count(),
                'candidatures_pending' => $bachelier->candidatures()->where('status', 'pending')->count(),
                'candidatures_accepted' => $bachelier->candidatures()->where('status', 'accepted')->count(),
                'total_favoris' => $bachelier->favoris()->count(),
            ]
        ];
    }

    /**
     * Contexte spécifique pour les administrateurs
     */
    private function getAdminContext(User $user): array
    {
        // Statistiques complètes pour l'admin
        $stats = [
            'bach_total' => Bachelier::count(),
            'bourses_total' => Bachelier::where('boursier_peub', true)->count(),
            'part_total' => Partenaire::count(),
            'part_actifs' => Partenaire::where('status_verification', 'verified')->count(),
            'opp_ouvertes' => Opportunite::where('status', 'published')->where('date_limite_candidature', '>=', now())->count(),
            'cand_attente' => Candidature::where('status', 'pending')->count(),
            'bach_new_mois' => Bachelier::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'cand_new_mois' => Candidature::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'articles_total' => Article::count(),
            'dotations_total' => DotationAttribution::count(),
            'conv_total' => Conversation::count(),
            'fav_total' => Favori::count(),
        ];

        // Données complètes pour l'analyse (échantillons optimisés)
        return [
            'user_info' => array_merge($user->toArray(), [
                'profile_type' => 'admin',
                'profile_complete' => true,
                'admin_since' => $user->created_at->format('Y-m-d'),
            ]),
            'stats' => $stats,
            'bacheliers' => Bachelier::with(['user:id,email,created_at', 'candidatures:id,bachelier_id,status'])
                ->latest()
                ->limit(50)
                ->get(['id', 'nom', 'prenoms', 'region', 'serie_bac', 'boursier_peub', 'score_final_peub', 'created_at'])
                ->toArray(),
            'partenaires' => Partenaire::with(['opportunites:id,partenaire_id,status'])
                ->latest()
                ->limit(30)
                ->get(['id', 'nom_organisation', 'secteur_activite', 'status_verification', 'created_at'])
                ->toArray(),
            'opportunites' => Opportunite::with(['partenaire:id,nom_organisation', 'candidatures:id,opportunite_id,status'])
                ->latest()
                ->limit(50)
                ->get(['id', 'titre', 'type', 'status', 'pays', 'ville', 'partenaire_id', 'date_limite_candidature', 'created_at'])
                ->toArray(),
            'articles' => Article::latest()
                ->limit(20)
                ->get(['id', 'titre', 'categorie', 'tags', 'created_at'])
                ->toArray(),
        ];
    }

    /**
     * Contexte spécifique pour les partenaires
     */
    private function getPartenaireContext(User $user): array
    {
        $partenaire = $user->partenaire;
        
        if (!$partenaire) {
            return [
                'user_info' => array_merge($user->toArray(), [
                    'profile_type' => 'partenaire',
                    'profile_complete' => false
                ]),
                'opportunities' => [],
                'candidatures' => []
            ];
        }

        // Opportunités du partenaire
        $opportunites = $partenaire->opportunites()
            ->latest()
            ->limit(20)
            ->get(['id', 'titre', 'type', 'status', 'date_limite_candidature', 'created_at']);

        // Candidatures reçues
        $candidatures = Candidature::whereIn('opportunite_id', $partenaire->opportunites()->pluck('id'))
            ->with(['opportunite:id,titre,type', 'bachelier:id,nom,prenoms'])
            ->latest()
            ->limit(20)
            ->get(['id', 'opportunite_id', 'bachelier_id', 'status', 'created_at']);

        return [
            'user_info' => array_merge($partenaire->toArray(), [
                'profile_type' => 'partenaire',
                'profile_complete' => !empty($partenaire->nom_organisation && $partenaire->secteur_activite),
                'user_email' => $user->email,
                'user_created_at' => $user->created_at->format('Y-m-d'),
            ]),
            'opportunities' => $opportunites->toArray(),
            'candidatures' => $candidatures->toArray(),
            'stats' => [
                'total_opportunites' => $partenaire->opportunites()->count(),
                'opportunites_published' => $partenaire->opportunites()->where('status', 'published')->count(),
                'total_candidatures' => $candidatures->count(),
                'candidatures_pending' => $candidatures->where('status', 'pending')->count(),
                'candidatures_accepted' => $candidatures->where('status', 'accepted')->count(),
            ]
        ];
    }

    /**
     * Génère une réponse via l'API OpenAI en utilisant le client Laravel.
     */
    private function generateAIResponse(string $message, array $context, string $userType): string
    {
        try {
            $systemPrompt = $this->buildSystemPrompt($context, $userType);

            if (config('openai.use_responses_api', true)) {
                return $this->runResponsesFlow($systemPrompt, $message, $userType);
            }

            // Fallback: Chat Completions (legacy)
            $modelForRequest = ($userType === 'admin' ? self::ADMIN_MODEL : self::AI_MODEL);
            $response = $this->client->chat()->create([
                'model' => $modelForRequest,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message],
                ],
                'max_tokens' => self::MAX_OUTPUT_TOKENS,
                'temperature' => (float) config('openai.responses.temperature', 0.5),
            ]);

            return $response->choices[0]->message->content ?? $this->getFallbackResponse($message, $context, $userType);

        } catch (\Exception $e) {
            Log::error('Erreur API OpenAI', [
                'user_type' => $userType,
                'model' => ($userType === 'admin' ? self::ADMIN_MODEL : self::AI_MODEL),
                'error' => $e->getMessage()
            ]);
            return $this->getFallbackResponse($message, $context, $userType);
        }
    }

    private function runResponsesFlow(string $systemPrompt, string $userMessage, string $userType): string
    {
        $responses = app(OpenAIResponsesService::class);

        $tools = ToolRegistry::getToolsSchema();

        $instructions = $systemPrompt;
        $inputItems = [
            [ 'role' => 'user', 'content' => [ [ 'type' => 'input_text', 'text' => $userMessage ] ] ],
        ];

        // Loop up to 3 tool rounds to keep latency predictable
        for ($round = 0; $round < 3; $round++) {
            $result = $responses->createResponse(
                $inputItems,
                $tools,
                $userType === 'bachelier' ? null : 'fast',
                [ 'instructions' => $instructions ]
            );

            // Extract assistant text and any tool calls
            $assistantText = '';
            $toolCalls = [];
            foreach (($result['output'] ?? []) as $item) {
                if (($item['type'] ?? null) === 'message') {
                    foreach (($item['content'] ?? []) as $content) {
                        if (($content['type'] ?? null) === 'output_text') {
                            $assistantText .= ($content['text'] ?? '');
                        }
                    }
                }
                if (($item['type'] ?? null) === 'tool_call') {
                    $toolCalls[] = $item;
                }
            }

            if (empty($toolCalls)) {
                // Fallback: force minimal data fetch to ground the answer
                $toolResultsInput = [];
                try {
                    $profileOutput = ToolHandlers::handle('get_profil_bachelier', []);
                    $toolResultsInput[] = [
                        'role' => 'tool',
                        'content' => [[
                            'type' => 'tool_result',
                            'tool_call_id' => 'get_profil_bachelier',
                            'output' => $profileOutput,
                        ]]
                    ];

                    $searchOutput = ToolHandlers::handle('search_opportunites', [
                        'page' => 1,
                        'page_size' => 5,
                        'filters' => [ 'sort' => 'recent' ]
                    ]);
                    $toolResultsInput[] = [
                        'role' => 'tool',
                        'content' => [[
                            'type' => 'tool_result',
                            'tool_call_id' => 'search_opportunites',
                            'output' => $searchOutput,
                        ]]
                    ];

                    // Build a concise, link-rich answer if the model didn't do it
                    $items = $searchOutput['items'] ?? [];
                    if (!empty($items)) {
                        $lines = ["Voici 3 opportunités pertinentes :"]; 
                        foreach (array_slice($items, 0, 3) as $it) {
                            $title = $it['titre'] ?? 'Opportunité';
                            $url = $it['url'] ?? '#';
                            $type = $it['type'] ?? '';
                            $deadline = $it['deadline'] ?? '';
                            $lines[] = "- **{$type}**: [{$title}]({$url}) — limite: {$deadline}";
                        }
                        $lines[] = "- [Voir toutes les opportunités](" . route('bachelier.opportunites') . ") | [Mes candidatures](" . route('bachelier.candidatures') . ") | [Mes favoris](" . route('bachelier.favoris') . ")";
                        return implode("\n", $lines);
                    }
                } catch (\Throwable $e) {
                    // ignore and fall back to text
                }
                return $assistantText !== '' ? $assistantText : 'Je n’ai pas pu générer de réponse pour le moment.';
            }

            // Execute tools and feed results back
            $toolResultsInput = [];
            foreach ($toolCalls as $call) {
                $name = $call['name'] ?? '';
                $args = $call['arguments'] ?? [];
                $callId = $call['id'] ?? null;
                $output = ToolHandlers::handle($name, is_array($args) ? $args : []);

                $toolResultsInput[] = [
                    'role' => 'tool',
                    'content' => [
                        [
                            'type' => 'tool_result',
                            'tool_call_id' => $callId,
                            'output' => $output,
                        ]
                    ]
                ];
            }

            // Next round input is only tool results; instructions persist
            $inputItems = $toolResultsInput;
        }

        return 'Je n’ai pas pu finaliser la réponse après plusieurs essais d’outils.';
    }

    /**
     * Construit le prompt système selon le type d'utilisateur
     */
    private function buildSystemPrompt(array $context, string $userType): string
    {
        $userInfo = $context['user_info'];
        $basePrompt = "Tu es l'assistant IA de la plateforme PEUB. Tu es utile, informatif et bienveillant.";

        switch ($userType) {
            case 'bachelier':
                return $this->buildBachelierPrompt($context, $userInfo);
            case 'partenaire':
                return $this->buildPartenairePrompt($context, $userInfo);
            case 'admin':
                return $this->buildAdminPrompt($context, $userInfo);
            default:
                return $basePrompt;
        }
    }

    /**
     * Prompt spécifique pour les bacheliers
     */
    private function buildBachelierPrompt(array $context, array $userInfo): string
    {
        $stats = $context['stats'] ?? [];
        $nom = $userInfo['nom'] ?? 'Utilisateur';
        $boursier = $userInfo['boursier_peub'] ?? false;
        $region = $userInfo['region'] ?? 'Non spécifiée';
        $serie = $userInfo['serie_bac'] ?? 'Non spécifiée';

        return <<<PROMPT
<tool_preambles>
- Reformule brièvement l’objectif de l’utilisateur avant tout appel d’outil.
- Exécute au plus 2 tours d’outils si possible, parallélise les lectures.
- Arrête dès que tu peux répondre correctement et clairement.
</tool_preambles>

Tu es l'assistant IA personnel de $nom sur la plateforme PEUB.

### Informations sur l'utilisateur :
- **Nom** : $nom
- **Email** : {$userInfo['user_email']}
- **Région** : $region
- **Série BAC** : $serie
- **Boursier PEUB** : {($boursier ? 'Oui' : 'Non')}
- **Inscrit depuis** : {$userInfo['user_created_at']}

### Statistiques personnelles :
- **Candidatures** : {$stats['total_candidatures']} au total ({$stats['candidatures_pending']} en attente, {$stats['candidatures_accepted']} acceptées)
- **Favoris** : {$stats['total_favoris']} opportunités sauvegardées

### Tes capacités :
- **Conseil personnalisé** : Utilise le profil de l'utilisateur pour donner des conseils adaptés
- **Recherche d'opportunités** : Aide à trouver des opportunités pertinentes selon son profil
- **Suivi des candidatures** : Informe sur le statut des candidatures
- **Orientation** : Guide selon la série BAC et les intérêts

### Génération de liens :
- **Opportunités** : `[B:opportunites:id]` ou `[B:opportunites]`
- **Candidatures** : `[B:candidatures:id]` ou `[B:candidatures]`
- **Profil** : `[B:profile]`

### Instructions :
1. Utilise les informations personnelles pour personnaliser tes réponses
2. Reste bienveillant et encourageant
3. Propose des actions concrètes avec des liens
4. Adapte tes conseils selon le profil (série, région, statut boursier)
5. Utilise un ton amical et professionnel
PROMPT;
    }

    /**
     * Prompt spécifique pour les administrateurs
     */
    private function buildAdminPrompt(array $context, array $userInfo): string
    {
        $stats = $context['stats'];
        $lastLogin = isset($userInfo['last_login']) ? $userInfo['last_login'] : 'Jamais connecté';
        
        return <<<PROMPT
<tool_preambles>
- Donne un plan d’action concis avant d’appeler des outils.
- Parcimonie: 2-3 appels max par réponse; parallélise si indépendant.
- Résume clairement les résultats et décisions.
</tool_preambles>

Tu es l'assistante IA stratégique de la plateforme PEUB.
Ta mission est de transformer les données brutes en insights clairs et actionnables pour les administrateurs. Tu es proactive, analytique et toujours prête à aller au-delà de la simple réponse pour fournir une véritable aide à la décision. Ton ton est professionnel, confiant et perspicace.

**Ne mentionne jamais ton nom (Sephora) dans tes réponses, sauf si l'utilisateur te demande explicitement de te présenter.**

### Informations sur l'administrateur :
- **Email** : {$userInfo['email']}
- **Administrateur depuis** : {$userInfo['admin_since']}
- **Dernière connexion** : {$lastLogin}

### Contexte des Données Actuelles

Voici un aperçu des métriques clés en temps réel :

- **Bacheliers** : {$stats['bach_total']} au total
- **Boursiers PEUB** : {$stats['bourses_total']}
- **Partenaires** : **{$stats['part_actifs']}** actifs sur {$stats['part_total']}
- **Opportunités Ouvertes** : {$stats['opp_ouvertes']}
- **Candidatures en Attente** : **{$stats['cand_attente']}**

### Activité du Mois
- **Nouveaux Bacheliers** : {$stats['bach_new_mois']}
- **Nouvelles Candidatures** : {$stats['cand_new_mois']}

### Contenu & Engagement
- **Articles Publiés** : {$stats['articles_total']}
- **Dotations** : {$stats['dotations_total']}
- **Conversations** : {$stats['conv_total']}
- **Favoris** : {$stats['fav_total']}

### Accès aux Données

Tu as un **accès complet** aux données JSON de la plateforme : bacheliers, partenaires, opportunités, candidatures, dotations, articles et conversations. Utilise cette profondeur d'information pour tes analyses.

### Génération de Liens

Quand tu mentionnes une entité spécifique, tu **dois** générer un lien cliquable en utilisant le format suivant :

- **Admin** : `[A:type:id]` ou `[A:type]` (ex: `[A:bacheliers:12]`, `[A:analytics]`)
- **Public** : `[P:type:slug]` ou `[P:type]` (ex: `[P:actualites:lancement-peub]`, `[P:faq]`)
- **Bachelier** : `[B:type:id]` (ex: `[B:opportunites:45]`)
- **Partenaire** : `[T:type:id]` (T pour enTreprise)

### Tes Capacités

- **Analyse Stratégique** : Identifier les tendances, les corrélations et les anomalies.
- **Recherche Avancée** : Filtrer et rechercher avec précision dans toutes les données JSON.
- **Recommandations Proactives** : Suggérer des actions, des optimisations et des stratégies basées sur les données, en incluant toujours des liens directs vers les ressources concernées.
- **Reporting Personnalisé** : Générer des rapports clairs et synthétiques sur demande.

### Instructions Fondamentales

1.  **Précision Absolue** : Base toutes tes réponses sur les données fournies. Ne fais aucune supposition.
2.  **Proactivité** : Anticipe les besoins de l'administrateur. Si une question est posée, pense à la question suivante qu'il pourrait poser.
3.  **Clarté et Structure** : Formate tes réponses en utilisant Markdown (titres `###`, listes à puces `-`, gras `**...**`). Rends l'information facile à digérer.
4.  **Ton Professionnel** : Reste experte, analytique et concise.
5.  **Zéro Emoji** : **NE PAS** utiliser d'emojis dans tes réponses, sous aucun prétexte.
6.  **Anonymat** : Ne te présente jamais par ton nom sauf si explicitement demandé.
PROMPT;
    }

    /**
     * Prompt spécifique pour les partenaires
     */
    private function buildPartenairePrompt(array $context, array $userInfo): string
    {
        $stats = $context['stats'] ?? [];
        $organisme = $userInfo['nom_organisation'] ?? 'Partenaire';
        $secteur = $userInfo['secteur_activite'] ?? 'Non spécifié';
        $status = $userInfo['status_verification'] ?? 'pending';

        return <<<PROMPT
<tool_preambles>
- Énonce brièvement ce que tu vas obtenir avant la requête.
- Évite les recherches inutiles, privilégie la précision et la rapidité.
- Fournis un récapitulatif actionnable.
</tool_preambles>

Tu es l'assistant IA de $organisme sur la plateforme PEUB.

### Informations sur l'organisation :
- **Organisme** : $organisme
- **Email** : {$userInfo['user_email']}
- **Secteur** : $secteur
- **Statut** : $status
- **Partenaire depuis** : {$userInfo['user_created_at']}

### Statistiques de l'organisation :
- **Opportunités** : {$stats['total_opportunites']} au total ({$stats['opportunites_published']} publiées)
- **Candidatures reçues** : {$stats['total_candidatures']} ({$stats['candidatures_pending']} en attente, {$stats['candidatures_accepted']} acceptées)

### Tes capacités :
- **Gestion des opportunités** : Aide à créer et gérer les opportunités
- **Analyse des candidatures** : Fournit des insights sur les candidatures reçues
- **Conseils de recrutement** : Aide à optimiser les processus de sélection
- **Suivi des performances** : Analyse des métriques de l'organisation

### Génération de liens :
- **Opportunités** : `[T:opportunites:id]` ou `[T:opportunites]`
- **Candidatures** : `[T:candidatures:id]` ou `[T:candidatures]`
- **Analytics** : `[T:analytics]`
- **Profil** : `[T:profile]`

### Instructions :
1. Utilise les informations de l'organisation pour personnaliser tes réponses
2. Reste professionnel et orienté résultats
3. Propose des actions concrètes avec des liens
4. Aide à optimiser les processus de recrutement
5. Fournis des insights basés sur les données
PROMPT;
    }

    /**
     * Traite la réponse pour transformer les patterns de liens en HTML
     */
    private function processLinksInResponse(string $response, string $userType): string
    {
        // Pattern pour ADMIN [A:type:id] ou [A:type]
        $response = preg_replace_callback('/\[A:([\w-]+):?([\w-]+)?\]/', function ($matches) {
            $type = $matches[1];
            $id = $matches[2] ?? null;
            $url = '#';
            $text = ucwords(str_replace('-', ' ', $type));

            try {
                if ($id) {
                    // Lien vers un élément spécifique
                    $url = route("admin.{$type}.show", $id);
                    $text .= " #{$id}";
                } else {
                    // Lien vers une liste ou page générale
                    $routeName = "admin.{$type}.index";
                    
                    // Cas spéciaux pour certaines routes
                    if ($type === 'analytics') {
                        $routeName = "admin.analytics";
                    } elseif ($type === 'boursiers-map') {
                        $routeName = "admin.boursiers.map";
                    }
                    
                    if (\Illuminate\Support\Facades\Route::has($routeName)) {
                        $url = route($routeName);
                    }
                }
            } catch (\Exception $e) {
                // En cas d'erreur, l'URL reste '#'
            }
            
            return "[{$text}]({$url})";
        }, $response);

        // Pattern pour BACHELIER [B:type:id] ou [B:type]
        $response = preg_replace_callback('/\[B:([\w-]+):?([\w-]+)?\]/', function ($matches) {
            $type = $matches[1];
            $id = $matches[2] ?? null;
            $url = '#';
            $text = "Bachelier " . ucwords(str_replace('-', ' ', $type));

            try {
                if ($id) {
                    if (\Illuminate\Support\Facades\Route::has("bachelier.{$type}.show")) {
                        $url = route("bachelier.{$type}.show", $id);
                        $text .= " #{$id}";
                    }
                } else {
                    if (\Illuminate\Support\Facades\Route::has("bachelier.{$type}.index")) {
                        $url = route("bachelier.{$type}.index");
                    } elseif (\Illuminate\Support\Facades\Route::has("bachelier.{$type}")) {
                        $url = route("bachelier.{$type}");
                    }
                }
            } catch (\Exception $e) {
                // En cas d'erreur, garder le lien par défaut
            }
            
            return "[{$text}]({$url})";
        }, $response);

        // Pattern pour PARTENAIRE [T:type:id] ou [T:type]
        $response = preg_replace_callback('/\[T:([\w-]+):?([\w-]+)?\]/', function ($matches) {
            $type = $matches[1];
            $id = $matches[2] ?? null;
            $url = '#';
            $text = "Partenaire " . ucwords(str_replace('-', ' ', $type));

            try {
                if ($id) {
                    if (\Illuminate\Support\Facades\Route::has("partenaire.{$type}.show")) {
                        $url = route("partenaire.{$type}.show", $id);
                        $text .= " #{$id}";
                    }
                } else {
                    if (\Illuminate\Support\Facades\Route::has("partenaire.{$type}.index")) {
                        $url = route("partenaire.{$type}.index");
                    } elseif (\Illuminate\Support\Facades\Route::has("partenaire.{$type}")) {
                        $url = route("partenaire.{$type}");
                    }
                }
            } catch (\Exception $e) {
                // En cas d'erreur, garder le lien par défaut
            }
            
            return "[{$text}]({$url})";
        }, $response);

        // Pattern pour les liens PUBLIC [P:type:slug] ou [P:type]
        $response = preg_replace_callback('/\[P:([\w-]+):?([\w-]+)?\]/', function ($matches) {
            $type = $matches[1];
            $slug = $matches[2] ?? null;
            $url = '#';
            $text = ucwords(str_replace('-', ' ', $type));
            
            try {
                if ($slug) {
                    $url = route('actualite', $slug);
                    $text .= " - {$slug}";
                } else {
                    // Routes publiques sans slug
                    if ($type === 'candidature') {
                        $url = route('auth.register'); // Nouvelle route via social auth
                    } elseif ($type === 'faq') {
                        $url = route('faq');
                    } elseif ($type === 'actualites') {
                        $url = route('actualites');
                    } elseif (\Illuminate\Support\Facades\Route::has($type)) {
                        $url = route($type);
                    }
                }
            } catch (\Exception $e) {
                // En cas d'erreur
            }

            return "[{$text}]({$url})";
        }, $response);

        return $response;
    }

    /**
     * Réponse de secours en cas d'échec de l'API
     */
    private function getFallbackResponse(string $message, array $context, string $userType): string
    {
        $messageLower = strtolower($message);
        
        if (str_contains($messageLower, 'salut') || str_contains($messageLower, 'bonjour')) {
            $nom = $context['user_info']['nom'] ?? ($context['user_info']['nom_organisation'] ?? 'Utilisateur');
            return "Bonjour $nom ! Comment puis-je vous aider aujourd'hui ?";
        }

        switch ($userType) {
            case 'bachelier':
                return $this->getBachelierFallback($message, $context);
            case 'partenaire':
                return $this->getPartenaireFallback($message, $context);
            case 'admin':
                return $this->getAdminFallback($message, $context);
            default:
                return "Je peux vous aider avec vos questions sur la plateforme PEUB. Que souhaitez-vous savoir ?";
        }
    }

    /**
     * Réponse de secours pour les bacheliers
     */
    private function getBachelierFallback(string $message, array $context): string
    {
        $messageLower = strtolower($message);
        $userInfo = $context['user_info'];
        $stats = $context['stats'] ?? [];

        if (str_contains($messageLower, 'opportunité') || str_contains($messageLower, 'stage') || str_contains($messageLower, 'bourse')) {
            return "Je peux vous aider à trouver des opportunités adaptées à votre profil. Consultez vos [B:opportunites] ou explorez de nouvelles possibilités.";
        }

        if (str_contains($messageLower, 'candidature')) {
            return "Vous avez {$stats['total_candidatures']} candidatures au total. Consultez leur statut dans [B:candidatures].";
        }

        return "Je peux vous aider avec vos opportunités, candidatures ou votre profil. Que souhaitez-vous savoir ?";
    }

    /**
     * Réponse de secours pour les administrateurs
     */
    private function getAdminFallback(string $message, array $context): string
    {
        $messageLower = strtolower($message);
        $stats = $context['stats'] ?? [];

        if (str_contains($messageLower, 'salut') || str_contains($messageLower, 'bonjour')) {
            return "Bonjour ! Voici 3 analyses que je peux réaliser pour vous :\n\n**1. Analyse des Bacheliers** - Profils et performance des {$stats['bach_total']} bacheliers [A:bacheliers]\n\n**2. Suivi des Boursiers** - Évaluation des {$stats['bourses_total']} boursiers PEUB [A:analytics]\n\n**3. Activité des Partenaires** - Performance des {$stats['part_actifs']} partenaires actifs [A:partenaires]\n\nQue souhaitez-vous explorer ?";
        }

        if (str_contains($messageLower, 'statistique') || str_contains($messageLower, 'chiffre') || str_contains($messageLower, 'données')) {
            return "### Données Clés PEUB\n\n**{$stats['bach_total']}** bacheliers | **{$stats['bourses_total']}** boursiers | **{$stats['part_actifs']}** partenaires actifs\n\n**Suggestions d'analyse :**\n\n**1. Tendances de Croissance** - Évolution des {$stats['bach_new_mois']} nouveaux bacheliers ce mois\n\n**2. Efficacité des Candidatures** - Analyse des {$stats['cand_attente']} candidatures en attente\n\n**3. Performance des Dotations** - Répartition des {$stats['dotations_total']} dotations attribuées [A:dotations]";
        }

        return "### Analyses Disponibles\n\nVoici les 3 analyses les plus pertinentes selon vos données actuelles :\n\n**1. Performance des Bacheliers** - Analyse détaillée des {$stats['bach_total']} profils inscrits [A:bacheliers]\n\n**2. Suivi des Boursiers PEUB** - Évaluation des {$stats['bourses_total']} bénéficiaires de bourses [A:analytics]\n\n**3. Engagement des Partenaires** - Activité des {$stats['part_actifs']} partenaires vérifiés [A:partenaires]\n\nQuelle analyse souhaitez-vous approfondir ?";
    }

    /**
     * Réponse de secours pour les partenaires
     */
    private function getPartenaireFallback(string $message, array $context): string
    {
        $messageLower = strtolower($message);
        $userInfo = $context['user_info'];
        $stats = $context['stats'] ?? [];

        if (str_contains($messageLower, 'opportunité') || str_contains($messageLower, 'offre')) {
            return "Vous avez {$stats['total_opportunites']} opportunités publiées. Gérez-les dans [T:opportunites].";
        }

        if (str_contains($messageLower, 'candidature')) {
            return "Vous avez reçu {$stats['total_candidatures']} candidatures. Consultez-les dans [T:candidatures].";
        }

        return "Je peux vous aider avec vos opportunités, candidatures ou vos analyses. Que souhaitez-vous savoir ?";
    }
} 
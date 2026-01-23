<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AiExtractionService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openai.com/v1';
    
    // Modèles recommandés pour 2025
    private const VISION_MODEL = 'gpt-4o-mini'; 
    private const TEXT_MODEL = 'gpt-4o-mini';   // Modèle pour l'analyse de texte
    private const REASONING_MODEL = 'gpt-4o-mini';   // Modèle plus avancé pour le raisonnement complexe

    public function __construct()
    {
        $this->apiKey = config('openai.api_key');
        
        if (empty($this->apiKey)) {
            throw new \Exception('OpenAI API key not configured');
        }
    }

    /**
     * Extrait les données structurées d'une pièce d'identité
     */
    public function extractIdentityData(string $filePath): array
    {
        try {
            $imageData = $this->encodeImage($filePath);
            
            $prompt = $this->getIdentityExtractionPrompt();
            
            $response = $this->makeVisionRequest($prompt, $imageData);
            
            return $this->parseExtractionResponse($response);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'extraction des données d\'identité', [
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'extracted_data' => null
            ];
        }
    }

    /**
     * Extrait les données structurées d'une collante du BAC
     */
    public function extractBacData(string $filePath): array
    {
        try {
            $imageData = $this->encodeImage($filePath);
            
            $prompt = $this->getBacExtractionPrompt();
            
            $response = $this->makeVisionRequest($prompt, $imageData);
            
            return $this->parseExtractionResponse($response);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'extraction des données du BAC', [
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'extracted_data' => null
            ];
        }
    }

    /**
     * Analyse et score les motivations du candidat
     */
    public function analyzeMotivation(string $motivation, array $context = []): array
    {
        try {
            $prompt = $this->getMotivationAnalysisPrompt();
            
            $response = $this->makeTextRequest($prompt, $motivation);
            
            return $this->parseMotivationResponse($response);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'analyse des motivations', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'score' => null,
                'analysis' => null
            ];
        }
    }

    /**
     * Extrait et analyse toutes les données d'un candidat
     */
    public function processBachelierData(array $data): array
    {
        $results = [
            'identity_extraction' => null,
            'bac_extraction' => null,
            'motivation_analysis' => null,
            'overall_success' => false
        ];

        // Extraction des données d'identité
        if (!empty($data['piece_identite_file'])) {
            $results['identity_extraction'] = $this->extractIdentityData($data['piece_identite_file']);
        }

        // Extraction des données du BAC
        if (!empty($data['collante_bac_file'])) {
            $results['bac_extraction'] = $this->extractBacData($data['collante_bac_file']);
        }

        // Analyse des motivations
        if (!empty($data['motivation'])) {
            $context = [
                'region' => $data['region'] ?? null,
                'serie_bac' => $data['serie_bac'] ?? null,
                'note_bac' => $data['note_bac'] ?? null,
                'mention' => $data['mention'] ?? null,
                'situations_particulieres' => $data['situations_particulieres'] ?? []
            ];
            
            $results['motivation_analysis'] = $this->analyzeMotivation($data['motivation'], $context);
        }

        // Déterminer le succès global
        $results['overall_success'] = 
            ($results['identity_extraction']['success'] ?? false) ||
            ($results['bac_extraction']['success'] ?? false) ||
            ($results['motivation_analysis']['success'] ?? false);

        return $results;
    }

    /**
     * Valide qu'un document est bien du type attendu
     */
    public function validateDocument(string $filePath, string $expectedType): array
    {
        try {
            $imageData = $this->encodeImage($filePath);
            
            $prompt = $this->getDocumentValidationPrompt($expectedType);
            
            $response = $this->makeVisionRequest($prompt, $imageData);
            
            $result = $this->parseValidationResponse($response);
            
            Log::info('Validation de document IA', [
                'file' => $filePath,
                'expected_type' => $expectedType,
                'is_valid' => $result['is_valid'] ?? false,
                'document_type_detected' => $result['document_type'] ?? 'unknown'
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation du document', [
                'file' => $filePath,
                'expected_type' => $expectedType,
                'error' => $e->getMessage()
            ]);
            
            return [
                'is_valid' => false,
                'confidence' => 0,
                'document_type' => 'unknown',
                'reason' => $e->getMessage(),
                'error' => true
            ];
        }
    }

    /**
     * Encode une image en base64
     */
    private function encodeImage(string $filePath): string
    {
        if (!Storage::disk('public')->exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $imageContent = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        
        return 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
    }

    /**
     * Fait une requête à l'API Vision d'OpenAI
     */
    private function makeVisionRequest(string $prompt, string $imageData): array
    {
        $payload = [
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => ['url' => $imageData]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.2,
            'response_format' => ['type' => 'json_object']
        ];

        return $this->makeApiRequest($payload, self::VISION_MODEL);
    }

    /**
     * Fait une requête à l'API Text d'OpenAI
     */
    private function makeTextRequest(string $prompt, string $inputText): array
    {
        $payload = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $prompt
                ],
                [
                    'role' => 'user',
                    'content' => $inputText
                ]
            ],
            'max_tokens' => 3000,
            'temperature' => 0.3,
            'response_format' => ['type' => 'json_object']
        ];

        return $this->makeApiRequest($payload, self::TEXT_MODEL);
    }

    private function makeApiRequest(array $payload, string $model): array
    {
        $apiType = config('openai.api_type', 'openai');
        
        // Ajouter le modèle au payload pour l'API OpenAI standard
        $payload['model'] = $model;
        
        if ($apiType === 'azure') {
            // Configuration Azure OpenAI
        $azureResource = config('openai.azure_resource');
        $apiVersion = config('openai.api_version');
            $deploymentName = env('AZURE_OPENAI_DEPLOYMENT_NAME', $model);
            
            if (empty($azureResource)) {
                throw new \Exception('Azure OpenAI resource name not configured');
            }
            
        $url = "https://{$azureResource}.openai.azure.com/openai/deployments/{$deploymentName}/chat/completions?api-version={$apiVersion}";

            $headers = [
            'api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
            ];
        } else {
            // Configuration OpenAI standard
            $url = $this->baseUrl . '/chat/completions';
            
            $headers = [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ];
        }

        Log::info('Requête API OpenAI', [
            'api_type' => $apiType,
            'url' => $url,
            'model' => $model
        ]);

        $response = Http::withHeaders($headers)
            ->timeout(config('openai.request_timeout', 30))
            ->post($url, $payload);

        if (!$response->successful()) {
            Log::error('Erreur API OpenAI', [
                'api_type' => $apiType,
                'url' => $url,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Prompt pour l'extraction des données d'identité
     */
    private function getIdentityExtractionPrompt(): string
    {
        return <<<PROMPT
        Analyse cette pièce d'identité et extrait les informations suivantes au format JSON :
        
        {
            "nom": "Nom de famille",
            "prenoms": "Prénoms",
            "date_naissance": "YYYY-MM-DD",
            "lieu_naissance": "Lieu de naissance",
            "sexe": "M ou F",
            "numero_piece": "Numéro de la pièce",
            "date_delivrance": "YYYY-MM-DD",
            "date_expiration": "YYYY-MM-DD",
            "autorite_delivrance": "Autorité de délivrance",
            "confiance_extraction": 0.95,
            "notes": "Observations ou difficultés rencontrées"
        }
        
        Règles importantes :
        - Retourne UNIQUEMENT du JSON valide
        - Si une information n'est pas visible, utilise null
        - Le score de confiance doit être entre 0 et 1
        - Les dates doivent être au format YYYY-MM-DD
        - Respecte strictement la structure JSON demandée
        PROMPT;
    }

    /**
     * Prompt pour l'extraction des données du BAC
     */
    private function getBacExtractionPrompt(): string
    {
        return <<<PROMPT
        Analyse cette collante du BAC et extrait les informations suivantes au format JSON :
        
        {
            "matricule": "Numéro de matricule",
            "nom_candidat": "Nom complet du candidat",
            "serie": "Série du BAC (A1, A2, B, C, D, E, F1, F2, F3, F4, G1, G2)",
            "note_totale": 0.00,
            "mention": "passable, assez_bien, bien, tres_bien",
            "annee": 2024,
            "centre_examen": "Centre d'examen",
            "confiance_extraction": 0.95,
            "notes": "Observations ou difficultés rencontrées"
        }
        
        Règles importantes :
        - Retourne UNIQUEMENT du JSON valide
        - Si une information n'est pas visible, utilise null
        - Le score de confiance doit être entre 0 et 1
        - La note doit être un nombre décimal
        - Respecte strictement la structure JSON demandée
        PROMPT;
    }

    /**
     * Prompt pour la validation d'un document
     */
    private function getDocumentValidationPrompt(string $expectedType): string
    {
        $typeDescriptions = [
            'piece_identite' => 'une pièce d\'identité ivoirienne (CNI, Carte Scolaire, Attestation d\'identité)',
            'collante_bac' => 'une collante du Baccalauréat ivoirien (relevé de notes avec mention, série, et note sur 400)',
        ];
        
        $description = $typeDescriptions[$expectedType] ?? 'un document officiel';
        
        return <<<PROMPT
        Analyse cette image et détermine si c'est bien {$description}.
        
        Retourne un JSON avec cette structure exacte :
        {
            "is_valid": true ou false,
            "confidence": 0.95,
            "document_type": "cni" ou "carte_scolaire" ou "collante_bac" ou "autre",
            "reason": "Explication claire de la décision"
        }
        
        Critères de validation selon le type attendu :
        
        Pour "piece_identite" :
        - Doit contenir : nom, prénoms, date de naissance, photo
        - Types acceptés : CNI (Carte Nationale d'Identité), Carte Scolaire, Attestation
        - Doit être un document officiel ivoirien
        
        Pour "collante_bac" :
        - Doit contenir : matricule, série (A1, A2, C, D, E, F, G), notes par matière
        - Doit afficher une note totale (généralement sur 400 points pour le système ivoirien)
        - Doit mentionner l'année du BAC
        - Peut contenir une mention (Passable, Assez Bien, Bien, Très Bien)
        
        Rejette si :
        - L'image est floue ou illisible
        - Ce n'est pas le bon type de document
        - Le document est manifestement faux ou altéré
        - Il manque des informations essentielles
        
        IMPORTANT : Retourne UNIQUEMENT du JSON valide, sans texte avant ou après.
        PROMPT;
    }

    /**
     * Parse la réponse de validation
     */
    private function parseValidationResponse(array $response): array
    {
        try {
            $content = $response['choices'][0]['message']['content'] ?? '';
            $data = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from AI');
            }
            
            return [
                'is_valid' => $data['is_valid'] ?? false,
                'confidence' => $data['confidence'] ?? 0,
                'document_type' => $data['document_type'] ?? 'unknown',
                'reason' => $data['reason'] ?? 'Aucune raison fournie',
                'error' => false
            ];
            
        } catch (\Exception $e) {
            Log::error('Erreur de parsing de validation', [
                'error' => $e->getMessage(),
                'response' => $response
            ]);
            
            return [
                'is_valid' => false,
                'confidence' => 0,
                'document_type' => 'unknown',
                'reason' => 'Erreur lors de l\'analyse du document',
                'error' => true
            ];
        }
    }

    /**
     * Prompt pour l'analyse des motivations
     */
    private function getMotivationAnalysisPrompt(): string
    {
        return <<<PROMPT
        Analyse la motivation de candidature au programme PEUB et évalue les aspects suivants.
        Le contexte du candidat et sa motivation te seront fournis en entrée.
        
        Retourne une analyse au format JSON :
        
        {
            "score_global": 8.5,
            "analyse_detaillee": {
                "clarte_objectifs": {
                    "score": 8.0,
                    "commentaire": "Les objectifs sont clairement définis"
                },
                "motivation_intrinseque": {
                    "score": 9.0,
                    "commentaire": "Forte motivation personnelle"
                },
                "realisme_projet": {
                    "score": 7.5,
                    "commentaire": "Projet réaliste et bien pensé"
                },
                "impact_social": {
                    "score": 8.5,
                    "commentaire": "Bonne conscience de l'impact social"
                },
                "coherence_parcours": {
                    "score": 8.0,
                    "commentaire": "Cohérence avec le parcours académique"
                }
            },
            "forces": ["Clarté des objectifs", "Motivation personnelle forte"],
            "axes_amelioration": ["Précision du plan d'action"],
            "recommandation": "Candidature solide avec une motivation authentique",
            "confiance_evaluation": 0.9
        }
        
        Critères d'évaluation :
        - Clarté des objectifs (0-10)
        - Motivation intrinsèque (0-10)
        - Réalisme du projet (0-10)
        - Impact social potentiel (0-10)
        - Cohérence avec le parcours (0-10)
        
        Le score global est la moyenne pondérée des scores détaillés.
        PROMPT;
    }

    /**
     * Parse la réponse d'extraction
     */
    private function parseExtractionResponse(array $response): array
    {
        return $this->parseApiResponse($response, self::VISION_MODEL);
    }

    /**
     * Parse la réponse d'analyse des motivations
     */
    private function parseMotivationResponse(array $response): array
    {
        $parsed = $this->parseApiResponse($response, self::TEXT_MODEL);

        if (!$parsed['success']) {
            return $parsed;
        }

        $analysis = $parsed['extracted_data'];

        return [
            'success' => true,
            'score' => $analysis['score_global'] ?? 0,
            'analysis' => $analysis,
            'model_used' => $parsed['model_used'],
            'tokens_used' => $parsed['tokens_used']
        ];
    }

    private function parseApiResponse(array $response, string $modelUsed): array
    {
        try {
            if (!empty($response['error'])) {
                $errorMessage = is_array($response['error']) ? json_encode($response['error']) : $response['error'];
                throw new \Exception('API returned an error: ' . $errorMessage);
            }

            // Format de réponse standard Azure OpenAI
            $content = $response['choices'][0]['message']['content'] ?? null;
            if ($content === null) {
                throw new \Exception('Could not find message content in response.');
            }

            $extractedData = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response: ' . json_last_error_msg());
            }

            return [
                'success' => true,
                'extracted_data' => $extractedData,
                'model_used' => $modelUsed,
                'tokens_used' => $response['usage']['total_tokens'] ?? 0
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to parse response: ' . $e->getMessage(),
                'raw_response' => $response
            ];
        }
    }
}

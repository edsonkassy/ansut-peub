<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageGenerationService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openai.com/v1';
    private int $timeout = 120; // 2 minutes timeout
    private int $connectTimeout = 30; // 30 seconds connect timeout
    private int $retries = 3; // Number of retries

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        
        if (empty($this->apiKey)) {
            throw new \Exception('OpenAI API key not configured');
        }
    }

    /**
     * Génère une illustration pour une opportunité
     */
    public function generateOpportunityIllustration(string $title, string $type, ?string $description = null): array
    {
        Log::info('Début de la génération d\'image', compact('title', 'type', 'description'));

        try {
            $prompt = $this->generatePrompt($title, $type, $description);
            
            Log::info('Génération d\'image avec DALL-E-3', compact('prompt', 'title'));

            // Utiliser directement l'API OpenAI
            $imageUrl = "{$this->baseUrl}/images/generations";

            $client = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout($this->timeout)
              ->connectTimeout($this->connectTimeout)
              ->retry($this->retries, 1000); // Retry 3 times with 1 second delay

            $response = $client->post($imageUrl, [
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1792x1024',
                'quality' => 'standard',
                'response_format' => 'b64_json'
            ]);

            if (!$response->successful()) {
                throw new \Exception('API request failed: ' . $response->body());
            }

            $data = $response->json();
            
            if (empty($data['data'][0]['b64_json'])) {
                throw new \Exception('No image data in response');
            }

            // Decode the base64 image
            $imageContent = base64_decode($data['data'][0]['b64_json']);
            
            // Generate unique filename
            $filename = 'opportunites/' . uniqid('opp_') . '.png';
            
            // Store the image
            Storage::disk('public')->put($filename, $imageContent);

            Log::info('Image générée avec succès', ['path' => $filename]);

            return [
                'success' => true,
                'path' => $filename
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération de l\'image avec GPT-image-1: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Impossible de générer l\'image avec GPT-image-1'
            ];
        }
    }

    /**
     * Génère le prompt pour l'image
     */
    private function generatePrompt(string $title, string $type, ?string $description = null): string
    {
        $descriptionText = $description ?: 'Opportunité de type ' . $type;
        
        // Mapping des types d'opportunités vers des éléments visuels spécifiques
        $typeVisuals = [
            'bourse' => 'graduation cap, academic building, books, scholarship symbols, student success',
            'stage' => 'briefcase, office environment, professional development, mentorship, workplace learning',
            'emploi' => 'career growth, professional success, team collaboration, workplace achievement',
            'formation' => 'learning process, skill development, training modules, educational advancement',
            'concours' => 'competition, achievement, awards, excellence, competitive spirit',
            'event' => 'networking, community gathering, professional event, collaboration',
            'promotion' => 'opportunity, growth, advancement, promotional success'
        ];
        
        $visualElements = $typeVisuals[$type] ?? 'professional opportunity, growth, success';
        
        return "Create a modern, minimalist vector illustration for an opportunity banner in 1792x1024 landscape format. " .
               "STYLE: Clean, flat design with geometric shapes, smooth gradients, and modern vector aesthetics. " .
               "Use a consistent design language with rounded corners, subtle shadows, and professional color palette. " .
               "COLOR SCHEME: Primary ANSUT brand colors - Blue (#0066B3), Orange (#FF6B00), Green (#00B050). " .
               "Use these colors harmoniously with white space and subtle neutral tones (light grays, soft whites). " .
               "COMPOSITION: Wide horizontal layout with balanced visual hierarchy. " .
               "Main focal point should represent the opportunity type with supporting elements. " .
               "Use clean lines, geometric forms, and modern iconography. " .
               "CONTENT: Visual representation of '{$title}' - {$type} opportunity. " .
               "Include elements that represent: {$visualElements}. " .
               "Create a cohesive scene that conveys professionalism, opportunity, and growth. " .
               "TECHNICAL REQUIREMENTS: " .
               "- Pure vector illustration style with clean, crisp edges " .
               "- No photographic elements, only geometric and abstract shapes " .
               "- Minimalist approach with plenty of white space " .
               "- Consistent line weights and geometric precision " .
               "- Modern flat design with subtle depth through layering " .
               "RESTRICTIONS: " .
               "- ABSOLUTELY NO TEXT, LETTERS, NUMBERS, OR TYPOGRAPHY " .
               "- No realistic photos or complex textures " .
               "- No busy or cluttered compositions " .
               "- Focus on clean, modern vector aesthetics " .
               "RESULT: A professional, modern vector illustration that represents the opportunity type " .
               "with consistent ANSUT branding and clean, minimalist design principles.";
    }
}
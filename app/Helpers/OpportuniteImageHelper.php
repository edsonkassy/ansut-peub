<?php

namespace App\Helpers;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OpportuniteImageHelper
{
    // Couleurs de l'ANSUT
    private const ANSUT_COLORS = [
        'primary' => '#0066B3',    // Bleu ANSUT
        'secondary' => '#FF6B00',  // Orange ANSUT
        'accent' => '#00B050'      // Vert ANSUT
    ];

    // Configuration par défaut
    private const TARGET_WIDTH = 1200;
    private const TARGET_HEIGHT = 630;

    /**
     * Génère une description pour l'image basée sur les détails de l'opportunité
     */
    private static function generateImagePrompt(array $data): string
    {
        $type = $data['type'];
        $titre = $data['titre'];
        $description = $data['description'] ?? '';

        // Styles spécifiques selon le type d'opportunité
        $stylePrompts = [
            'formation' => "Educational and professional development theme with modern classroom or training setting",
            'stage' => "Professional workplace environment showing mentorship and learning",
            'emploi' => "Corporate and professional setting with modern office elements",
            'bourse' => "Academic achievement and financial support imagery",
            'concours' => "Competitive and achievement-focused imagery",
            'event' => "Dynamic event and conference style",
            'promotion' => "Dynamic marketing and promotional style"
        ];

        $stylePrompt = $stylePrompts[$type] ?? "Professional and modern business style";
        
        // Construction du prompt final optimisé pour GPT-image-1
        $prompt = "Create a professional opportunity banner illustration in 1536x1024 landscape format (3:2 aspect ratio). ";
        $prompt .= "OPPORTUNITY CONTEXT: This is an opportunity titled '{$titre}' of type '{$type}'. ";
        
        if (!empty($description)) {
            $contentSummary = self::summarizeContent($description);
            $prompt .= "Description: {$contentSummary}. ";
        }

        $prompt .= "VISUAL STYLE: {$stylePrompt}. ";
        $prompt .= "Use the ANSUT brand colors: blue (#0066B3), orange (#FF6B00) and green (#00B050). ";
        
        // Spécifications techniques pour GPT-image-1
        $prompt .= "DESIGN REQUIREMENTS: Modern vector illustration with flat design aesthetics, clean and minimalist approach. ";
        $prompt .= "Professional color palette with harmonious tones suitable for opportunity posts. ";
        $prompt .= "Create visual metaphors that represent the specific opportunity type and goals. ";
        
        // Restrictions sur le texte
        $prompt .= "CRITICAL RESTRICTIONS: ";
        $prompt .= "- ABSOLUTELY NO TEXT, NO TYPOGRAPHY, NO LETTERS, NO WORDS, NO NUMBERS in the image ";
        $prompt .= "- Pure visual communication through relevant icons, symbols, and illustrations only ";
        $prompt .= "- The illustration should be immediately recognizable as related to this specific opportunity ";
        
        // Composition
        $prompt .= "COMPOSITION: Wide horizontal layout optimized for opportunity thumbnails with balanced visual hierarchy and appropriate white space.";
        
        return $prompt;
    }

    /**
     * Summarize content to extract key themes for visual representation
     */
    private static function summarizeContent(string $content): string
    {
        // Nettoyer et limiter la longueur
        $content = strip_tags($content);
        $content = preg_replace('/\s+/', ' ', $content);
        
        // Prendre les premières phrases les plus informatives
        $sentences = preg_split('/[.!?]+/', $content);
        $summary = '';
        $wordCount = 0;
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (empty($sentence)) continue;
            
            $words = str_word_count($sentence);
            if ($wordCount + $words <= 50) { // Limiter à ~50 mots
                $summary .= $sentence . '. ';
                $wordCount += $words;
            } else {
                break;
            }
        }
        
        return trim($summary);
    }

    /**
     * Génère une image pour une opportunité avec GPT-image-1
     */
    private static function generateImageWithOpenAI(array $data): ?string
    {
        try {
            $prompt = self::generateImagePrompt($data);

            Log::info('Génération image avec DALL-E-3', [
                'prompt' => $prompt,
                'opportunity_title' => $data['titre'] ?? 'N/A'
            ]);

            // Appel à l'API OpenAI avec DALL-E-3 (modèle standard pour Azure)
            $response = OpenAI::images()->create([
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1792x1024',  // Taille supportée par DALL-E-3
                'quality' => 'standard',  // Qualité standard
                'response_format' => 'b64_json'  // Format base64 pour Azure
            ]);

            if (empty($response->data)) {
                Log::error('Erreur de génération d\'image: Pas de données retournées');
                return null;
            }

            // DALL-E-3 retourne les images en base64 avec response_format=b64_json
            if (!isset($response->data[0]->b64_json)) {
                Log::error('Image base64 non trouvée dans la réponse DALL-E-3');
                return null;
            }

            $base64Image = $response->data[0]->b64_json;
            
            // Sauvegarder l'image depuis base64
            $filename = self::saveBase64Image($base64Image, $data);

            // Redimensionner l'image avec Intervention Image
            $manager = new ImageManager(new Driver());
            $image = $manager->read(storage_path('app/public/' . $filename));
            $image->resize(self::TARGET_WIDTH, self::TARGET_HEIGHT);
            $image->save(storage_path('app/public/' . $filename), 85);

            return $filename;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération de l\'image avec GPT-image-1: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Save image from base64 and save to storage
     */
    private static function saveBase64Image(string $base64Image, array $data): string
    {
        try {
            // Décoder l'image base64
            $imageContent = base64_decode($base64Image);
            
            if ($imageContent === false) {
                throw new \Exception('Erreur lors du décodage de l\'image base64');
            }
            
            // Générer un nom de fichier unique
            $filename = 'opportunites/' . Str::uuid() . '.png';
            
            // Sauvegarder dans le storage public
            if (!Storage::put('public/' . $filename, $imageContent)) {
                throw new \Exception('Erreur de sauvegarde de l\'image');
            }
            
            return $filename;
            
        } catch (\Exception $e) {
            Log::error('Erreur sauvegarde image base64', [
                'error' => $e->getMessage(),
                'opportunity_title' => $data['titre'] ?? 'N/A'
            ]);
            throw $e;
        }
    }

    /**
     * Génère une image pour une opportunité (méthode publique pour le contrôleur)
     */
    public static function generateOpportunityImage(array $data): array
    {
        try {
            // Vérifier si OpenAI est configuré
            if (!config('openai.api_key')) {
                return [
                    'success' => false,
                    'error' => 'Configuration OpenAI manquante. Veuillez configurer votre clé API OpenAI.'
                ];
            }

            Log::info('Génération d\'image avec DALL-E-3');
            $imagePath = self::generateImageWithOpenAI($data);
            
            if (!$imagePath) {
                return [
                    'success' => false,
                    'error' => 'Impossible de générer l\'image avec DALL-E-3'
                ];
            }

            return [
                'success' => true,
                'image_path' => $imagePath,
                'full_url' => asset('storage/' . $imagePath),
                'model_used' => 'dall-e-3',
                'message' => 'Image générée avec succès par DALL-E-3'
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération de l\'image d\'opportunité: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Une erreur est survenue lors de la génération de l\'image: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Supprime une image d'opportunité
     */
    public static function deleteImage(?string $filename): bool
    {
        if (!$filename) {
            return true;
        }

        try {
            return Storage::delete('public/' . $filename);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'image: ' . $e->getMessage());
            return false;
        }
    }
} 
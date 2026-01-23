<?php

namespace App\Http\Controllers;

use App\Helpers\OpportuniteImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ImageGenerationController extends Controller
{
    public function generateOpportunityImage(Request $request)
    {
        try {
            Log::info('Début de la génération d\'image', $request->all());
            
            // Valider les données requises
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|in:formation,stage,emploi,bourse,concours,event,promotion',
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                Log::error('Validation échouée', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Vérifier la configuration OpenAI
            if (!config('openai.api_key')) {
                Log::error('Clé API OpenAI manquante');
                return response()->json([
                    'success' => false,
                    'error' => 'Configuration OpenAI manquante'
                ], 500);
            }

            Log::info('Appel du helper pour générer l\'image');
            
            // Générer l'image
            $result = OpportuniteImageHelper::generateOpportunityImage($request->all());

            Log::info('Résultat de la génération', $result);

            if (!$result['success']) {
                return response()->json($result, 500);
            }

            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans generateOpportunityImage: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }
} 
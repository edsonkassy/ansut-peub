#!/usr/bin/env php
<?php

/**
 * Script de test de configuration OpenAI
 * 
 * Usage: php test-openai.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   TEST DE CONFIGURATION OPENAI API                        ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// 1. Vérifier la présence de la clé API
echo "📋 Vérification de la configuration...\n\n";

$apiKey = config('openai.api_key');
$apiType = config('openai.api_type', 'openai');
$azureResource = config('openai.azure_resource');
$timeout = config('openai.request_timeout', 30);

echo "  ✓ Type d'API : " . ($apiType ?: '❌ NON DÉFINI') . "\n";

if (empty($apiKey)) {
    echo "  ❌ Clé API : NON DÉFINIE\n\n";
    echo "⚠️  ERREUR : La clé API OpenAI n'est pas configurée !\n\n";
    echo "➡️  Ajoutez dans votre fichier .env :\n";
    echo "    OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n\n";
    echo "    Obtenez votre clé sur : https://platform.openai.com/api-keys\n\n";
    exit(1);
}

echo "  ✓ Clé API : " . substr($apiKey, 0, 10) . "..." . substr($apiKey, -4) . "\n";
echo "  ✓ Timeout : {$timeout}s\n\n";

if ($apiType === 'azure') {
    echo "  ℹ️  Mode : Azure OpenAI\n";
    if (empty($azureResource)) {
        echo "  ❌ Ressource Azure : NON DÉFINIE\n\n";
        echo "⚠️  ERREUR : La ressource Azure OpenAI n'est pas configurée !\n\n";
        echo "➡️  Ajoutez dans votre fichier .env :\n";
        echo "    AZURE_OPENAI_RESOURCE=votre-resource-name\n\n";
        exit(1);
    }
    echo "  ✓ Ressource Azure : {$azureResource}\n\n";
} else {
    echo "  ℹ️  Mode : OpenAI Standard (api.openai.com)\n\n";
}

// 2. Test de connexion avec un simple prompt
echo "🔌 Test de connexion à l'API...\n\n";

try {
    $service = app(\App\Services\AiExtractionService::class);
    
    // Créer une image de test simple (1x1 pixel transparent PNG en base64)
    $testImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
    
    echo "  → Envoi d'une requête de test...\n";
    
    // Créer un fichier temporaire pour le test
    $tempFile = 'temp/test-openai-' . time() . '.png';
    Storage::disk('public')->put($tempFile, base64_decode(substr($testImage, strpos($testImage, ',') + 1)));
    
    // Tester la validation de document
    $result = $service->validateDocument($tempFile, 'piece_identite');
    
    // Nettoyer
    Storage::disk('public')->delete($tempFile);
    
    if (isset($result['error']) && $result['error'] === true) {
        echo "  ❌ Erreur lors de la requête\n";
        echo "  Raison : " . ($result['reason'] ?? 'Inconnue') . "\n\n";
        echo "⚠️  La connexion à l'API OpenAI a échoué.\n\n";
        echo "➡️  Vérifiez :\n";
        echo "    1. Que votre clé API est valide\n";
        echo "    2. Que vous avez des crédits sur votre compte OpenAI\n";
        echo "    3. Votre connexion internet\n\n";
        exit(1);
    }
    
    echo "  ✅ Connexion réussie !\n\n";
    
} catch (\Exception $e) {
    echo "  ❌ Exception : " . $e->getMessage() . "\n\n";
    
    if (str_contains($e->getMessage(), 'Could not resolve host')) {
        echo "⚠️  Erreur DNS : Impossible de résoudre l'hôte OpenAI\n\n";
        echo "➡️  Solutions :\n";
        echo "    1. Vérifiez votre connexion internet\n";
        echo "    2. Si vous utilisez Azure, vérifiez AZURE_OPENAI_RESOURCE dans .env\n";
        echo "    3. Si vous utilisez OpenAI standard, assurez-vous que OPENAI_API_TYPE=openai\n\n";
    } elseif (str_contains($e->getMessage(), 'Unauthorized') || str_contains($e->getMessage(), '401')) {
        echo "⚠️  Erreur d'authentification : Clé API invalide\n\n";
        echo "➡️  Solutions :\n";
        echo "    1. Vérifiez que votre clé commence bien par 'sk-proj-'\n";
        echo "    2. Générez une nouvelle clé sur https://platform.openai.com/api-keys\n\n";
    } elseif (str_contains($e->getMessage(), '429')) {
        echo "⚠️  Quota dépassé : Vous avez atteint votre limite\n\n";
        echo "➡️  Solutions :\n";
        echo "    1. Vérifiez votre usage sur https://platform.openai.com/usage\n";
        echo "    2. Ajoutez des crédits à votre compte OpenAI\n\n";
    } else {
        echo "⚠️  Erreur inattendue\n\n";
    }
    
    exit(1);
}

// 3. Résumé
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   ✅ CONFIGURATION OPENAI VALIDE                          ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";
echo "Votre configuration OpenAI est correcte et fonctionnelle !\n";
echo "\n";
echo "Modèles utilisés :\n";
echo "  • Vision (documents) : gpt-4o-mini\n";
echo "  • Text (motivation)  : gpt-4o-mini\n";
echo "\n";
echo "Prêt à analyser des candidatures ! 🚀\n";
echo "\n";

exit(0);


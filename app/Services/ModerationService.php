<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ModerationService
{
    /**
     * Quick prefilter using lightweight heuristics for obvious violations.
     */
    private function prefilter(string $text): ?string
    {
        $t = mb_strtolower($text);

        // Very short / low-signal
        if (mb_strlen(trim($t)) < 3) {
            return 'low_signal';
        }

        // Spam / ads indicators
        $spamHints = [
            'whatsapp', 'telegram', 'snapchat', 't.me/', 'wa.me/', 'wapp',
            'promo', 'code promo', 'remise', 'réduction', 'gagner de l\'argent',
            'mp pour', 'dm pour', 'contactez-moi', 'prix', 'vente', 'vends', 'acheter', 'achetez',
        ];
        foreach ($spamHints as $hint) {
            if (str_contains($t, $hint)) {
                return 'spam_or_ads';
            }
        }

        // URLs / phone numbers (basic)
        if (preg_match('/https?:\\/\\/|www\\.|\.com\b|\.net\b|\.io\b|\.fr\b/i', $text)) {
            return 'possible_ads_or_links';
        }
        if (preg_match('/(?:\+?\d[\s.-]?){8,}/', $text)) {
            return 'contact_sharing';
        }

        // Obvious hate/insult keywords (non-exhaustive, French)
        $insults = ['con\b', 'connard', 'connasse', 'fdp', 'enculé', 'pute', 'salope', 'merde'];
        foreach ($insults as $w) {
            if (preg_match('/\b' . $w . '/iu', $t)) {
                return 'harassment_or_insult';
            }
        }

        return null; // no obvious violation
    }

    /**
     * Moderate text via OpenAI Responses API with gpt-5-nano (fast path).
     */
    public function allowText(string $text): array
    {
        $heuristic = $this->prefilter($text);
        if ($heuristic !== null) {
            return [ 'allowed' => false, 'category' => $heuristic, 'source' => 'heuristic' ];
        }

        try {
            $responses = app(OpenAIResponsesService::class);

            $instructions = <<<SYS
Tu es un filtre de modération ultra-rapide pour une communauté étudiante francophone.
Décide si un message est acceptable.

Catégories à BLOQUER:
- spam / publicité (vente de produits/services, promos, liens externes commerciaux)
- harcèlement / insultes / propos dégradants
- violence / incitation à la violence
- contenu sexuel explicite ou suggestif
- contenu choquant / haineux / discriminatoire
- partage de contacts/coordonnées pour démarchage (numéros, WhatsApp, Telegram)
- "dumb content" sans valeur (flood, suites de caractères, très faible signal)

Réponds UNIQUEMENT en JSON compact: {"allowed":true|false, "category":"label"}
Ne mets aucun texte autour.
SYS;

            $result = $responses->createResponse(
                [ [ 'role' => 'user', 'content' => [ [ 'type' => 'input_text', 'text' => $text ] ] ] ],
                [],
                'fast',
                [ 'instructions' => $instructions ]
            );

            // Try to extract JSON from output
            $jsonStr = '';
            foreach (($result['output'] ?? []) as $item) {
                if (($item['type'] ?? null) === 'message') {
                    foreach (($item['content'] ?? []) as $c) {
                        if (($c['type'] ?? null) === 'output_text') {
                            $jsonStr .= $c['text'] ?? '';
                        }
                    }
                }
            }

            $parsed = json_decode(trim($jsonStr), true);
            if (is_array($parsed) && array_key_exists('allowed', $parsed)) {
                return [
                    'allowed' => (bool) ($parsed['allowed'] ?? true),
                    'category' => (string) ($parsed['category'] ?? ''),
                    'source' => 'gpt-5-nano'
                ];
            }

            // If parsing fails, default to allow to avoid false positives
            return [ 'allowed' => true, 'category' => 'unknown', 'source' => 'parse_fallback' ];
        } catch (\Throwable $e) {
            Log::warning('ModerationService allowText error', [ 'error' => $e->getMessage() ]);
            // On error, rely on heuristics already done (allow here)
            return [ 'allowed' => true, 'category' => 'error', 'source' => 'exception' ];
        }
    }
}



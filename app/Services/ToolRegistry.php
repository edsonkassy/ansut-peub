<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;

class ToolRegistry
{
    public static function getToolsSchema(): array
    {
        return [
            self::searchOpportunites(),
            self::listCandidatures(),
            self::listBibliotheques(),
            self::listForumThreads(),
            self::getInbox(),
            self::getNotifications(),
            self::getProfilBachelier(),
            self::getParcoursUniversitaire(),
        ];
    }

    private static function searchOpportunites(): array
    {
        return [
            'type' => 'function',
            'name' => 'search_opportunites',
            'description' => 'Recherche d’opportunités (read-only) pour un bachelier.',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'q' => ['type' => 'string'],
                    'filters' => [
                        'type' => 'object',
                        'properties' => [
                            'type' => ['type' => 'string', 'description' => 'bourse|stage|emploi|formation|concours|event|promotion'],
                            'location' => ['type' => 'string', 'description' => 'ville ou "Toutes les régions"'],
                            'region' => ['type' => 'string', 'description' => 'région du bachelier'],
                            'serie_bac' => ['type' => 'string'],
                            'sort' => ['type' => 'string', 'description' => 'recent|deadline|score'],
                        ]
                    ],
                    'page' => ['type' => 'integer', 'minimum' => 1],
                    'page_size' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
                ],
                'required' => ['page', 'page_size']
            ],
        ];
    }

    private static function listCandidatures(): array
    {
        return [
            'type' => 'function',
            'name' => 'list_candidatures',
            'description' => 'Liste des candidatures du bachelier (read-only).',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer', 'minimum' => 1],
                    'page_size' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
                    'status' => ['type' => 'string']
                ],
                'required' => ['page', 'page_size']
            ],
        ];
    }

    private static function listBibliotheques(): array
    {
        return [
            'type' => 'function',
            'name' => 'list_bibliotheques',
            'description' => 'Liste des ressources de la bibliothèque (read-only).',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'q' => ['type' => 'string'],
                    'page' => ['type' => 'integer', 'minimum' => 1],
                    'page_size' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
                ],
                'required' => ['page', 'page_size']
            ],
        ];
    }

    private static function listForumThreads(): array
    {
        return [
            'type' => 'function',
            'name' => 'list_forum_threads',
            'description' => 'Liste des discussions du forum (read-only).',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'category' => ['type' => 'string'],
                    'page' => ['type' => 'integer', 'minimum' => 1],
                    'page_size' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
                ],
                'required' => ['page', 'page_size']
            ],
        ];
    }

    private static function getInbox(): array
    {
        return [
            'type' => 'function',
            'name' => 'get_inbox',
            'description' => 'Récupère la boîte de réception du bachelier (read-only).',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer', 'minimum' => 1],
                    'page_size' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
                    'since' => ['type' => 'string']
                ],
                'required' => ['page', 'page_size']
            ],
        ];
    }

    private static function getNotifications(): array
    {
        return [
            'type' => 'function',
            'name' => 'get_notifications',
            'description' => 'Récupère les notifications de l’utilisateur (read-only).',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'page' => ['type' => 'integer', 'minimum' => 1],
                    'page_size' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
                    'since' => ['type' => 'string']
                ],
                'required' => ['page', 'page_size']
            ],
        ];
    }

    private static function getProfilBachelier(): array
    {
        return [
            'type' => 'function',
            'name' => 'get_profil_bachelier',
            'description' => 'Récupère le profil bachelier courant (read-only).',
            'parameters' => [
                'type' => 'object',
                'properties' => (object)[],
                'required' => []
            ],
        ];
    }

    private static function getParcoursUniversitaire(): array
    {
        return [
            'type' => 'function',
            'name' => 'get_parcours_universitaire',
            'description' => 'Récupère le parcours universitaire du bachelier (read-only).',
            'parameters' => [
                'type' => 'object',
                'properties' => (object)[],
                'required' => []
            ],
        ];
    }
}



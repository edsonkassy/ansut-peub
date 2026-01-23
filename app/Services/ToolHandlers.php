<?php

namespace App\Services;

use App\Models\Bachelier;
use App\Models\Candidature;
use App\Models\LibraryResource;
use App\Models\ForumThread;
use App\Models\SystemNotification;
use App\Models\ParcoursUniversitaire;
use App\Models\Opportunite;
use Illuminate\Support\Facades\Auth;

class ToolHandlers
{
    public static function handle(string $name, array $args = []): array
    {
        return match ($name) {
            'search_opportunites' => self::searchOpportunites($args),
            'list_candidatures' => self::listCandidatures($args),
            'list_bibliotheques' => self::listBibliotheques($args),
            'list_forum_threads' => self::listForumThreads($args),
            'get_inbox' => self::getInbox($args),
            'get_notifications' => self::getNotifications($args),
            'get_profil_bachelier' => self::getProfilBachelier(),
            'get_parcours_universitaire' => self::getParcoursUniversitaire(),
            default => ['error' => ['code' => 'unknown_tool', 'message' => 'Unknown tool']],
        };
    }

    private static function paginate($query, int $page, int $pageSize): array
    {
        $page = max(1, $page);
        $pageSize = max(1, min(100, $pageSize));
        $total = $query->count();
        $items = $query->forPage($page, $pageSize)->get();
        return [
            'items' => $items,
            'page' => $page,
            'page_size' => $pageSize,
            'total' => $total,
            'has_more' => ($page * $pageSize) < $total,
        ];
    }

    private static function searchOpportunites(array $args): array
    {
        $q = $args['q'] ?? null;
        $page = (int) ($args['page'] ?? 1);
        $pageSize = (int) ($args['page_size'] ?? 10);
        $filters = is_array($args['filters'] ?? null) ? $args['filters'] : [];

        $user = Auth::user();
        $bachelier = $user?->bachelier;

        $query = Opportunite::query()->where('status', 'published')
            ->where('date_limite_candidature', '>=', now());

        if ($q) {
            $query->where(function ($inner) use ($q) {
                $inner->where('titre', 'like', "%$q%")
                      ->orWhere('type', 'like', "%$q%")
                      ->orWhere('ville', 'like', "%$q%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (!empty($filters['location'])) {
            if ($filters['location'] !== 'Toutes les régions') {
                $query->where('ville', $filters['location']);
            }
        }
        // Personnalisation par défaut
        if ($bachelier) {
            if (!empty($filters['region'])) {
                $query->orWhereJsonContains('regions_ciblees', $filters['region']);
            } elseif (!empty($bachelier->region)) {
                $query->orWhereJsonContains('regions_ciblees', $bachelier->region);
            }

            if (!empty($filters['serie_bac'])) {
                $query->orWhereJsonContains('series_acceptees', $filters['serie_bac']);
            } elseif (!empty($bachelier->serie_bac)) {
                $query->orWhereJsonContains('series_acceptees', $bachelier->serie_bac);
            }
        }

        // Tri
        $sort = $filters['sort'] ?? 'recent';
        if ($sort === 'deadline') {
            $query->orderBy('date_limite_candidature');
        } else {
            $query->latest();
        }

        $items = $query->select(['id','titre','type','ville','partenaire_id','date_limite_candidature'])->forPage($page, $pageSize)->get();

        // Ajout de liens cliquables et score simple (baseline)
        $items = $items->map(function ($o) use ($bachelier) {
            $score = 0;
            if ($bachelier) {
                if ($bachelier->region && $o->ville && strcasecmp($bachelier->region, $o->ville) === 0) {
                    $score += 10;
                }
            }
            return [
                'id' => $o->id,
                'titre' => $o->titre,
                'type' => $o->type,
                'ville' => $o->ville,
                'deadline' => optional($o->date_limite_candidature)->format('Y-m-d'),
                'score' => $score,
                'url' => route('bachelier.opportunites.show', $o->id),
            ];
        });

        return [
            'items' => $items,
            'page' => $page,
            'page_size' => $pageSize,
            'total' => $query->count(),
            'has_more' => ($page * $pageSize) < $query->count(),
        ];
    }

    private static function listCandidatures(array $args): array
    {
        $user = Auth::user();
        $bachelier = $user?->bachelier;
        if (!$bachelier) {
            return ['items' => [], 'page' => 1, 'page_size' => 0, 'total' => 0, 'has_more' => false];
        }
        $status = $args['status'] ?? null;
        $page = (int) ($args['page'] ?? 1);
        $pageSize = (int) ($args['page_size'] ?? 20);

        $query = $bachelier->candidatures()->with('opportunite:id,titre,type');
        if ($status) {
            $query->where('status', $status);
        }
        return self::paginate($query->latest()->select(['id','opportunite_id','status','created_at']), $page, $pageSize);
    }

    private static function listBibliotheques(array $args): array
    {
        if (!class_exists(LibraryResource::class)) {
            return ['items' => [], 'page' => 1, 'page_size' => 0, 'total' => 0, 'has_more' => false];
        }
        $q = $args['q'] ?? null;
        $page = (int) ($args['page'] ?? 1);
        $pageSize = (int) ($args['page_size'] ?? 20);
        $query = LibraryResource::query();
        if ($q) {
            $query->where('title', 'like', "%$q%")
                  ->orWhere('description', 'like', "%$q%");
        }
        return self::paginate($query->latest()->select(['id','title','category','created_at']), $page, $pageSize);
    }

    private static function listForumThreads(array $args): array
    {
        if (!class_exists(ForumThread::class)) {
            return ['items' => [], 'page' => 1, 'page_size' => 0, 'total' => 0, 'has_more' => false];
        }
        $category = $args['category'] ?? null;
        $page = (int) ($args['page'] ?? 1);
        $pageSize = (int) ($args['page_size'] ?? 20);
        $query = ForumThread::query();
        if ($category) {
            $query->where('forum_category_id', $category);
        }
        return self::paginate($query->latest()->select(['id','title','forum_category_id','created_at']), $page, $pageSize);
    }

    private static function getInbox(array $args): array
    {
        $user = Auth::user();
        $page = (int) ($args['page'] ?? 1);
        $pageSize = (int) ($args['page_size'] ?? 20);
        $query = $user?->messages()->with('sender:id,name')->latest();
        if (!$query) {
            return ['items' => [], 'page' => 1, 'page_size' => 0, 'total' => 0, 'has_more' => false];
        }
        return self::paginate($query->select(['id','sender_id','subject','created_at']), $page, $pageSize);
    }

    private static function getNotifications(array $args): array
    {
        $page = (int) ($args['page'] ?? 1);
        $pageSize = (int) ($args['page_size'] ?? 20);
        $query = SystemNotification::query()->latest();
        return self::paginate($query->select(['id','title','type','created_at']), $page, $pageSize);
    }

    private static function getProfilBachelier(): array
    {
        $user = Auth::user();
        $bachelier = $user?->bachelier;
        return [
            'profile' => $bachelier ? $bachelier->only(['id','nom','prenoms','region','serie_bac','boursier_peub']) : null,
        ];
    }

    private static function getParcoursUniversitaire(): array
    {
        $user = Auth::user();
        $bachelier = $user?->bachelier;
        if (!$bachelier || !class_exists(ParcoursUniversitaire::class)) {
            return ['items' => []];
        }
        $items = ParcoursUniversitaire::where('bachelier_id', $bachelier->id)
            ->latest()
            ->get(['id','etablissement','filiere','annee_debut','annee_fin']);
        return ['items' => $items];
    }
}



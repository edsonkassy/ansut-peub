<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Opportunite;
use App\Models\Bachelier;

class OpportuniteController extends Controller
{
    /**
     * Liste des opportunités disponibles
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Opportunite::where('status', 'published')
            ->where(function($q) {
                $q->whereNull('date_limite_candidature')
                  ->orWhere('date_limite_candidature', '>=', now());
            })
            ->with(['partenaire.user', 'types']);

        // Filtres
        if ($request->has('type')) {
            $query->whereHas('types', function($q) use ($request) {
                $q->where('opportunite_types.id', $request->type);
            });
        }

        if ($request->has('region')) {
            $query->where(function($q) use ($request) {
                $q->whereNull('regions_ciblees')
                  ->orWhereJsonContains('regions_ciblees', $request->region);
            });
        }

        if ($request->has('serie')) {
            $query->where(function($q) use ($request) {
                $q->whereNull('series_acceptees')
                  ->orWhereJsonContains('series_acceptees', $request->serie);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $opportunites = $query->paginate($perPage);

        // Ajouter le score de compatibilité si l'utilisateur est un bachelier
        if ($request->user() && $request->user()->role === 'bachelier') {
            $bachelier = $request->user()->bachelier;
            $opportunites->getCollection()->transform(function ($opportunite) use ($bachelier) {
                $opportunite->compatibility_score = $bachelier->getMatchingScore($opportunite);
                $opportunite->can_apply = $bachelier->canApplyToOpportunity($opportunite);
                $opportunite->has_applied = $bachelier->candidatures()
                    ->where('opportunite_id', $opportunite->id)
                    ->exists();
                return $opportunite;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $opportunites
        ], 200);
    }

    /**
     * Détails d'une opportunité
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $opportunite = Opportunite::with([
            'partenaire.user',
            'types',
            'candidatures' => function($q) {
                $q->whereIn('status', ['accepted', 'pending'])->count();
            }
        ])->find($id);

        if (!$opportunite) {
            return response()->json([
                'success' => false,
                'message' => 'Opportunité non trouvée'
            ], 404);
        }

        if ($opportunite->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Cette opportunité n\'est pas disponible'
            ], 403);
        }

        // Informations supplémentaires si l'utilisateur est un bachelier
        $additionalData = [];
        if ($request->user() && $request->user()->role === 'bachelier') {
            $bachelier = $request->user()->bachelier;
            $additionalData = [
                'compatibility_score' => $bachelier->getMatchingScore($opportunite),
                'can_apply' => $bachelier->canApplyToOpportunity($opportunite),
                'has_applied' => $bachelier->candidatures()
                    ->where('opportunite_id', $opportunite->id)
                    ->exists(),
                'user_candidature' => $bachelier->candidatures()
                    ->where('opportunite_id', $opportunite->id)
                    ->first()
            ];
        }

        return response()->json([
            'success' => true,
            'data' => array_merge(
                ['opportunite' => $opportunite],
                $additionalData
            )
        ], 200);
    }

    /**
     * Opportunités recommandées pour le bachelier
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recommended(Request $request)
    {
        if (!$request->user() || $request->user()->role !== 'bachelier') {
            return response()->json([
                'success' => false,
                'message' => 'Cette fonctionnalité est réservée aux bacheliers'
            ], 403);
        }

        $bachelier = $request->user()->bachelier;

        $opportunites = Opportunite::where('status', 'published')
            ->where(function($q) {
                $q->whereNull('date_limite_candidature')
                  ->orWhere('date_limite_candidature', '>=', now());
            })
            ->with(['partenaire.user', 'types'])
            ->get();

        // Calculer le score de compatibilité pour chaque opportunité
        $opportunitesAvecScore = $opportunites->map(function ($opportunite) use ($bachelier) {
            $opportunite->compatibility_score = $bachelier->getMatchingScore($opportunite);
            $opportunite->can_apply = $bachelier->canApplyToOpportunity($opportunite);
            $opportunite->has_applied = $bachelier->candidatures()
                ->where('opportunite_id', $opportunite->id)
                ->exists();
            return $opportunite;
        });

        // Trier par score de compatibilité décroissant
        $opportunitesRecommandees = $opportunitesAvecScore
            ->sortByDesc('compatibility_score')
            ->take(10)
            ->values();

        return response()->json([
            'success' => true,
            'data' => $opportunitesRecommandees
        ], 200);
    }

    /**
     * Statistiques des opportunités
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $stats = [
            'total' => Opportunite::where('status', 'published')->count(),
            'actives' => Opportunite::where('status', 'published')
                ->where(function($q) {
                    $q->whereNull('date_limite_candidature')
                      ->orWhere('date_limite_candidature', '>=', now());
                })->count(),
            'expirees' => Opportunite::where('status', 'published')
                ->where('date_limite_candidature', '<', now())
                ->count(),
        ];

        // Stats par type si disponible
        $parType = Opportunite::where('status', 'published')
            ->with('types')
            ->get()
            ->groupBy(function($opportunite) {
                return $opportunite->types->pluck('nom')->join(', ');
            })
            ->map(function($group) {
                return $group->count();
            });

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'par_type' => $parType
            ]
        ], 200);
    }
}









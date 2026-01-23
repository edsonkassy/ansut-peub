<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Candidature;
use App\Models\Opportunite;

class CandidatureController extends Controller
{
    /**
     * Liste des candidatures du bachelier
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $bachelier = $request->user()->bachelier;
        
        if (!$bachelier) {
            return response()->json([
                'success' => false,
                'message' => 'Profil bachelier non trouvé'
            ], 404);
        }

        $query = $bachelier->candidatures()->with(['opportunite.partenaire.user', 'opportunite.types']);

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $candidatures = $query->paginate($perPage);

        // Statistiques
        $stats = [
            'total' => $bachelier->candidatures()->count(),
            'pending' => $bachelier->candidatures()->where('status', 'pending')->count(),
            'accepted' => $bachelier->candidatures()->where('status', 'accepted')->count(),
            'rejected' => $bachelier->candidatures()->where('status', 'rejected')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'candidatures' => $candidatures,
                'stats' => $stats
            ]
        ], 200);
    }

    /**
     * Détails d'une candidature
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $bachelier = $request->user()->bachelier;
        
        $candidature = Candidature::with(['opportunite.partenaire.user', 'opportunite.types', 'bachelier'])
            ->find($id);

        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature non trouvée'
            ], 404);
        }

        // Vérifier que la candidature appartient au bachelier
        if ($candidature->bachelier_id !== $bachelier->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas accès à cette candidature'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $candidature
        ], 200);
    }

    /**
     * Créer une nouvelle candidature
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $bachelier = $request->user()->bachelier;
        
        if (!$bachelier) {
            return response()->json([
                'success' => false,
                'message' => 'Profil bachelier non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'opportunite_id' => 'required|exists:opportunites,id',
            'lettre_motivation' => 'required|string|min:100',
            'documents' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $opportunite = Opportunite::find($request->opportunite_id);

        // Vérifier que l'opportunité existe et est publiée
        if (!$opportunite || $opportunite->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Cette opportunité n\'est pas disponible'
            ], 404);
        }

        // Vérifier que le bachelier peut postuler
        if (!$bachelier->canApplyToOpportunity($opportunite)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas postuler à cette opportunité. Vérifiez les critères d\'éligibilité ou si vous avez déjà postulé.'
            ], 403);
        }

        // Créer la candidature
        $candidature = Candidature::create([
            'bachelier_id' => $bachelier->id,
            'opportunite_id' => $opportunite->id,
            'lettre_motivation' => $request->lettre_motivation,
            'status' => 'pending',
            'documents' => $request->documents ?? [],
        ]);

        // Charger les relations
        $candidature->load(['opportunite.partenaire.user', 'opportunite.types']);

        return response()->json([
            'success' => true,
            'message' => 'Candidature envoyée avec succès',
            'data' => $candidature
        ], 201);
    }

    /**
     * Mettre à jour une candidature (uniquement la lettre de motivation si en attente)
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $bachelier = $request->user()->bachelier;
        
        $candidature = Candidature::find($id);

        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature non trouvée'
            ], 404);
        }

        // Vérifier que la candidature appartient au bachelier
        if ($candidature->bachelier_id !== $bachelier->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas accès à cette candidature'
            ], 403);
        }

        // Vérifier que la candidature est encore en attente
        if ($candidature->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez modifier que les candidatures en attente'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'lettre_motivation' => 'required|string|min:100',
            'documents' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $candidature->update([
            'lettre_motivation' => $request->lettre_motivation,
            'documents' => $request->documents ?? $candidature->documents,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidature mise à jour avec succès',
            'data' => $candidature->fresh(['opportunite.partenaire.user', 'opportunite.types'])
        ], 200);
    }

    /**
     * Retirer/annuler une candidature
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $bachelier = $request->user()->bachelier;
        
        $candidature = Candidature::find($id);

        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature non trouvée'
            ], 404);
        }

        // Vérifier que la candidature appartient au bachelier
        if ($candidature->bachelier_id !== $bachelier->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas accès à cette candidature'
            ], 403);
        }

        // Vérifier que la candidature est encore en attente
        if ($candidature->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez retirer que les candidatures en attente'
            ], 403);
        }

        // Mettre le statut à "withdrawn" au lieu de supprimer
        $candidature->update(['status' => 'withdrawn']);

        return response()->json([
            'success' => true,
            'message' => 'Candidature retirée avec succès'
        ], 200);
    }

    /**
     * Statistiques des candidatures
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $bachelier = $request->user()->bachelier;
        
        if (!$bachelier) {
            return response()->json([
                'success' => false,
                'message' => 'Profil bachelier non trouvé'
            ], 404);
        }

        $stats = [
            'total' => $bachelier->candidatures()->count(),
            'pending' => $bachelier->candidatures()->where('status', 'pending')->count(),
            'accepted' => $bachelier->candidatures()->where('status', 'accepted')->count(),
            'rejected' => $bachelier->candidatures()->where('status', 'rejected')->count(),
            'withdrawn' => $bachelier->candidatures()->where('status', 'withdrawn')->count(),
            'taux_acceptation' => 0,
        ];

        if ($stats['total'] > 0) {
            $stats['taux_acceptation'] = round(($stats['accepted'] / $stats['total']) * 100, 2);
        }

        // Candidatures récentes (derniers 30 jours)
        $recentes = $bachelier->candidatures()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'candidatures_recentes' => $recentes
            ]
        ], 200);
    }
}









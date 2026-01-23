<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Bachelier;
use App\Models\Opportunite;
use App\Models\LibraryResource;
use App\Models\LibraryFavorite;
use App\Services\ImageOptimizationService;

class BachelierController extends Controller
{
    protected $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Obtenir les données du dashboard bachelier
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard(Request $request)
    {
        $bachelier = $request->user()->bachelier;
        
        if (!$bachelier) {
            return response()->json([
                'success' => false,
                'message' => 'Profil bachelier non trouvé'
            ], 404);
        }

        // Statistiques pour le dashboard
        $stats = [
            'candidatures' => $bachelier->candidatures()->count(),
            'candidatures_en_attente' => $bachelier->candidatures()->where('status', 'pending')->count(),
            'candidatures_acceptees' => $bachelier->candidatures()->where('status', 'accepted')->count(),
            'favoris' => $bachelier->favoris()->count(),
            'opportunites_disponibles' => Opportunite::where('status', 'published')
                ->where(function($q) {
                    $q->whereNull('date_limite_candidature')
                      ->orWhere('date_limite_candidature', '>=', now());
                })->count(),
            'library_resources' => LibraryResource::where('is_active', true)->count(),
            'library_favorites' => LibraryFavorite::where('user_id', $request->user()->id)->count(),
        ];
        
        // Dernières opportunités
        $dernieres_opportunites = Opportunite::where('status', 'published')
            ->where(function($q) {
                $q->whereNull('date_limite_candidature')
                  ->orWhere('date_limite_candidature', '>=', now());
            })
            ->with('partenaire.user')
            ->latest()
            ->limit(5)
            ->get();
        
        // Dernières candidatures
        $dernieres_candidatures = $bachelier->candidatures()
            ->with('opportunite.partenaire')
            ->latest()
            ->limit(5)
            ->get();
            
        // Ressources de la bibliothèque récentes
        $ressources_recentes = LibraryResource::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->with('category')
            ->withCount(['likes', 'comments'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Score PEUB si disponible
        $score_peub = null;
        if ($bachelier->score_final_peub) {
            $score_peub = [
                'score_final' => $bachelier->score_final_peub,
                'rang' => $bachelier->rang_peub,
                'boursier' => $bachelier->boursier_peub,
                'eligible' => $bachelier->isInTop2000(),
                'breakdown' => $bachelier->getPeubScoreBreakdown()
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'bachelier' => $bachelier,
                'stats' => $stats,
                'dernieres_opportunites' => $dernieres_opportunites,
                'dernieres_candidatures' => $dernieres_candidatures,
                'ressources_recentes' => $ressources_recentes,
                'score_peub' => $score_peub
            ]
        ], 200);
    }

    /**
     * Obtenir le profil du bachelier
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $bachelier = $request->user()->bachelier()->with('parcoursUniversitaires')->first();
        
        if (!$bachelier) {
            return response()->json([
                'success' => false,
                'message' => 'Profil bachelier non trouvé'
            ], 404);
        }

        // Charger les dotations si boursier PEUB
        $dotations = collect();
        if ($bachelier->boursier_peub) {
            $dotations = $bachelier->getActiveDotations();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'bachelier' => $bachelier,
                'dotations' => $dotations,
                'completion_rate' => $this->calculateProfileCompletion($bachelier)
            ]
        ], 200);
    }

    /**
     * Mettre à jour le profil du bachelier
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $bachelier = $request->user()->bachelier;
        
        if (!$bachelier) {
            return response()->json([
                'success' => false,
                'message' => 'Profil bachelier non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'telephone_eleve' => 'nullable|string|max:20',
            'telephone_parent' => 'nullable|string|max:20',
            'email_parent' => 'nullable|email|max:255',
            'commune' => 'nullable|string|max:255',
            'motivation' => 'nullable|string|max:1000',
            'projet_professionnel' => 'nullable|string|max:1000',
            'competences' => 'nullable|array',
            'langues' => 'nullable|array',
            'bio' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mise à jour des informations
        $bachelier->update($request->only([
            'telephone_eleve',
            'telephone_parent',
            'email_parent',
            'commune',
            'motivation',
            'projet_professionnel',
            'competences',
            'langues',
            'bio'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'data' => $bachelier->fresh()
        ], 200);
    }

    /**
     * Uploader une photo de profil
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bachelier = $request->user()->bachelier;

        try {
            $photoPath = $this->imageService->optimizeAndStore(
                $request->file('photo'), 
                'photos'
            );
            $bachelier->update(['photo' => $photoPath]);

            return response()->json([
                'success' => true,
                'message' => 'Photo uploadée avec succès',
                'data' => [
                    'photo_url' => asset('storage/' . $photoPath)
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload de la photo'
            ], 500);
        }
    }

    /**
     * Uploader un CV
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadCV(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cv' => 'required|file|mimes:pdf|max:10240', // 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bachelier = $request->user()->bachelier;

        try {
            $cvPath = $request->file('cv')->store('cv', 'public');
            $bachelier->update(['cv_path' => $cvPath]);

            return response()->json([
                'success' => true,
                'message' => 'CV uploadé avec succès',
                'data' => [
                    'cv_url' => asset('storage/' . $cvPath)
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload du CV'
            ], 500);
        }
    }

    /**
     * Obtenir les dotations du bachelier
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dotations(Request $request)
    {
        $bachelier = $request->user()->bachelier;
        
        if (!$bachelier->boursier_peub) {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux boursiers PEUB.'
            ], 403);
        }
        
        $dotations = $bachelier->dotationsAttributions()
            ->with(['inventaire.fournisseur'])
            ->latest()
            ->get();

        $dotations_actives = $dotations->where('status', 'active');
        $dotations_par_type = [
            'ordinateur_portable' => $dotations->filter(fn($d) => $d->inventaire->type_dotation === 'ordinateur_portable'),
            'connexion_internet' => $dotations->filter(fn($d) => $d->inventaire->type_dotation === 'connexion_internet'),
            'abonnement_ia' => $dotations->filter(fn($d) => $d->inventaire->type_dotation === 'abonnement_ia'),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'dotations' => $dotations,
                'dotations_actives' => $dotations_actives,
                'dotations_par_type' => $dotations_par_type,
                'stats' => [
                    'total' => $dotations->count(),
                    'actives' => $dotations_actives->count(),
                    'suspendues' => $dotations->where('status', 'suspendue')->count(),
                ]
            ]
        ], 200);
    }

    /**
     * Exporter les données du profil
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportData(Request $request)
    {
        $bachelier = $request->user()->bachelier;
        
        $data = [
            'informations_personnelles' => [
                'nom' => $bachelier->nom,
                'prenoms' => $bachelier->prenoms,
                'date_naissance' => $bachelier->date_naissance?->format('d/m/Y'),
                'lieu_naissance' => $bachelier->lieu_naissance,
                'sexe' => $bachelier->sexe === 'M' ? 'Masculin' : 'Féminin',
                'region' => $bachelier->region,
                'commune' => $bachelier->commune,
            ],
            'contact' => [
                'telephone_eleve' => $bachelier->telephone_eleve,
                'telephone_parent' => $bachelier->telephone_parent,
                'email_eleve' => $bachelier->email_eleve,
                'email_parent' => $bachelier->email_parent,
            ],
            'informations_academiques' => [
                'matricule_bac' => $bachelier->matricule_bac,
                'serie_bac' => $bachelier->serie_bac,
                'note_bac' => $bachelier->note_bac,
                'mention' => $bachelier->mention,
                'etablissement' => $bachelier->etablissement_nom,
                'type_etablissement' => $bachelier->etablissement_type,
                'annee_bac' => $bachelier->annee_bac,
            ],
            'statut_peub' => [
                'boursier_peub' => $bachelier->boursier_peub,
                'score_final' => $bachelier->score_final_peub,
                'rang' => $bachelier->rang_peub,
                'status_candidature' => $bachelier->status_candidature,
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    /**
     * Calculer le taux de complétion du profil
     * 
     * @param Bachelier $bachelier
     * @return int
     */
    private function calculateProfileCompletion(Bachelier $bachelier): int
    {
        $fields = [
            'nom', 'prenoms', 'date_naissance', 'lieu_naissance', 'sexe',
            'telephone_eleve', 'telephone_parent', 'email_eleve', 'email_parent',
            'region', 'commune', 'matricule_bac', 'serie_bac', 'note_bac',
            'mention', 'etablissement_nom', 'annee_bac', 'motivation',
            'projet_professionnel', 'photo', 'cv_path'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($bachelier->$field)) {
                $completed++;
            }
        }

        return (int) (($completed / count($fields)) * 100);
    }
}









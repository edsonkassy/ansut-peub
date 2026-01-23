<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Opportunite;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends Controller
{
    protected $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
        Log::info('CandidatureController initialized');
    }

    public function index(Request $request)
    {
        Log::info('CandidatureController@index called', [
            'user_id' => Auth::id(),
            'request_params' => $request->all()
        ]);

        try {
            $bachelier = Auth::user()->bachelier;
            Log::info('Bachelier retrieved', ['bachelier_id' => $bachelier->id ?? 'null']);
            
            $query = $bachelier->candidatures()->with(['opportunite.partenaire']);

            // Recherche
            if ($request->filled('search')) {
                $search = $request->search;
                Log::info('Applying search filter', ['search_term' => $search]);
                $query->whereHas('opportunite', function($q) use ($search) {
                    $q->where('titre', 'like', "%{$search}%")
                      ->orWhereHas('partenaire', function($pq) use ($search) {
                          $pq->where('nom_organisation', 'like', "%{$search}%");
                      });
                });
            }

            // Filtre par statut
            if ($request->filled('status')) {
                Log::info('Applying status filter', ['status' => $request->status]);
                $query->where('status', $request->status);
            }

            // Filtre par type
            if ($request->filled('type')) {
                Log::info('Applying type filter', ['type' => $request->type]);
                $query->whereHas('opportunite', function($q) use ($request) {
                    $q->where('type', $request->type);
                });
            }

            // Tri
            $query->latest('date_soumission');

            $candidatures = $query->paginate(15);
            Log::info('Candidatures retrieved', ['count' => $candidatures->count()]);

            // Statistiques
            $stats = [
                'pending' => $bachelier->candidatures()->where('status', 'pending')->count(),
                'reviewed' => $bachelier->candidatures()->where('status', 'reviewed')->count(),
                'accepted' => $bachelier->candidatures()->where('status', 'accepted')->count(),
                'rejected' => $bachelier->candidatures()->where('status', 'rejected')->count(),
            ];
            Log::info('Stats calculated', $stats);

            return view('bachelier.candidatures', compact('candidatures', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error in CandidatureController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function show(Candidature $candidature)
    {
        Log::info('CandidatureController@show called', [
            'user_id' => Auth::id(),
            'candidature_id' => $candidature->id
        ]);

        try {
            // Vérifier que la candidature appartient à l'utilisateur connecté
            $bachelier = Auth::user()->bachelier;
            Log::info('Checking candidature ownership', [
                'candidature_bachelier_id' => $candidature->bachelier_id,
                'current_bachelier_id' => $bachelier->id
            ]);

            if ($candidature->bachelier_id !== $bachelier->id) {
                Log::warning('Unauthorized access attempt to candidature', [
                    'candidature_id' => $candidature->id,
                    'user_id' => Auth::id()
                ]);
                abort(403);
            }

            $candidature->load(['opportunite.partenaire']);
            Log::info('Candidature loaded successfully', [
                'candidature_id' => $candidature->id,
                'opportunite_id' => $candidature->opportunite_id
            ]);

            return view('bachelier.candidatures-show', compact('candidature'));
        } catch (\Exception $e) {
            Log::error('Error in CandidatureController@show', [
                'error' => $e->getMessage(),
                'candidature_id' => $candidature->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function store(Request $request)
    {
        Log::info('CandidatureController@store called', [
            'user_id' => Auth::id(),
            'request_data' => $request->except(['documents']), // Exclure les fichiers pour éviter les logs trop volumineux
            'has_documents' => $request->hasFile('documents'),
            'documents_count' => $request->hasFile('documents') ? count($request->file('documents')) : 0
        ]);

        try {
            $request->validate([
                'opportunite_id' => 'required|exists:opportunites,id',
                'lettre_motivation' => 'nullable|string|min:100',
                'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB max - images et PDF
            ]);
            Log::info('Validation passed');

            $bachelier = Auth::user()->bachelier;
            Log::info('Bachelier retrieved', ['bachelier_id' => $bachelier->id]);

            $opportunite = Opportunite::findOrFail($request->opportunite_id);
            Log::info('Opportunite retrieved', [
                'opportunite_id' => $opportunite->id,
                'opportunite_status' => $opportunite->status,
                'date_limite' => $opportunite->date_limite_candidature
            ]);

            // Vérifier que l'opportunité est publiée
            if ($opportunite->status !== 'published') {
                Log::warning('Attempt to apply to unpublished opportunity', [
                    'opportunite_id' => $opportunite->id,
                    'status' => $opportunite->status
                ]);
                return back()->with('error', 'Cette opportunité n\'est plus disponible.');
            }

            // Vérifier la date limite
            if ($opportunite->date_limite_candidature && $opportunite->date_limite_candidature->isPast()) {
                Log::warning('Attempt to apply after deadline', [
                    'opportunite_id' => $opportunite->id,
                    'date_limite' => $opportunite->date_limite_candidature,
                    'current_time' => now()
                ]);
                return back()->with('error', 'La date limite de candidature est dépassée.');
            }

            // Vérifier qu'il n'a pas déjà postulé
            $existingCandidature = $bachelier->candidatures()->where('opportunite_id', $opportunite->id)->first();
            if ($existingCandidature) {
                Log::warning('Duplicate candidature attempt', [
                    'bachelier_id' => $bachelier->id,
                    'opportunite_id' => $opportunite->id,
                    'existing_candidature_id' => $existingCandidature->id
                ]);
                return back()->with('error', 'Vous avez déjà postulé à cette opportunité.');
            }

            // Traitement des documents (images et PDF)
            $documents = [];
            if ($request->hasFile('documents')) {
                Log::info('Processing documents', ['count' => count($request->file('documents'))]);
                foreach ($request->file('documents') as $index => $document) {
                    Log::info('Processing document', [
                        'index' => $index,
                        'original_name' => $document->getClientOriginalName(),
                        'size' => $document->getSize(),
                        'mime_type' => $document->getMimeType()
                    ]);
                    
                    try {
                        // Vérifier si c'est une image ou un PDF
                        $mimeType = $document->getMimeType();
                        
                        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
                            // Optimiser les images
                            $path = $this->imageService->optimizeAndStore($document, 'candidatures/documents');
                        } else {
                            // Stocker directement les PDF et autres fichiers autorisés
                            $filename = uniqid() . '.' . $document->getClientOriginalExtension();
                            $path = $document->storeAs('candidatures/documents', $filename, 'public');
                        }
                        
                        $documents[] = [
                            'path' => $path,
                            'original_name' => $document->getClientOriginalName(),
                            'mime_type' => $mimeType,
                            'size' => $document->getSize()
                        ];
                        
                        Log::info('Document processed successfully', ['path' => $path]);
                    } catch (\Exception $e) {
                        Log::error('Error processing document', [
                            'index' => $index,
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }
            }

            // Calculer un score de matching basique (à améliorer avec l'IA)
            $score = $this->calculateMatchingScore($bachelier, $opportunite);
            Log::info('Matching score calculated', ['score' => $score]);

            // Créer la candidature
            $candidatureData = [
                'bachelier_id' => $bachelier->id,
                'opportunite_id' => $opportunite->id,
                'type_interaction' => 'candidature',
                'lettre_motivation' => $request->lettre_motivation ?: 'Candidature automatique via PEUB - Score de compatibilité calculé par IA',
                'documents_joints' => $documents,
                'status' => 'pending',
                'date_soumission' => now(),
                'score_matching' => $score,
            ];
            
            Log::info('Creating candidature', $candidatureData);
            $candidature = Candidature::create($candidatureData);
            Log::info('Candidature created successfully', ['candidature_id' => $candidature->id]);

            // Incrémenter le compteur de candidatures de l'opportunité
            $opportunite->increment('candidatures_count');
            Log::info('Opportunite candidatures count incremented', [
                'opportunite_id' => $opportunite->id,
                'new_count' => $opportunite->fresh()->candidatures_count
            ]);

            // Si c'est une requête AJAX, retourner JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Votre candidature a été soumise avec succès !',
                    'candidature_id' => $candidature->id,
                    'redirect_url' => route('bachelier.candidatures.show', $candidature)
                ]);
            }

            return redirect()->route('bachelier.candidatures.show', $candidature)
                ->with('success', 'Votre candidature a été soumise avec succès !');
        } catch (\Exception $e) {
            Log::error('Error in CandidatureController@store', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['documents'])
            ]);
            
            // Si c'est une requête AJAX, retourner JSON d'erreur
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la soumission de votre candidature. Veuillez réessayer.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Une erreur est survenue lors de la soumission de votre candidature.');
        }
    }

    public function withdraw(Candidature $candidature)
    {
        Log::info('CandidatureController@withdraw called', [
            'user_id' => Auth::id(),
            'candidature_id' => $candidature->id
        ]);

        try {
            // Vérifier que la candidature appartient à l'utilisateur connecté
            $bachelier = Auth::user()->bachelier;
            Log::info('Checking candidature ownership for withdrawal', [
                'candidature_bachelier_id' => $candidature->bachelier_id,
                'current_bachelier_id' => $bachelier->id
            ]);

            if ($candidature->bachelier_id !== $bachelier->id) {
                Log::warning('Unauthorized withdrawal attempt', [
                    'candidature_id' => $candidature->id,
                    'user_id' => Auth::id()
                ]);
                abort(403);
            }

            // Vérifier que la candidature est en attente
            Log::info('Checking candidature status', ['status' => $candidature->status]);
            if ($candidature->status !== 'pending') {
                Log::warning('Attempt to withdraw non-pending candidature', [
                    'candidature_id' => $candidature->id,
                    'status' => $candidature->status
                ]);
                return back()->with('error', 'Impossible de retirer une candidature qui a déjà été traitée.');
            }

            // Supprimer les documents
            if ($candidature->documents_joints) {
                Log::info('Deleting candidature documents', [
                    'documents_count' => count($candidature->documents_joints)
                ]);
                foreach ($candidature->documents_joints as $document) {
                    try {
                        // Gérer les anciens formats (string) et nouveaux formats (array)
                        $path = is_array($document) ? $document['path'] : $document;
                        Storage::disk('public')->delete($path);
                        Log::info('Document deleted', ['path' => $path]);
                    } catch (\Exception $e) {
                        Log::error('Error deleting document', [
                            'document' => $document,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            // Décrémenter le compteur de candidatures de l'opportunité
            $opportunite = $candidature->opportunite;
            $opportunite->decrement('candidatures_count');
            Log::info('Opportunite candidatures count decremented', [
                'opportunite_id' => $opportunite->id,
                'new_count' => $opportunite->fresh()->candidatures_count
            ]);

            // Supprimer la candidature
            $candidature->delete();
            Log::info('Candidature deleted successfully', ['candidature_id' => $candidature->id]);

            return redirect()->route('bachelier.candidatures')
                ->with('success', 'Votre candidature a été retirée avec succès.');
        } catch (\Exception $e) {
            Log::error('Error in CandidatureController@withdraw', [
                'error' => $e->getMessage(),
                'candidature_id' => $candidature->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function calculateMatchingScore($bachelier, $opportunite)
    {
        Log::info('Calculating matching score', [
            'bachelier_id' => $bachelier->id,
            'opportunite_id' => $opportunite->id
        ]);

        $score = 0;
        $scoreDetails = [];

        // Score basé sur la série BAC
        if ($opportunite->serie_bac_requise && $bachelier->serie_bac === $opportunite->serie_bac_requise) {
            $score += 20;
            $scoreDetails['serie_bac'] = 20;
            Log::info('BAC series match', [
                'bachelier_serie' => $bachelier->serie_bac,
                'opportunite_serie' => $opportunite->serie_bac_requise,
                'points' => 20
            ]);
        }

        // Score basé sur la note BAC
        if ($bachelier->note_bac >= 12) {
            $score += 20;
            $scoreDetails['note_bac'] = 20;
            Log::info('BAC note excellent', ['note' => $bachelier->note_bac, 'points' => 20]);
        } elseif ($bachelier->note_bac >= 10) {
            $score += 10;
            $scoreDetails['note_bac'] = 10;
            Log::info('BAC note good', ['note' => $bachelier->note_bac, 'points' => 10]);
        }

        // Score basé sur la région
        if ($opportunite->region && $bachelier->region === $opportunite->region) {
            $score += 15;
            $scoreDetails['region'] = 15;
            Log::info('Region match', [
                'bachelier_region' => $bachelier->region,
                'opportunite_region' => $opportunite->region,
                'points' => 15
            ]);
        }

        // Score basé sur les compétences
        if ($bachelier->competences && $opportunite->competences_requises) {
            $bachelierCompetences = is_array($bachelier->competences) ? $bachelier->competences : explode(',', $bachelier->competences);
            $opportuniteCompetences = is_array($opportunite->competences_requises) ? $opportunite->competences_requises : explode(',', $opportunite->competences_requises);
            
            $matchingCompetences = array_intersect($bachelierCompetences, $opportuniteCompetences);
            $competenceScore = count($matchingCompetences) * 5;
            $score += $competenceScore;
            $scoreDetails['competences'] = $competenceScore;
            
            Log::info('Competences matching', [
                'bachelier_competences' => $bachelierCompetences,
                'opportunite_competences' => $opportuniteCompetences,
                'matching_competences' => $matchingCompetences,
                'points' => $competenceScore
            ]);
        }

        // Score basé sur les langues
        if ($bachelier->langues && $opportunite->langues_requises) {
            $bachelierLangues = is_array($bachelier->langues) ? $bachelier->langues : explode(',', $bachelier->langues);
            $opportuniteLangues = is_array($opportunite->langues_requises) ? $opportunite->langues_requises : explode(',', $opportunite->langues_requises);
            
            $matchingLangues = array_intersect($bachelierLangues, $opportuniteLangues);
            $langueScore = count($matchingLangues) * 5;
            $score += $langueScore;
            $scoreDetails['langues'] = $langueScore;
            
            Log::info('Languages matching', [
                'bachelier_langues' => $bachelierLangues,
                'opportunite_langues' => $opportuniteLangues,
                'matching_langues' => $matchingLangues,
                'points' => $langueScore
            ]);
        }

        $finalScore = min($score, 100); // Score maximum de 100
        
        Log::info('Matching score calculation completed', [
            'score_details' => $scoreDetails,
            'total_score' => $score,
            'final_score' => $finalScore
        ]);

        return $finalScore;
    }
}

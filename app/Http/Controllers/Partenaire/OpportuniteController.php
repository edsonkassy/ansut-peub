<?php

namespace App\Http\Controllers\Partenaire;

use App\Http\Controllers\Controller;
use App\Models\Opportunite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\ImageGenerationService;
use Illuminate\Support\Facades\Log;

class OpportuniteController extends Controller
{
    protected $imageGenerationService;

    public function __construct(ImageGenerationService $imageGenerationService)
    {
        $this->imageGenerationService = $imageGenerationService;
    }

    /**
     * Affiche la liste des opportunités du partenaire
     */
    public function index()
    {
        $partenaire = auth()->user()->partenaire;
        
        $opportunites = Opportunite::where('partenaire_id', $partenaire->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('partenaire.opportunites.index', compact('opportunites'));
    }

    /**
     * Affiche le formulaire de création d'opportunité
     */
    public function create()
    {
        return view('partenaire.opportunites.create');
    }

    /**
     * Enregistre une nouvelle opportunité
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:bourse,stage,emploi,formation,concours,event,promotion',
            'description' => 'required|string',
            'competences_requises' => 'nullable|string',
            'criteres_eligibilite' => 'nullable|string',
            'pays' => 'required|string|in:Bénin,Burkina Faso,Côte d\'Ivoire,Guinée-Bissau,Mali,Niger,Sénégal,Togo,Guinée,France',
            'ville' => 'nullable|string|max:255',
            'duree' => 'required|string|max:255',
            'remuneration' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date|after_or_equal:today',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'date_limite_candidature' => 'required|date|after_or_equal:today',
            'nombre_places' => 'nullable|integer|min:1',
            'documents_requis' => 'nullable|string',
            'illustration' => 'nullable|image|mimes:jpg,jpeg,png|max:20480', // 20MB max
            'generated_illustration' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_telephone' => 'nullable|string|max:20',
            'lien_externe' => 'nullable|url|max:255',
            'status' => 'required|in:draft,published,closed,archived',
        ]);

        // Validation personnalisée pour date_limite_candidature
        if ($request->date_debut && $request->date_limite_candidature >= $request->date_debut) {
            return back()->withErrors(['date_limite_candidature' => 'La date limite de candidature doit être antérieure à la date de début.'])->withInput();
        }

        $partenaire = auth()->user()->partenaire;

        // Traitement des champs JSON
        $competences_requises = $request->competences_requises ? 
            array_filter(array_map('trim', explode(',', $request->competences_requises))) : [];
        
        $criteres_eligibilite = $request->criteres_eligibilite ? 
            array_filter(array_map('trim', explode(',', $request->criteres_eligibilite))) : [];
        
        $documents_requis = $request->documents_requis ? 
            array_filter(array_map('trim', explode(',', $request->documents_requis))) : [];

        // Traitement de l'illustration
        $illustration = null;

        if ($request->hasFile('illustration')) {
            $illustration = $request->file('illustration')->store('public/opportunites');
            $illustration = str_replace('public/', '', $illustration);
        } elseif ($request->filled('generated_illustration')) {
            $illustration = $request->generated_illustration;
        }

        $opportunite = Opportunite::create([
            'partenaire_id' => $partenaire->id,
            'titre' => $request->titre,
            'type' => $request->type,
            'description' => $request->description,
            'illustration' => $illustration,
            'competences_requises' => $competences_requises,
            'criteres_eligibilite' => $criteres_eligibilite,
            'pays' => $request->pays,
            'ville' => $request->ville,
            'duree' => $request->duree,
            'remuneration' => $request->remuneration,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'date_limite_candidature' => $request->date_limite_candidature,
            'nombre_places' => $request->nombre_places,
            'documents_requis' => $documents_requis,
            'contact_email' => $request->contact_email,
            'contact_telephone' => $request->contact_telephone,
            'lien_externe' => $request->lien_externe,
            'status' => $request->status,
        ]);

        return redirect()->route('partenaire.opportunites.show', $opportunite)
            ->with('success', 'Opportunité créée avec succès !');
    }

    /**
     * Affiche les détails d'une opportunité
     */
    public function show(Opportunite $opportunite)
    {
        $partenaire = auth()->user()->partenaire;
        
        // Vérifier que l'opportunité appartient au partenaire
        if ($opportunite->partenaire_id !== $partenaire->id) {
            abort(403, 'Accès non autorisé');
        }

        $opportunite->load(['candidatures.bachelier']);
        
        return view('partenaire.opportunites.show', compact('opportunite'));
    }

    /**
     * Affiche le formulaire d'édition d'une opportunité
     */
    public function edit(Opportunite $opportunite)
    {
        $partenaire = auth()->user()->partenaire;
        
        // Vérifier que l'opportunité appartient au partenaire
        if ($opportunite->partenaire_id !== $partenaire->id) {
            abort(403, 'Accès non autorisé');
        }

        return view('partenaire.opportunites.edit', compact('opportunite'));
    }

    /**
     * Met à jour une opportunité
     */
    public function update(Request $request, Opportunite $opportunite)
    {
        $partenaire = auth()->user()->partenaire;
        
        // Vérifier que l'opportunité appartient au partenaire
        if ($opportunite->partenaire_id !== $partenaire->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:bourse,stage,emploi,formation,concours,event,promotion',
            'description' => 'required|string',
            'competences_requises' => 'nullable|string',
            'criteres_eligibilite' => 'nullable|string',
            'pays' => 'required|string|in:Bénin,Burkina Faso,Côte d\'Ivoire,Guinée-Bissau,Mali,Niger,Sénégal,Togo,Guinée,France',
            'ville' => 'nullable|string|max:255',
            'duree' => 'required|string|max:255',
            'remuneration' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date|after_or_equal:today',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'date_limite_candidature' => 'required|date|after_or_equal:today',
            'nombre_places' => 'nullable|integer|min:1',
            'documents_requis' => 'nullable|string',
            'illustration' => 'nullable|image|mimes:jpg,jpeg,png|max:20480', // 20MB max
            'generated_illustration' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_telephone' => 'nullable|string|max:20',
            'lien_externe' => 'nullable|url|max:255',
            'status' => 'required|in:draft,published,closed,archived',
        ]);

        // Validation personnalisée pour date_limite_candidature
        if ($request->date_debut && $request->date_limite_candidature >= $request->date_debut) {
            return back()->withErrors(['date_limite_candidature' => 'La date limite de candidature doit être antérieure à la date de début.'])->withInput();
        }

        // Traitement des champs JSON
        $competences_requises = $request->competences_requises ? 
            array_filter(array_map('trim', explode(',', $request->competences_requises))) : [];
        
        $criteres_eligibilite = $request->criteres_eligibilite ? 
            array_filter(array_map('trim', explode(',', $request->criteres_eligibilite))) : [];
        
        $documents_requis = $request->documents_requis ? 
            array_filter(array_map('trim', explode(',', $request->documents_requis))) : [];

        // Traitement de l'illustration
        $illustration = $opportunite->illustration;

        if ($request->hasFile('illustration')) {
            // Supprimer l'ancienne illustration si elle existe
            if ($illustration) {
                Storage::delete($illustration);
            }
            $illustration = $request->file('illustration')->store('public/opportunites');
            $illustration = str_replace('public/', '', $illustration);
        } elseif ($request->filled('generated_illustration')) {
            // Si une nouvelle image a été générée
            if ($illustration) {
                Storage::delete($illustration);
            }
            $illustration = $request->generated_illustration;
        }

        $opportunite->update([
            'titre' => $request->titre,
            'type' => $request->type,
            'description' => $request->description,
            'competences_requises' => $competences_requises,
            'criteres_eligibilite' => $criteres_eligibilite,
            'pays' => $request->pays,
            'ville' => $request->ville,
            'duree' => $request->duree,
            'remuneration' => $request->remuneration,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'date_limite_candidature' => $request->date_limite_candidature,
            'nombre_places' => $request->nombre_places,
            'documents_requis' => $documents_requis,
            'illustration' => $illustration,
            'contact_email' => $request->contact_email,
            'contact_telephone' => $request->contact_telephone,
            'lien_externe' => $request->lien_externe,
            'status' => $request->status,
        ]);

        return redirect()->route('partenaire.opportunites.show', $opportunite)
            ->with('success', 'Opportunité mise à jour avec succès !');
    }

    /**
     * Supprime une opportunité
     */
    public function destroy(Opportunite $opportunite)
    {
        $partenaire = auth()->user()->partenaire;
        
        // Vérifier que l'opportunité appartient au partenaire
        if ($opportunite->partenaire_id !== $partenaire->id) {
            abort(403, 'Accès non autorisé');
        }

        // Supprimer l'illustration si elle existe
        if ($opportunite->illustration) {
            Storage::delete('public/' . $opportunite->illustration);
        }

        $opportunite->delete();

        return redirect()->route('partenaire.opportunites.index')
            ->with('success', 'Opportunité supprimée avec succès !');
    }

    /**
     * Génère une illustration pour l'opportunité
     */
    public function generateImage(Request $request)
    {
        try {
            Log::info('Début de la génération d\'image', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role ?? 'none',
                'request_data' => $request->all()
            ]);

            $request->validate([
                'type' => 'required|string',
                'titre' => 'required|string',
                'description' => 'nullable|string',
            ]);

            Log::info('Validation réussie, appel du service');

            $result = $this->imageGenerationService->generateOpportunityIllustration(
                $request->titre,
                $request->type,
                $request->description
            );

            Log::info('Résultat du service', $result);

            if (!$result['success']) {
                Log::error('Échec de la génération d\'image', $result);
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 422);
            }

            Log::info('Génération d\'image réussie', $result);

            return response()->json([
                'success' => true,
                'image_path' => $result['path'],
                'full_url' => Storage::url($result['path']),
                'model_used' => 'dall-e-3',
                'message' => 'Image générée avec succès par DALL-E-3'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dans generateImage', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors de la génération de l\'image: ' . $e->getMessage()
            ], 500);
        }
    }
}

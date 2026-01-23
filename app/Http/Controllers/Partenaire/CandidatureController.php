<?php

namespace App\Http\Controllers\Partenaire;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Opportunite;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    /**
     * Affiche la liste des candidatures reçues par le partenaire
     */
    public function index(Request $request)
    {
        $partenaire = auth()->user()->partenaire;
        
        $query = Candidature::whereHas('opportunite', function($q) use ($partenaire) {
            $q->where('partenaire_id', $partenaire->id);
        })->with(['bachelier', 'opportunite']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('opportunite_id')) {
            $query->where('opportunite_id', $request->opportunite_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bachelier', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $candidatures = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Opportunités du partenaire pour le filtre
        $opportunites = Opportunite::where('partenaire_id', $partenaire->id)
            ->orderBy('titre')
            ->get();

        // Statistiques
        $stats = [
            'total' => Candidature::whereHas('opportunite', function($q) use ($partenaire) {
                $q->where('partenaire_id', $partenaire->id);
            })->count(),
            'pending' => Candidature::whereHas('opportunite', function($q) use ($partenaire) {
                $q->where('partenaire_id', $partenaire->id);
            })->where('status', 'pending')->count(),
            'accepted' => Candidature::whereHas('opportunite', function($q) use ($partenaire) {
                $q->where('partenaire_id', $partenaire->id);
            })->where('status', 'accepted')->count(),
            'rejected' => Candidature::whereHas('opportunite', function($q) use ($partenaire) {
                $q->where('partenaire_id', $partenaire->id);
            })->where('status', 'rejected')->count(),
        ];

        return view('partenaire.candidatures.index', compact('candidatures', 'opportunites', 'stats'));
    }

    /**
     * Affiche les détails d'une candidature
     */
    public function show(Candidature $candidature)
    {
        $partenaire = auth()->user()->partenaire;
        
        // Vérifier que la candidature appartient au partenaire
        if ($candidature->opportunite->partenaire_id !== $partenaire->id) {
            abort(403, 'Accès non autorisé');
        }

        $candidature->load(['bachelier', 'opportunite']);
        
        return view('partenaire.candidatures.show', compact('candidature'));
    }

    /**
     * Met à jour le statut d'une candidature
     */
    public function update(Request $request, Candidature $candidature)
    {
        $partenaire = auth()->user()->partenaire;
        
        // Vérifier que la candidature appartient au partenaire
        if ($candidature->opportunite->partenaire_id !== $partenaire->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,reviewed',
            'commentaire_partenaire' => 'nullable|string|max:1000',
            'date_reponse' => 'nullable|date',
        ]);

        $candidature->update([
            'status' => $request->status,
            'commentaire_partenaire' => $request->commentaire_partenaire,
            'date_reponse' => $request->date_reponse ?: now(),
        ]);

        return back()->with('success', 'Statut de la candidature mis à jour avec succès !');
    }
}

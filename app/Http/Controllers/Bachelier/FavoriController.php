<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\Favori;
use App\Models\Opportunite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    public function index(Request $request)
    {
        $bachelier = Auth::user()->bachelier;
        
        $query = $bachelier->favoris()->with(['opportunite.partenaire']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('opportunite', function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhereHas('partenaire', function($pq) use ($search) {
                      $pq->where('nom_organisation', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->whereHas('opportunite', function($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        // Tri
        switch ($request->get('sort', 'recent')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'deadline':
                $query->whereHas('opportunite', function($q) {
                    $q->orderBy('date_limite_candidature', 'asc');
                });
                break;
            default:
                $query->latest('created_at');
                break;
        }

        $favoris = $query->paginate(12);

        // Marquer les candidatures existantes
        foreach ($favoris as $favori) {
            $favori->opportunite->hasApplied = $bachelier->candidatures()
                ->where('opportunite_id', $favori->opportunite->id)
                ->exists();
        }

        return view('bachelier.favoris', compact('favoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'opportunite_id' => 'required|exists:opportunites,id',
        ]);

        $bachelier = Auth::user()->bachelier;
        $opportunite = Opportunite::findOrFail($request->opportunite_id);

        // Vérifier que l'opportunité est publiée
        if ($opportunite->status !== 'published') {
            return response()->json(['error' => 'Cette opportunité n\'est plus disponible.'], 400);
        }

        // Vérifier qu'il n'a pas déjà ajouté aux favoris
        if ($bachelier->favoris()->where('opportunite_id', $opportunite->id)->exists()) {
            return response()->json(['error' => 'Cette opportunité est déjà dans vos favoris.'], 400);
        }

        // Ajouter aux favoris
        Favori::create([
            'bachelier_id' => $bachelier->id,
            'opportunite_id' => $opportunite->id,
        ]);

        return response()->json(['success' => 'Opportunité ajoutée aux favoris.']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'opportunite_id' => 'required|exists:opportunites,id',
        ]);

        $bachelier = Auth::user()->bachelier;
        
        $favori = $bachelier->favoris()
            ->where('opportunite_id', $request->opportunite_id)
            ->first();

        if (!$favori) {
            return response()->json(['error' => 'Favori non trouvé.'], 404);
        }

        $favori->delete();

        return response()->json(['success' => 'Opportunité retirée des favoris.']);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'opportunite_id' => 'required|exists:opportunites,id',
        ]);

        $bachelier = Auth::user()->bachelier;
        $opportunite = Opportunite::findOrFail($request->opportunite_id);

        // Vérifier que l'opportunité est publiée
        if ($opportunite->status !== 'published') {
            return response()->json(['error' => 'Cette opportunité n\'est plus disponible.'], 400);
        }

        $favori = $bachelier->favoris()
            ->where('opportunite_id', $opportunite->id)
            ->first();

        if ($favori) {
            $favori->delete();
            $isFavorited = false;
            $message = 'Opportunité retirée des favoris.';
        } else {
            Favori::create([
                'bachelier_id' => $bachelier->id,
                'opportunite_id' => $opportunite->id,
            ]);
            $isFavorited = true;
            $message = 'Opportunité ajoutée aux favoris.';
        }

        return response()->json([
            'success' => $message,
            'isFavorited' => $isFavorited
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'favori_ids' => 'required|array',
            'favori_ids.*' => 'exists:favoris,id',
        ]);

        $bachelier = Auth::user()->bachelier;
        
        $deletedCount = $bachelier->favoris()
            ->whereIn('id', $request->favori_ids)
            ->delete();

        return response()->json([
            'success' => "{$deletedCount} favori(s) supprimé(s) avec succès."
        ]);
    }
}

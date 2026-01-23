<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\Opportunite;
use App\Models\Favori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportuniteController extends Controller
{
    public function index(Request $request)
    {
        $query = Opportunite::with(['partenaire', 'candidatures'])
            ->where('status', 'published');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('partenaire', function($pq) use ($search) {
                      $pq->where('nom_organisation', 'like', "%{$search}%");
                  });
            });
        }

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('location')) {
            if ($request->location === 'À distance') {
                $query->whereNull('ville')->whereNull('regions_ciblees');
            } else {
                $query->where(function($q) use ($request) {
                    $q->whereJsonContains('regions_ciblees', $request->location)
                      ->orWhere('ville', $request->location);
                });
            }
        }

        if ($request->filled('duration')) {
            switch ($request->duration) {
                case 'court':
                    $query->where('duree', 'like', '%mois%')->where('duree', 'not like', '%an%');
                    break;
                case 'moyen':
                    $query->where('duree', 'like', '%mois%');
                    break;
                case 'long':
                    $query->where('duree', 'like', '%an%');
                    break;
            }
        }

        // Tri
        switch ($request->get('sort', 'recent')) {
            case 'popular':
                $query->orderBy('vues', 'desc');
                break;
            case 'deadline':
                $query->orderBy('date_limite_candidature', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $opportunites = $query->paginate(12);

        // Marquer les favoris
        $bachelier = Auth::user()->bachelier;
        foreach ($opportunites as $opportunite) {
            $opportunite->isFavorited = $bachelier->favoris()->where('opportunite_id', $opportunite->id)->exists();
            $opportunite->hasApplied = $bachelier->candidatures()->where('opportunite_id', $opportunite->id)->exists();
        }

        return view('bachelier.opportunites', compact('opportunites'));
    }

    public function show(Opportunite $opportunite)
    {
        // Vérifier que l'opportunité est publiée
        if ($opportunite->status !== 'published') {
            abort(404);
        }

        // Incrémenter les vues
        $opportunite->increment('vues');

        // Charger les relations
        $opportunite->load(['partenaire', 'candidatures.bachelier']);

        // Vérifier si l'utilisateur a déjà postulé
        $bachelier = Auth::user()->bachelier;
        $hasApplied = $bachelier->candidatures()->where('opportunite_id', $opportunite->id)->exists();
        $candidature = null;
        
        if ($hasApplied) {
            $candidature = $bachelier->candidatures()->where('opportunite_id', $opportunite->id)->first();
        }

        // Vérifier si c'est un favori
        $opportunite->isFavorited = $bachelier->favoris()->where('opportunite_id', $opportunite->id)->exists();

        // Opportunités similaires
        $opportunites_similaires = Opportunite::where('status', 'published')
            ->where('type', $opportunite->type)
            ->where('id', '!=', $opportunite->id)
            ->limit(3)
            ->get();

        return view('bachelier.opportunites-show', compact('opportunite', 'hasApplied', 'candidature', 'opportunites_similaires'));
    }
}

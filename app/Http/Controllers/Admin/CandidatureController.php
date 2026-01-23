<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Opportunite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidatureController extends Controller
{
    /**
     * Afficher la liste des candidatures groupées par opportunité
     */
    public function index(Request $request)
    {
        $query = Candidature::with(['bachelier', 'opportunite.partenaire']);
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('opportunite_id')) {
            $query->where('opportunite_id', $request->opportunite_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('bachelier', function($bq) use ($search) {
                    $bq->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('opportunite', function($oq) use ($search) {
                    $oq->where('titre', 'like', "%{$search}%");
                })->orWhereHas('opportunite.partenaire', function($pq) use ($search) {
                    $pq->where('nom_organisation', 'like', "%{$search}%");
                });
            });
        }
        
        // Tri par date de soumission (plus récent en premier)
        $candidatures = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Opportunités pour le filtre
        $opportunites = Opportunite::with('partenaire')
            ->orderBy('titre')
            ->get();
        
        // Statistiques globales
        $stats = [
            'total' => Candidature::count(),
            'pending' => Candidature::where('status', 'pending')->count(),
            'reviewed' => Candidature::where('status', 'reviewed')->count(),
            'accepted' => Candidature::where('status', 'accepted')->count(),
            'rejected' => Candidature::where('status', 'rejected')->count(),
        ];
        
        // Statistiques par opportunité (top 10)
        $opportuniteStats = DB::table('candidatures')
            ->join('opportunites', 'candidatures.opportunite_id', '=', 'opportunites.id')
            ->join('partenaires', 'opportunites.partenaire_id', '=', 'partenaires.id')
            ->select(
                'opportunites.id',
                'opportunites.titre',
                'partenaires.nom_organisation',
                DB::raw('COUNT(*) as candidatures_count'),
                DB::raw('SUM(CASE WHEN candidatures.status = \'pending\' THEN 1 ELSE 0 END) as pending_count'),
                DB::raw('SUM(CASE WHEN candidatures.status = \'accepted\' THEN 1 ELSE 0 END) as accepted_count')
            )
            ->groupBy('opportunites.id', 'opportunites.titre', 'partenaires.nom_organisation')
            ->orderBy('candidatures_count', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.candidatures.index', compact(
            'candidatures', 
            'opportunites', 
            'stats', 
            'opportuniteStats'
        ));
    }
    
    /**
     * Afficher les détails d'une candidature
     */
    public function show(Candidature $candidature)
    {
        $candidature->load(['bachelier', 'opportunite.partenaire']);
        
        return view('admin.candidatures.show', compact('candidature'));
    }
} 
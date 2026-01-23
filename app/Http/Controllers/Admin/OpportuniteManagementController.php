<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Opportunite;

class OpportuniteManagementController extends Controller
{
    /**
     * Afficher la liste des opportunités
     */
    public function index(Request $request)
    {
        $query = Opportunite::with(['partenaire', 'candidatures']);
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
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
        
        $opportunites = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => Opportunite::count(),
            'actives' => Opportunite::where('status', 'published')->count(),
            'en_attente' => Opportunite::where('status', 'draft')->count(),
            'expirees' => Opportunite::where('status', 'closed')->count(),
        ];
        
        return view('admin.opportunites.index', compact('opportunites', 'stats'));
    }
    
    /**
     * Afficher les détails d'une opportunité
     */
    public function show(Opportunite $opportunite)
    {
        $opportunite->load(['partenaire', 'candidatures.bachelier']);
        
        return view('admin.opportunites.show', compact('opportunite'));
    }
    
    /**
     * Modérer une opportunité
     */
    public function moderate(Request $request, Opportunite $opportunite)
    {
        $request->validate([
            'action' => 'required|in:publier,desactiver,supprimer'
        ]);
        
        switch($request->action) {
            case 'publier':
                $opportunite->update(['status' => 'published']);
                $message = 'Opportunité publiée avec succès.';
                return back()->with('success', $message);
                
            case 'desactiver':
                $opportunite->update(['status' => 'draft']);
                $message = 'Opportunité désactivée avec succès.';
                return back()->with('success', $message);
                
            case 'supprimer':
                $opportunite->delete();
                $message = 'Opportunité supprimée avec succès.';
                return redirect()->route('admin.opportunites.index')->with('success', $message);
                
            default:
                return back()->with('error', 'Action non reconnue.');
        }
    }
}

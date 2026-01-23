<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\Bachelier;
use App\Models\Opportunite;
use App\Models\Candidature;
use App\Models\LibraryResource;
use App\Models\LibraryFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BachelierController extends Controller
{
    public function dashboard()
    {
        $bachelier = Auth::user()->bachelier;
        
        // Statistiques pour le dashboard
        $stats = [
            'candidatures' => $bachelier->candidatures()->count(),
            'favoris' => $bachelier->favoris()->count(),
            'opportunites_disponibles' => Opportunite::where('status', 'published')->count(),
            'library_resources' => LibraryResource::where('is_active', true)->count(),
            'library_favorites' => LibraryFavorite::where('user_id', Auth::id())->count(),
        ];
        
        // Dernières opportunités
        $dernieres_opportunites = Opportunite::where('status', 'published')
            ->latest()
            ->limit(5)
            ->get();
        
        // Dernières candidatures
        $dernieres_candidatures = $bachelier->candidatures()
            ->with('opportunite')
            ->latest()
            ->limit(5)
            ->get();
            
        // Ressources de la bibliothèque récentes et populaires
        $ressources_recentes = LibraryResource::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->latest('published_at')
            ->limit(3)
            ->get();
        
        return view('bachelier.dashboard', compact('bachelier', 'stats', 'dernieres_opportunites', 'dernieres_candidatures', 'ressources_recentes'));
    }
    
    public function dotations()
    {
        $bachelier = Auth::user()->bachelier;
        
        if (!$bachelier->boursier_peub) {
            abort(403, 'Accès réservé aux boursiers PEUB.');
        }
        
        $dotations = $bachelier->dotationsAttributions()->with('inventaire')->latest()->get();
        
        return view('bachelier.dotations', compact('dotations'));
    }
} 
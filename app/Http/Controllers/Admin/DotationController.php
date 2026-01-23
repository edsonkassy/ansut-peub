<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dotation;
use App\Models\Bachelier;
use App\Models\DotationAttribution;
use App\Models\DotationInventaire;

class DotationController extends Controller
{
    /**
     * Afficher la liste des dotations
     */
    public function index(Request $request)
    {
        $query = DotationAttribution::with(['bachelier', 'inventaire']);
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('bachelier', function($bq) use ($search) {
                    $bq->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenoms', 'like', "%{$search}%");
                })
                ->orWhereHas('inventaire', function($iq) use ($search) {
                    $iq->where('nom', 'like', "%{$search}%");
                });
            });
        }
        
        $dotations = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => DotationAttribution::count(),
            'actives' => DotationAttribution::where('status', 'active')->count(),
            'montant_total' => DotationAttribution::where('dotations_attributions.status', 'active')
                                ->join('dotations_inventaire', 'dotations_attributions.inventaire_id', '=', 'dotations_inventaire.id')
                                ->sum('dotations_inventaire.valeur_unitaire'),
        ];
        
        return view('admin.dotations.index', compact('dotations', 'stats'));
    }
    
    /**
     * Afficher les détails d'une dotation
     */
    public function show(DotationAttribution $dotation)
    {
        $dotation->load(['bachelier', 'inventaire.fournisseur', 'attribuePar', 'mouvementsStock.effectuePar']);
        
        return view('admin.dotations.show', compact('dotation'));
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(DotationAttribution $dotation)
    {
        return view('admin.dotations.edit', compact('dotation'));
    }
    
    /**
     * Mettre à jour une dotation
     */
    public function update(Request $request, DotationAttribution $dotation)
    {
        $request->validate([
            'status' => 'required|in:active,suspendue,terminee,retournee',
            'raison_suspension' => 'nullable|string|required_if:status,suspendue',
        ]);
        
        switch ($request->status) {
            case 'active':
                $dotation->activer();
                break;
            case 'suspendue':
                $dotation->suspendre($request->raison_suspension);
                break;
            case 'terminee':
                $dotation->terminer();
                break;
            case 'retournee':
                $dotation->retourner();
                break;
        }
        
        return redirect()->route('admin.dotations.show', $dotation)
                        ->with('success', 'Statut de la dotation mis à jour avec succès.');
    }
    
    /**
     * Supprimer une dotation
     */
    public function destroy(DotationAttribution $dotation)
    {
        if ($dotation->estActive) {
            return back()->with('error', 'Vous ne pouvez pas supprimer une dotation active. Veuillez d\'abord la terminer ou la retourner.');
        }

        $dotation->delete();
        
        return redirect()->route('admin.dotations.index')
                        ->with('success', 'Attribution de dotation supprimée avec succès.');
    }
}

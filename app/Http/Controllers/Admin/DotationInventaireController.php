<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DotationInventaire;
use App\Models\DotationFournisseur;
use App\Models\DotationMouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DotationInventaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventaireItems = DotationInventaire::latest()->paginate(15);

        $stats = [
            'total_items'   => DotationInventaire::count(),
            'total_value'   => DotationInventaire::sum(\DB::raw('valeur_unitaire * stock_total')),
            'low_stock'     => DotationInventaire::whereColumn('stock_disponible', '<=', 'stock_minimum')->count(),
            'out_of_stock'  => DotationInventaire::where('stock_disponible', '=', 0)->count(),
        ];

        return view('admin.dotations.inventaire.index', compact('inventaireItems', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = DotationFournisseur::active()->get();
        return view('admin.dotations.inventaire.create', compact('fournisseurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'type_dotation' => 'required|in:ordinateur_portable,connexion_internet,abonnement_ia',
            'description' => 'nullable|string',
            'valeur_unitaire' => 'required|numeric|min:0',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'stock_total' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'fournisseur_id' => 'nullable|exists:dotations_fournisseurs,id',
            'date_achat' => 'nullable|date',
            'marque' => 'nullable|string|max:255',
            'modele' => 'nullable|string|max:255',
            'caracteristiques' => 'nullable|string|max:255',
            'duree_validite' => 'nullable|string|max:255',
            'status' => 'required|in:active,suspendu,archive',
        ]);

        $validatedData['stock_disponible'] = $validatedData['stock_total'];
        $validatedData['stock_attribue'] = 0;
        $validatedData['code_interne'] = 'INV-'.strtoupper(Str::slug($validatedData['nom'], '-')).'-'.Str::random(6);

        $inventaire = DotationInventaire::create($validatedData);

        if ($inventaire->stock_total > 0) {
            DotationMouvementStock::creerEntree(
                $inventaire->id,
                $inventaire->stock_total,
                'Stock initial',
                'Création de l\'article dans l\'inventaire',
                auth()->id()
            );
        }

        return redirect()->route('admin.dotations.inventaire.index')->with('success', 'Article ajouté à l\'inventaire avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DotationInventaire $inventaire)
    {
        $inventaire->load(['fournisseur', 'mouvementsStock.effectuePar']);
        
        return view('admin.dotations.inventaire.show', compact('inventaire'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DotationInventaire $inventaire)
    {
        $fournisseurs = DotationFournisseur::active()->get();
        return view('admin.dotations.inventaire.edit', compact('inventaire', 'fournisseurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DotationInventaire $inventaire)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'type_dotation' => 'required|in:ordinateur_portable,connexion_internet,abonnement_ia',
            'description' => 'nullable|string',
            'valeur_unitaire' => 'required|numeric|min:0',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'stock_total' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'fournisseur_id' => 'nullable|exists:dotations_fournisseurs,id',
            'date_achat' => 'nullable|date',
            'marque' => 'nullable|string|max:255',
            'modele' => 'nullable|string|max:255',
            'caracteristiques' => 'nullable|string|max:255',
            'duree_validite' => 'nullable|string|max:255',
            'status' => 'required|in:active,suspendu,archive',
        ]);

        $oldStockTotal = $inventaire->stock_total;
        $newStockTotal = (int)$validatedData['stock_total'];
        $stockDifference = $newStockTotal - $oldStockTotal;
        
        // On ne peut pas avoir un stock total inférieur au stock déjà attribué
        if ($newStockTotal < $inventaire->stock_attribue) {
            return back()->withErrors(['stock_total' => 'Le stock total ne peut pas être inférieur au nombre d\'articles déjà attribués (' . $inventaire->stock_attribue . ').'])->withInput();
        }
        
        // Mettre à jour les champs qui ne sont pas liés au stock
        $inventaire->update(collect($validatedData)->except(['stock_total'])->toArray());

        // Gérer l'ajustement de stock si nécessaire
        if ($stockDifference != 0) {
            DotationMouvementStock::creerAjustement(
                $inventaire->id,
                $stockDifference,
                'Ajustement manuel du stock',
                'Modification depuis le formulaire d\'édition.',
                auth()->id()
            );
        }

        return redirect()->route('admin.dotations.inventaire.index')->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DotationInventaire $inventaire)
    {
        if ($inventaire->attributions()->exists()) {
            return redirect()->route('admin.dotations.inventaire.index')
                ->with('error', 'Cet article ne peut pas être supprimé car il est ou a été attribué. Veuillez plutôt l\'archiver.');
        }

        // Supprimer les mouvements de stock associés avant de supprimer l'article
        $inventaire->mouvementsStock()->delete();

        $inventaire->delete();

        return redirect()->route('admin.dotations.inventaire.index')
            ->with('success', 'Article de l\'inventaire supprimé avec succès.');
    }
} 
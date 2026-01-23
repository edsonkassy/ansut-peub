<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DotationFournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DotationFournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DotationFournisseur::query();

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $fournisseurs = $query->orderBy('nom')->paginate(15);

        return view('admin.dotations.fournisseurs.index', compact('fournisseurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dotations.fournisseurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255|unique:dotations_fournisseurs,nom',
            'contact_email' => 'nullable|email|max:255',
            'contact_telephone' => 'nullable|string|max:20',
            'status' => 'required|in:active,suspendu,archive',
            'contrat' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB Max
        ]);

        if ($request->hasFile('contrat')) {
            $validatedData['contrat_url'] = $this->uploadContrat($request->file('contrat'));
        }

        DotationFournisseur::create($validatedData);

        return redirect()->route('admin.dotations.fournisseurs.index')
                         ->with('success', 'Fournisseur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DotationFournisseur $fournisseur)
    {
        $fournisseur->load('inventaires');
        
        return view('admin.dotations.fournisseurs.show', compact('fournisseur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DotationFournisseur $fournisseur)
    {
        return view('admin.dotations.fournisseurs.edit', compact('fournisseur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DotationFournisseur $fournisseur)
    {
        $validatedData = $request->validate([
            'nom' => ['required','string','max:255',Rule::unique('dotations_fournisseurs')->ignore($fournisseur->id)],
            'contact_email' => 'nullable|email|max:255',
            'contact_telephone' => 'nullable|string|max:20',
            'status' => 'required|in:active,suspendu,archive',
            'contrat' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB Max
        ]);

        if ($request->hasFile('contrat')) {
            // Supprimer l'ancien contrat s'il existe
            if ($fournisseur->contrat_url) {
                $this->deleteContrat($fournisseur->contrat_url);
            }
            $validatedData['contrat_url'] = $this->uploadContrat($request->file('contrat'));
        }

        $fournisseur->update($validatedData);

        return redirect()->route('admin.dotations.fournisseurs.index')
                         ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DotationFournisseur $fournisseur)
    {
        // Vérifier si le fournisseur est lié à des éléments d'inventaire
        if ($fournisseur->inventaire()->exists()) {
            return back()->with('error', 'Ce fournisseur ne peut pas être supprimé car il est lié à des articles en inventaire.');
        }
        
        // Supprimer le contrat s'il existe
        if ($fournisseur->contrat_url) {
            $this->deleteContrat($fournisseur->contrat_url);
        }

        $fournisseur->delete();

        return redirect()->route('admin.dotations.fournisseurs.index')
                         ->with('success', 'Fournisseur supprimé avec succès.');
    }

    /**
     * Gère l'upload du fichier de contrat.
     */
    private function uploadContrat($file): string
    {
        return $file->store('contrats_fournisseurs', 'public');
    }

    /**
     * Gère la suppression du fichier de contrat.
     */
    private function deleteContrat(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}

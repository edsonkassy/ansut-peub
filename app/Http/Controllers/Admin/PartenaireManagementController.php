<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partenaire;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\LogoService;
use Illuminate\Support\Facades\Log;

class PartenaireManagementController extends Controller
{
    protected $logoService;

    public function __construct(LogoService $logoService)
    {
        $this->logoService = $logoService;
    }

    /**
     * Afficher la liste des partenaires
     */
    public function index(Request $request)
    {
        $query = Partenaire::with('user');
        
        // Filtres
        if ($request->filled('status_verification')) {
            $query->where('status_verification', $request->status_verification);
        }
        
        if ($request->filled('type_organisation')) {
            $query->where('type_organisation', $request->type_organisation);
        }
        
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_organisation', 'like', "%{$search}%")
                  ->orWhere('personne_contact_nom', 'like', "%{$search}%")
                  ->orWhere('personne_contact_email', 'like', "%{$search}%");
            });
        }
        
        $partenaires = $query->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => Partenaire::count(),
            'verifies' => Partenaire::where('status_verification', 'verified')->count(),
            'en_attente' => Partenaire::where('status_verification', 'pending')->count(),
            'rejetes' => Partenaire::where('status_verification', 'rejected')->count(),
        ];
        
        // Options pour les filtres
        $types = Partenaire::select('type_organisation')->distinct()->whereNotNull('type_organisation')->pluck('type_organisation');
        $regions = Partenaire::select('region')->distinct()->whereNotNull('region')->pluck('region');
        
        return view('admin.partenaires.index', compact('partenaires', 'stats', 'types', 'regions'));
    }
    
    /**
     * Afficher les détails d'un partenaire
     */
    public function show(Partenaire $partenaire)
    {
        $partenaire->load(['user', 'opportunites.candidatures', 'typesOpportunites']);
        
        return view('admin.partenaires.show', compact('partenaire'));
    }
    
    /**
     * Toggle le statut d'un partenaire (vérifié/non vérifié)
     */
    public function toggleStatus(Partenaire $partenaire)
    {
        if ($partenaire->status_verification === 'verified') {
            // Si le partenaire est vérifié, on le désactive
            $partenaire->update([
                'status_verification' => 'pending',
                'date_verification' => null
            ]);
            $message = 'Le partenaire a été désactivé avec succès.';
        } else {
            // Si le partenaire n'est pas vérifié, on l'active
            $partenaire->update([
                'status_verification' => 'verified',
                'date_verification' => now()
            ]);
            $message = 'Le partenaire a été activé avec succès.';
        }

        return back()->with('success', $message);
    }
    
    /**
     * Rejeter un partenaire
     */
    public function reject(Request $request, Partenaire $partenaire)
    {
        $partenaire->update([
            'status_verification' => 'rejected',
            'date_verification' => now()
        ]);
        
        return back()->with('success', 'Partenaire rejeté avec succès.');
    }
    
    /**
     * Export des partenaires
     */
    public function export(Request $request)
    {
        // TODO: Implémenter l'export
        return back()->with('info', 'Fonctionnalité d\'export en cours de développement.');
    }

    /**
     * Afficher le formulaire d'édition d'un partenaire
     */
    public function edit(Partenaire $partenaire)
    {
        return view('admin.partenaires.edit', compact('partenaire'));
    }

    /**
     * Mettre à jour un partenaire
     */
    public function update(Request $request, Partenaire $partenaire)
    {
        // Vérifier si le logo est un objet vide et le retirer de la requête
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if (!$file->isValid()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['logo' => 'Le fichier logo est invalide.']);
            }
        } else {
            $request->request->remove('logo');
        }

        $validated = $request->validate([
            'nom_organisation' => 'required|string|max:255',
            'type_organisation' => 'required|in:entreprise,institution_academique,ong,gouvernement',
            'secteur_activite' => 'nullable|string|max:255',
            'region' => 'required|string|max:255',
            'commune' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB max
            'personne_contact_nom' => 'required|string|max:255',
            'personne_contact_fonction' => 'required|string|max:255',
            'personne_contact_email' => 'required|email|max:255',
            'personne_contact_telephone' => 'required|string|max:20',
            'types_opportunites' => 'array'
        ]);

        try {
            DB::beginTransaction();

            // Traiter le logo si un nouveau fichier est uploadé
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $result = $this->logoService->processAndStoreLogo($request->file('logo'));
                
                if (!$result['success']) {
                    throw new \Exception($result['error']);
                }

                // Supprimer l'ancien logo
                if ($partenaire->logo) {
                    $this->logoService->deleteLogo($partenaire->logo);
                }
                
                $validated['logo'] = $result['path'];
            } else {
                // Si pas de nouveau logo, garder l'ancien
                unset($validated['logo']);
            }

            // Mettre à jour les informations du partenaire
            $partenaire->update($validated);

            // Mettre à jour les types d'opportunités autorisés
            if (isset($validated['types_opportunites'])) {
                $partenaire->typesOpportunites()->delete();
                foreach ($validated['types_opportunites'] as $type) {
                    $partenaire->typesOpportunites()->create(['type_opportunite' => $type]);
                }
            }

            DB::commit();

            return redirect()->route('admin.partenaires.show', $partenaire)
                ->with('success', 'Partenaire mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log l'erreur
            Log::error('Erreur lors de la mise à jour du partenaire', [
                'partenaire_id' => $partenaire->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\Bachelier;
use App\Models\Dotation;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function show()
    {
        $bachelier = Auth::user()->bachelier;
        
        // Charger les dotations si boursier PEUB
        $dotations = collect();
        if ($bachelier->boursier_peub) {
            $dotations = Dotation::where('bachelier_id', $bachelier->id)
                ->where('status', 'active')
                ->get();
        }

        return view('bachelier.profile', compact('bachelier', 'dotations'));
    }

    public function update(Request $request)
    {
        $bachelier = Auth::user()->bachelier;

        $validated = $request->validate([
            // Informations personnelles (non modifiables après création)
            'nom' => 'sometimes|string|max:255',
            'prenoms' => 'sometimes|string|max:255',
            'date_naissance' => 'sometimes|date|before_or_equal:2020-12-31|after:1990-01-01',
            'lieu_naissance' => 'sometimes|string|max:255',
            'sexe' => 'sometimes|in:M,F',
            'region' => 'sometimes|string|max:255',
            
            // Contact (modifiable)
            'telephone_eleve' => 'required|string|max:20',
            'telephone_parent' => 'required|string|max:20',
            'email_eleve' => 'required|email|max:255',
            'email_parent' => 'required|email|max:255',
            'commune' => 'sometimes|string|max:255',
            
            // Informations académiques (non modifiables après création)
            'matricule_bac' => 'sometimes|string|max:50',
            'serie_bac' => 'sometimes|in:C,E,D,A1,A2,F1,F2,F3,F4,F5,F6,F7,F8,G1,G2,G3,BT,BP',
            'note_bac' => 'sometimes|numeric|min:0|max:400',
            'mention' => 'sometimes|in:passable,assez_bien,bien,tres_bien',
            'etablissement_nom' => 'sometimes|string|max:255',
            'annee_bac' => 'sometimes|integer|min:2020|max:2025',
            
            // Compétences et motivation (modifiable)
            'motivation' => 'nullable|string|max:5000',
            'competences' => 'nullable|string|max:1000',
            'langues' => 'nullable|string|max:1000',
            
            // Fichiers
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // 2MB
            'cv_path' => 'nullable|file|mimes:pdf|max:10240', // 10MB
        ], [
            // Messages personnalisés
            'telephone_eleve.required' => 'Le téléphone de l\'élève est obligatoire.',
            'telephone_parent.required' => 'Le téléphone du parent est obligatoire.',
            'email_eleve.required' => 'L\'email de l\'élève est obligatoire.',
            'email_eleve.email' => 'L\'email de l\'élève doit être valide.',
            'email_parent.required' => 'L\'email du parent est obligatoire.',
            'email_parent.email' => 'L\'email du parent doit être valide.',
            'note_bac.max' => 'La note BAC doit être sur 400 points maximum.',
            'serie_bac.in' => 'Veuillez sélectionner une série BAC valide.',
            'annee_bac.max' => 'L\'année BAC ne peut pas dépasser 2025.',
            'photo.max' => 'La photo ne doit pas dépasser 2 MB.',
            'cv_path.max' => 'Le CV ne doit pas dépasser 10 MB.',
            'cv_path.mimes' => 'Le CV doit être au format PDF.',
        ]);

        // Mise à jour des informations modifiables
        $updateData = [
            'telephone_eleve' => $validated['telephone_eleve'],
            'telephone_parent' => $validated['telephone_parent'],
            'email_eleve' => $validated['email_eleve'],
            'email_parent' => $validated['email_parent'],
            'motivation' => $validated['motivation'] ?? null,
            'competences' => isset($validated['competences']) ? (is_array($validated['competences']) ? $validated['competences'] : array_map('trim', explode(',', $validated['competences']))) : null,
            'langues' => isset($validated['langues']) ? (is_array($validated['langues']) ? $validated['langues'] : array_map('trim', explode(',', $validated['langues']))) : null,
        ];
        
        // Ajouter les champs optionnels si présents
        if (isset($validated['commune'])) {
            $updateData['commune'] = $validated['commune'];
        }
        
        $bachelier->update($updateData);

        // Traitement de la photo avec optimisation
        if ($request->hasFile('photo')) {
            $photoPath = $this->imageService->optimizeAndStore(
                $request->file('photo'), 
                'photos'
            );
            $bachelier->update(['photo' => $photoPath]);
        }

        // Traitement du CV
        if ($request->hasFile('cv_path')) {
            $cvPath = $request->file('cv_path')->store('cv', 'public');
            $bachelier->update(['cv_path' => $cvPath]);
        }

        return redirect()->route('bachelier.profile')
            ->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    public function updateStatus(Request $request)
    {
        $bachelier = Auth::user()->bachelier;

        $request->validate([
            'status_profil' => 'required|in:complet,verifie,en_attente',
        ]);

        $bachelier->update([
            'status_profil' => $request->status_profil,
            'date_verification' => now(),
        ]);

        return response()->json([
            'success' => 'Statut du profil mis à jour avec succès.',
            'status' => $request->status_profil
        ]);
    }

    public function downloadDocument($type)
    {
        $bachelier = Auth::user()->bachelier;
        
        $documentPath = null;
        $filename = null;

        switch ($type) {
            case 'piece_identite':
                $documentPath = $bachelier->piece_identite_file;
                $filename = 'piece_identite_' . $bachelier->nom . '.jpg';
                break;
            case 'collante_bac':
                $documentPath = $bachelier->collante_bac_file;
                $filename = 'collante_bac_' . $bachelier->nom . '.jpg';
                break;
            case 'cv':
                $documentPath = $bachelier->cv_path;
                $filename = 'cv_' . $bachelier->nom . '.pdf';
                break;
            default:
                abort(404);
        }

        if (!$documentPath || !file_exists(storage_path('app/public/' . $documentPath))) {
            abort(404);
        }

        return response()->download(storage_path('app/public/' . $documentPath), $filename);
    }

    public function exportData()
    {
        $bachelier = Auth::user()->bachelier;
        
        $data = [
            'Informations personnelles' => [
                'Nom' => $bachelier->nom,
                'Prénoms' => $bachelier->prenoms,
                'Date de naissance' => $bachelier->date_naissance?->format('d/m/Y'),
                'Lieu de naissance' => $bachelier->lieu_naissance,
                'Sexe' => $bachelier->sexe === 'M' ? 'Masculin' : 'Féminin',
                'Région' => $bachelier->region,
                'Commune' => $bachelier->commune,
            ],
            'Contact' => [
                'Téléphone élève' => $bachelier->telephone_eleve,
                'Téléphone parent' => $bachelier->telephone_parent,
                'Email élève' => $bachelier->email_eleve,
                'Email parent' => $bachelier->email_parent,
            ],
            'Informations académiques' => [
                'Matricule BAC' => $bachelier->matricule_bac,
                'Série BAC' => $bachelier->serie_bac,
                'Note BAC' => $bachelier->note_bac,
                'Mention' => $bachelier->mention,
                'Établissement' => $bachelier->etablissement_nom,
                'Type établissement' => $bachelier->etablissement_type,
                'Année BAC' => $bachelier->annee_bac,
            ],
            'Situation socio-économique' => [
                'Pensionnaire/Internat' => $bachelier->pensionnaire_internat ? 'Oui' : 'Non',
                'Bourse scolaire lycée' => $bachelier->bourse_scolaire_lycee ? 'Oui' : 'Non',
                'Profession père' => $bachelier->profession_pere,
                'Profession mère' => $bachelier->profession_mere,
                'Situations particulières' => $bachelier->situations_particulieres ? implode(', ', $bachelier->situations_particulieres) : 'Aucune',
                'Possède ordinateur' => $bachelier->possede_ordinateur ? 'Oui' : 'Non',
                'Connexion internet' => $bachelier->connexion_internet,
            ],
            'Motivations' => [
                'Motivation' => $bachelier->motivation,
                'Compétences' => $bachelier->competences ? implode(', ', $bachelier->competences) : 'Aucune',
                'Langues' => $bachelier->langues ? implode(', ', $bachelier->langues) : 'Aucune',
            ],
            'Statut PEUB' => [
                'Boursier PEUB' => $bachelier->boursier_peub ? 'Oui' : 'Non',
                'Date d\'intégration PEUB' => $bachelier->date_integration_peub?->format('d/m/Y'),
                'Statut candidature' => $bachelier->status_candidature,
                'Statut profil' => $bachelier->status_profil,
                'Score final PEUB' => $bachelier->score_final_peub,
                'Rang PEUB' => $bachelier->rang_peub,
            ]
        ];

        $filename = 'profil_' . $bachelier->nom . '_' . date('Y-m-d') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    public function destroyDocument($type)
    {
        $bachelier = Auth::user()->bachelier;
        
        $documentField = null;
        switch ($type) {
            case 'piece_identite':
                $documentField = 'piece_identite_file';
                break;
            case 'collante_bac':
                $documentField = 'collante_bac_file';
                break;
            case 'cv':
                $documentField = 'cv_path';
                break;
            case 'photo':
                $documentField = 'photo';
                break;
            default:
                abort(404);
        }

        if ($bachelier->$documentField && file_exists(storage_path('app/public/' . $bachelier->$documentField))) {
            unlink(storage_path('app/public/' . $bachelier->$documentField));
        }

        $bachelier->update([$documentField => null]);

        return redirect()->route('bachelier.profile')
            ->with('success', 'Document supprimé avec succès.');
    }
}

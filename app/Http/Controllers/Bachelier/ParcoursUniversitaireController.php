<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\ParcoursUniversitaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\RegionHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ParcoursUniversitaireController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bachelier = auth()->user()->bachelier;
        $parcours = $bachelier->parcoursUniversitaires()->orderBy('annee_academique', 'desc')->get();

        return view('bachelier.parcours.index', compact('parcours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $niveaux = $this->getNiveaux();
        $statuts = ['en_cours', 'termine', 'abandonne'];
        $pays = RegionHelper::getCountries();
        $mentions = ['Passable', 'Assez Bien', 'Bien', 'Très Bien', 'Excellent'];

        $currentYear = date('Y');
        $annees = [];
        for ($i = -1; $i <= 1; $i++) {
            $year = $currentYear + $i;
            $annees[] = $year . '-' . ($year + 1);
        }

        return view('bachelier.parcours.create', compact('niveaux', 'statuts', 'pays', 'mentions', 'annees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateRequest($request);
        
        $bachelier = auth()->user()->bachelier;
        $data['bachelier_id'] = $bachelier->id;

        // Mapper le niveau détaillé au niveau de base pour la DB
        $data['niveau'] = $this->mapNiveauToBase($data['niveau']);

        if ($request->hasFile('attestation_admission_file')) {
            $data['attestation_admission_file'] = $request->file('attestation_admission_file')->store('attestations_admission', 'public');
        }

        ParcoursUniversitaire::create($data);

        return redirect()->route('bachelier.parcours.index')->with('success', 'Parcours ajouté avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParcoursUniversitaire $parcour)
    {
        $this->authorize('update', $parcour);
        
        $niveaux = $this->getNiveaux();
        $statuts = ['en_cours', 'termine', 'abandonne'];
        $pays = RegionHelper::getCountries();
        $mentions = ['Passable', 'Assez Bien', 'Bien', 'Très Bien', 'Excellent'];

        $currentYear = date('Y');
        $annees = [];
        for ($i = -1; $i <= 1; $i++) {
            $year = $currentYear + $i;
            $annees[] = $year . '-' . ($year + 1);
        }

        return view('bachelier.parcours.edit', compact('parcour', 'niveaux', 'statuts', 'pays', 'mentions', 'annees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParcoursUniversitaire $parcour)
    {
        $this->authorize('update', $parcour);

        $data = $this->validateRequest($request, $parcour->id);

        // Mapper le niveau détaillé au niveau de base pour la DB
        $data['niveau'] = $this->mapNiveauToBase($data['niveau']);

        if ($request->hasFile('attestation_admission_file')) {
            // Supprimer l'ancien fichier s'il existe
            if ($parcour->attestation_admission_file) {
                Storage::disk('public')->delete($parcour->attestation_admission_file);
            }
            $data['attestation_admission_file'] = $request->file('attestation_admission_file')->store('attestations_admission', 'public');
        }

        $parcour->update($data);

        return redirect()->route('bachelier.parcours.index')->with('success', 'Parcours mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParcoursUniversitaire $parcour)
    {
        $this->authorize('delete', $parcour);

        if ($parcour->attestation_admission_file) {
            Storage::disk('public')->delete($parcour->attestation_admission_file);
        }
        
        $parcour->delete();

        return redirect()->route('bachelier.parcours.index')->with('success', 'Parcours supprimé avec succès.');
    }

    private function validateRequest(Request $request, $parcoursId = null)
    {
        $niveauxList = array_merge(...array_values($this->getNiveaux()));

        $rules = [
            'universite_nom' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'niveau' => 'required|in:' . implode(',', $niveauxList),
            'annee_academique' => 'required|string|max:20',
            'performance' => 'required|numeric|min:0|max:20',
            'mention' => 'required|string|in:Passable,Assez Bien,Bien,Très Bien,Excellent',
            'statut' => 'required|in:en_cours,termine,abandonne',
        ];

        $fileRule = 'file|mimes:pdf,jpg,jpeg,png|max:10240';

        if ($parcoursId) {
            // On update, file is not required
            $rules['attestation_admission_file'] = 'nullable|' . $fileRule;
        } else {
            // On create, file is required
            $rules['attestation_admission_file'] = 'required|' . $fileRule;
        }
        
        return $request->validate($rules);
    }

    private function getNiveaux()
    {
        return [
            'BTS' => [
                'BTS 1ère année',
                'BTS 2ème année',
            ],
            'Licence' => [
                'Licence 1ère année',
                'Licence 2ème année',
                'Licence 3ème année',
            ],
            'Master' => [
                'Master 1ère année',
                'Master 2ème année',
            ],
            'Doctorat' => [
                'Doctorat',
            ],
            'Autre' => [
                'Autre',
            ]
        ];
    }

    private function mapNiveauToBase($niveauDetaille)
    {
        if (strpos($niveauDetaille, 'BTS') !== false) {
            return 'bts';
        } elseif (strpos($niveauDetaille, 'Licence') !== false) {
            return 'licence';
        } elseif (strpos($niveauDetaille, 'Master') !== false) {
            return 'master';
        } elseif (strpos($niveauDetaille, 'Doctorat') !== false) {
            return 'doctorat';
        } else {
            return 'autre';
        }
    }
}

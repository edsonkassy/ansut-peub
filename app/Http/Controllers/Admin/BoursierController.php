<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bachelier;
use App\Models\User;
use App\Helpers\RegionHelper;
use Illuminate\Support\Facades\DB;

class BoursierController extends Controller
{
    /**
     * Afficher la page des boursiers avec la carte interactive
     */
    public function index(Request $request)
    {
        // Récupérer les filtres
        $selectedGenders = $request->get('sexe', ['F', 'M']);
        if (is_string($selectedGenders)) {
            $selectedGenders = [$selectedGenders];
        }
        
        // Récupérer les statistiques des boursiers
        $stats = [
            'total_boursiers' => Bachelier::where('boursier_peub', true)->count(),
            'total_filles' => Bachelier::where('boursier_peub', true)->where('sexe', 'F')->count(),
            'total_garcons' => Bachelier::where('boursier_peub', true)->where('sexe', 'M')->count(),
            'total_actifs' => Bachelier::where('boursier_peub', true)
                ->whereHas('user', function($query) {
                    $query->where('status', 'active');
                })->count(),
        ];

        // Récupérer les données des boursiers par région avec coordonnées
        $boursiers_data = $this->getBoursiersWithCoordinates($selectedGenders);

        // Récupérer les statistiques par région
        $stats_par_region = $this->getStatsParRegion();

        return view('admin.boursiers.index', compact('stats', 'boursiers_data', 'stats_par_region', 'selectedGenders'));
    }

    /**
     * Obtenir les boursiers avec leurs coordonnées géographiques organisés par région
     */
    private function getBoursiersWithCoordinates($selectedGenders = ['F', 'M'])
    {
        $boursiers = Bachelier::with('user')
            ->where('boursier_peub', true)
            ->whereIn('sexe', $selectedGenders)
            ->get();

        $data = [];
        foreach ($boursiers as $boursier) {
            $commune = $boursier->commune ?? 'Abidjan';
            $region = $boursier->region ?? 'Abidjan';
            
            // Normaliser la région (mapper les anciennes vers les nouvelles)
            $normalizedRegion = RegionHelper::normalizeRegion($region);
            
            // Obtenir les coordonnées depuis le helper
            $coords = RegionHelper::getCoordinates($commune);

            $data[] = [
                'id' => $boursier->id,
                'name' => $boursier->nom_complet,
                'gender' => $boursier->sexe === 'F' ? 'female' : 'male',
                'commune' => $commune,
                'region' => $normalizedRegion, // Utiliser la région normalisée
                'serie' => $boursier->serie_bac ?? 'N/A',
                'note' => $boursier->note_bac ?? 0,
                'annee' => $boursier->annee_bac ?? 2025,
                'status' => $boursier->user->status ?? 'active',
                'lng' => $coords[0],
                'lat' => $coords[1],
                'etablissement' => $boursier->etablissement_nom ?? 'N/A',
                'phone' => $boursier->telephone_eleve ?? 'N/A',
                'email' => $boursier->email_eleve ?? $boursier->user->email,
            ];
        }

        // Organiser par région
        $result = [];
        $regions = RegionHelper::getRegions();
        
        foreach ($regions as $region_key => $region_name) {
            $result[$region_key] = collect($data)->where('region', $region_key)->values()->all();
        }

        // Ajouter "Toutes" pour afficher tous les boursiers
        $result['Toutes'] = $data;

        return $result;
    }

    /**
     * Obtenir les statistiques par région
     */
    private function getStatsParRegion()
    {
        $stats = Bachelier::where('boursier_peub', true)
            ->select('region', 
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN sexe = \'F\' THEN 1 ELSE 0 END) as filles'),
                DB::raw('SUM(CASE WHEN sexe = \'M\' THEN 1 ELSE 0 END) as garcons')
            )
            ->groupBy('region')
            ->orderBy('total', 'desc')
            ->get();

        // Normaliser les régions et regrouper les statistiques
        $normalizedStats = [];
        foreach ($stats as $stat) {
            $normalizedRegion = RegionHelper::normalizeRegion($stat->region);
            
            if (!isset($normalizedStats[$normalizedRegion])) {
                $normalizedStats[$normalizedRegion] = (object) [
                    'region' => $normalizedRegion,
                    'total' => 0,
                    'filles' => 0,
                    'garcons' => 0
                ];
            }
            
            $normalizedStats[$normalizedRegion]->total += $stat->total;
            $normalizedStats[$normalizedRegion]->filles += $stat->filles;
            $normalizedStats[$normalizedRegion]->garcons += $stat->garcons;
        }

        return collect($normalizedStats)->keyBy('region');
    }
}

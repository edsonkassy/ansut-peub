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

        $regionCoords = RegionHelper::getRegionCoordinates();
        $regions = RegionHelper::getRegions();
        
        $grouped = [];
        foreach ($boursiers as $boursier) {
            $region = $boursier->region ?? 'Abidjan';
            $normalizedRegion = RegionHelper::normalizeRegion($region);
            $coords = $regionCoords[$normalizedRegion] ?? [-4.0167, 5.3167];
            
            if (!isset($grouped[$normalizedRegion])) {
                $grouped[$normalizedRegion] = [
                    'region' => $normalizedRegion,
                    'region_label' => $regions[$normalizedRegion] ?? $normalizedRegion,
                    'lng' => $coords[0],
                    'lat' => $coords[1],
                    'total' => 0,
                    'filles' => 0,
                    'garcons' => 0,
                    'boursiers' => [],
                ];
            }
            
            $grouped[$normalizedRegion]['total']++;
            if ($boursier->sexe === 'F') $grouped[$normalizedRegion]['filles']++;
            else $grouped[$normalizedRegion]['garcons']++;
            
            $grouped[$normalizedRegion]['boursiers'][] = [
                'id' => $boursier->id,
                'name' => $boursier->nom_complet,
                'gender' => $boursier->sexe === 'F' ? 'female' : 'male',
                'commune' => $boursier->commune ?? 'N/A',
                'serie' => $boursier->serie_bac ?? 'N/A',
                'etablissement' => $boursier->etablissement_nom ?? 'N/A',
                'email' => $boursier->email_eleve ?? ($boursier->user->email ?? 'N/A'),
                'status' => $boursier->user->status ?? 'active',
            ];
        }

        return array_values($grouped);
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

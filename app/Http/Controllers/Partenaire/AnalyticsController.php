<?php

namespace App\Http\Controllers\Partenaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bachelier;
use App\Models\Opportunite;
use App\Models\Candidature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Afficher le tableau de bord analytics des bacheliers et boursiers
     */
    public function index()
    {
        $partenaire = auth()->user()->partenaire;
        $opportunites = Opportunite::where('partenaire_id', $partenaire->id)->pluck('id');
        $candidatures = Candidature::whereIn('opportunite_id', $opportunites)->with('bachelier')->get();
        $bacheliers = $candidatures->pluck('bachelier')->unique('id');
        
        $stats = [
            'total_bacheliers' => $bacheliers->count(),
            'bacheliers_verifies' => $bacheliers->where('status_profil', 'verifie')->count(),
            'bacheliers_en_attente' => $bacheliers->where('status_profil', 'incomplet')->count(),
            'total_boursiers' => $bacheliers->where('boursier_peub', true)->count(),
        ];

        // Distribution par toutes les régions (incluant celles avec 0 bacheliers)
        $regions_completes = [
            'Abidjan', 'Yamoussoukro', 'Agnéby‑Tiassa', 'Bafing', 'Bagoué', 'Bélier', 'Béré', 
            'Bounkani', 'Cavally', 'Folon', 'Gbêkê', 'Gbôklé', 'Gôh', 'Gontougo', 'Grands‑Ponts', 
            'Guémon', 'Hambol', 'Haut‑Sassandra', 'Iffou', 'Indénié‑Djuablin', 'Kabadougou', 
            'La Mé', 'LôhDjiboua', 'Marahoué', 'Moronou', 'Nawa', 'Nzi', 'Poro', 'San‑Pédro', 
            'Sud‑Comoé', 'Tchologo', 'Tonkpi', 'Worodougou'
        ];

        $stats_par_region = $bacheliers->groupBy('region')->map->count();

        // Créer la liste complète avec 0 pour les régions vides
        $top_regions = collect($regions_completes)->map(function ($region) use ($stats_par_region) {
            return (object) [
                'region' => $region,
                'count' => $stats_par_region->get($region, 0)
            ];
        })->sortByDesc('count');

        // Statistiques par commune
        $stats_par_commune = $bacheliers->groupBy('commune')->map->count()->sortDesc()->take(15);

        // Répartition par genre
        $stats_par_genre = $bacheliers->groupBy('sexe')->map(fn($group) => $group->count());

        // Répartition par âge (calculé à partir de date_naissance)
        $stats_par_age = $bacheliers->map(function ($b) {
            $age = now()->diffInYears($b->date_naissance);
            if ($age < 18) return 'Moins de 18 ans';
            if ($age <= 20) return '18-20 ans';
            if ($age <= 23) return '21-23 ans';
            if ($age <= 26) return '24-26 ans';
            return 'Plus de 26 ans';
        })->groupBy(fn($item) => $item)->map(fn($group) => $group->count());

        // Statistiques des opportunités par pays
        $opportunites_par_pays = Opportunite::where('partenaire_id', $partenaire->id)
            ->select('pays', DB::raw('count(*) as count'))
            ->groupBy('pays')
            ->get()
            ->sortByDesc('count');

        // Bacheliers récents
        $bacheliers_recents = $bacheliers->sortByDesc('created_at')->take(10);

        // Statistiques mensuelles des inscriptions
        $inscriptions_mensuelles = [];
        for ($i = 11; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $count = Bachelier::whereYear('created_at', $mois->year)
                ->whereMonth('created_at', $mois->month)
                ->count();
            $inscriptions_mensuelles[$mois->format('M Y')] = $count;
        }

        // Données pour la carte Mapbox avec plus de villes ivoiriennes et bacheliers simulés
        $villes_ci = [
            // Grandes villes
            ['nom' => 'Abidjan', 'lng' => -4.0167, 'lat' => 5.3167],
            ['nom' => 'Bouaké', 'lng' => -5.0305, 'lat' => 7.6922],
            ['nom' => 'Yamoussoukro', 'lng' => -5.2767, 'lat' => 6.8205],
            ['nom' => 'Korhogo', 'lng' => -5.6283, 'lat' => 9.4583],
            ['nom' => 'San-Pédro', 'lng' => -6.6370, 'lat' => 4.7467],
            ['nom' => 'Daloa', 'lng' => -6.4442, 'lat' => 6.8770],
            ['nom' => 'Man', 'lng' => -7.5539, 'lat' => 7.4122],
            ['nom' => 'Gagnoa', 'lng' => -5.9500, 'lat' => 6.1333],
            ['nom' => 'Divo', 'lng' => -5.3572, 'lat' => 5.8397],
            ['nom' => 'Abengourou', 'lng' => -3.4972, 'lat' => 6.7289],
            
            // Villes moyennes
            ['nom' => 'Grand-Bassam', 'lng' => -3.7378, 'lat' => 5.2111],
            ['nom' => 'Agboville', 'lng' => -4.2139, 'lat' => 5.9267],
            ['nom' => 'Adzopé', 'lng' => -3.8633, 'lat' => 6.1089],
            ['nom' => 'Anyama', 'lng' => -4.0522, 'lat' => 5.4933],
            ['nom' => 'Bingerville', 'lng' => -3.8956, 'lat' => 5.3583],
            ['nom' => 'Grand-Lahou', 'lng' => -5.2428, 'lat' => 5.2506],
            ['nom' => 'Tiassalé', 'lng' => -4.8211, 'lat' => 5.8978],
            ['nom' => 'Lakota', 'lng' => -5.8508, 'lat' => 5.8508],
            ['nom' => 'Aboisso', 'lng' => -3.2067, 'lat' => 5.4706],
            ['nom' => 'Dabou', 'lng' => -4.3792, 'lat' => 5.3253],
            
            // Villes du Nord
            ['nom' => 'Katiola', 'lng' => -5.1000, 'lat' => 8.1333],
            ['nom' => 'Ferkessédougou', 'lng' => -5.1967, 'lat' => 9.6000],
            ['nom' => 'Tingréla', 'lng' => -5.4667, 'lat' => 10.3667],
            ['nom' => 'Kong', 'lng' => -4.6083, 'lat' => 9.1500],
            ['nom' => 'Boundiali', 'lng' => -6.4833, 'lat' => 9.5167],
            ['nom' => 'Mankono', 'lng' => -6.1858, 'lat' => 8.0583],
            ['nom' => 'Béoumi', 'lng' => -5.5833, 'lat' => 7.6667],
            ['nom' => 'Sakassou', 'lng' => -5.2917, 'lat' => 7.4500],
            ['nom' => 'M\'Bahiakro', 'lng' => -4.3417, 'lat' => 7.4583],
            ['nom' => 'Dimbokro', 'lng' => -4.7058, 'lat' => 6.6475],
            
            // Villes de l'Est
            ['nom' => 'Bondoukou', 'lng' => -2.8000, 'lat' => 8.0333],
            ['nom' => 'Séguéla', 'lng' => -6.6733, 'lat' => 7.9611],
            ['nom' => 'Odienné', 'lng' => -7.5667, 'lat' => 9.5086],
            ['nom' => 'Sassandra', 'lng' => -6.0919, 'lat' => 4.9500],
            ['nom' => 'Soubré', 'lng' => -6.5944, 'lat' => 5.7856],
            ['nom' => 'Issia', 'lng' => -6.4939, 'lat' => 6.4933],
            ['nom' => 'Bangolo', 'lng' => -7.4861, 'lat' => 6.8389],
            ['nom' => 'Danané', 'lng' => -8.1500, 'lat' => 7.2667],
            ['nom' => 'Biankouma', 'lng' => -7.7389, 'lat' => 7.7453],
            ['nom' => 'Touba', 'lng' => -7.6833, 'lat' => 8.2833],
            ['nom' => 'Bouna', 'lng' => -2.9833, 'lat' => 9.2667],
            ['nom' => 'Tanda', 'lng' => -3.1692, 'lat' => 7.8008],
            ['nom' => 'Koun-Fao', 'lng' => -3.2167, 'lat' => 9.6167],
            ['nom' => 'Doropo', 'lng' => -3.3500, 'lat' => 9.7833],
            ['nom' => 'Nassian', 'lng' => -4.4833, 'lat' => 10.5000],
            ['nom' => 'Minignan', 'lng' => -6.2500, 'lat' => 10.4333],
            ['nom' => 'Samatiguila', 'lng' => -5.8000, 'lat' => 9.8000],
            ['nom' => 'Ouangolodougou', 'lng' => -5.1500, 'lat' => 9.9667],
            ['nom' => 'Niellé', 'lng' => -6.7000, 'lat' => 9.4500],
            ['nom' => 'Sinématiali', 'lng' => -5.3833, 'lat' => 9.5833],
            
            // Villes du Centre
            ['nom' => 'Oumé', 'lng' => -5.4169, 'lat' => 6.3831],
            ['nom' => 'Sinfra', 'lng' => -5.9108, 'lat' => 6.6219],
            ['nom' => 'Zuénoula', 'lng' => -6.0508, 'lat' => 7.4333],
            ['nom' => 'Vavoua', 'lng' => -6.4781, 'lat' => 7.3833],
            ['nom' => 'Guiglo', 'lng' => -7.4978, 'lat' => 6.5439],
            ['nom' => 'Duékoué', 'lng' => -7.3500, 'lat' => 6.7333],
            ['nom' => 'Bloléquin', 'lng' => -8.0167, 'lat' => 6.5833],
            ['nom' => 'Tabou', 'lng' => -7.3500, 'lat' => 4.4231],
            ['nom' => 'Toulepleu', 'lng' => -8.4167, 'lat' => 6.5833],
            ['nom' => 'Tai', 'lng' => -7.4667, 'lat' => 5.8667],
        ];

        // Récupérer les vrais bacheliers de la base de données
        $bacheliers_reels = Bachelier::select('nom', 'prenoms', 'region', 'commune', 'boursier_peub', 'status_profil')
            ->get();

        // Créer des bacheliers simulés supplémentaires pour avoir plus de données
        $bacheliers_simules = [];
        $noms_femmes = ['Aminata', 'Fatou', 'Mariam', 'Aicha', 'Salimata', 'Hawa', 'Adja', 'Oumi', 'Nogaye', 'Seynabou', 'Fanta', 'Kadiatou', 'Aissatou', 'Ramatou', 'Maimouna'];
        $noms_hommes = ['Kouassi', 'Mamadou', 'Ibrahim', 'Sekou', 'Bakary', 'Lassina', 'Aboubacar', 'Youssouf', 'Moussa', 'Adama', 'Khalifa', 'Serigne', 'Mansour', 'Fallou', 'Moustapha'];
        $prenoms_femmes = ['KOUASSI', 'KONAN', 'OUATTARA', 'YAO', 'BAMBA', 'KOUADIO', 'TRAORE', 'DIABATE', 'KONE', 'SANGARE', 'DOUMBIA', 'FOFANA', 'DIARRA', 'CISSE', 'SARR'];
        $prenoms_hommes = ['KOUADIO', 'KONE', 'OUATTARA', 'TOURE', 'SANGARE', 'DOUMBIA', 'FOFANA', 'DIARRA', 'BAMBA', 'CISSE', 'SARR', 'MBOUP', 'DIOP', 'GUEYE', 'BA'];

        // Générer 200 bacheliers simulés supplémentaires
        for ($i = 0; $i < 200; $i++) {
            $ville = $villes_ci[array_rand($villes_ci)];
            $is_femme = rand(0, 1) == 1;
            
            if ($is_femme) {
                $nom = $noms_femmes[array_rand($noms_femmes)];
                $prenom = $prenoms_femmes[array_rand($prenoms_femmes)];
            } else {
                $nom = $noms_hommes[array_rand($noms_hommes)];
                $prenom = $prenoms_hommes[array_rand($prenoms_hommes)];
            }

            // Déterminer le statut (30% boursiers, 50% vérifiés, 20% en attente)
            $rand = rand(1, 100);
            if ($rand <= 30) {
                $boursier_peub = true;
                $status_profil = 'verifie';
            } elseif ($rand <= 80) {
                $boursier_peub = false;
                $status_profil = 'verifie';
            } else {
                $boursier_peub = false;
                $status_profil = 'incomplet';
            }

            $bacheliers_simules[] = [
                'nom' => $nom,
                'prenoms' => $prenom,
                'region' => $this->getRegionFromVille($ville['nom']),
                'commune' => $ville['nom'],
                'boursier_peub' => $boursier_peub,
                'status_profil' => $status_profil,
                'lng' => $ville['lng'],
                'lat' => $ville['lat']
            ];
        }

        // Combiner les vrais bacheliers avec les simulés
        $tous_bacheliers = collect($bacheliers_reels)->map(function ($bachelier) use ($villes_ci) {
            $ville = $villes_ci[array_rand($villes_ci)];
            return [
                'nom' => $bachelier->nom,
                'prenoms' => $bachelier->prenoms,
                'region' => $bachelier->region,
                'commune' => $bachelier->commune,
                'boursier_peub' => $bachelier->boursier_peub,
                'status_profil' => $bachelier->status_profil,
                'lng' => $ville['lng'],
                'lat' => $ville['lat']
            ];
        })->merge($bacheliers_simules);

        // Convertir en format GeoJSON
        $donnees_carte = $bacheliers->map(function ($bachelier) use ($villes_ci) {
            $ville = collect($villes_ci)->firstWhere('nom', $bachelier->commune) ?? $villes_ci[array_rand($villes_ci)];
            $status = $bachelier->boursier_peub ? 'active' : ($bachelier->status_profil === 'verifie' ? 'verifie' : 'inactive');
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$ville['lng'], $ville['lat']]
                ],
                'properties' => [
                    'nom' => $bachelier->nom . ' ' . $bachelier->prenoms,
                    'region' => $bachelier->region,
                    'commune' => $bachelier->commune,
                    'status_boursier' => $status,
                    'color' => $status === 'active' ? '#10B981' : ($status === 'verifie' ? '#3B82F6' : '#6B7280')
                ]
            ];
        });

        return view('partenaire.analytics', compact(
            'stats', 'top_regions', 'stats_par_commune', 'stats_par_genre', 'stats_par_age',
            'opportunites_par_pays', 'bacheliers_recents', 'donnees_carte'
        ));
    }

    /**
     * Obtenir les données pour les graphiques (AJAX)
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'inscriptions');

        if ($type === 'inscriptions') {
            // Données des inscriptions par mois
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $mois = now()->subMonths($i);
                $count = Bachelier::whereYear('created_at', $mois->year)
                    ->whereMonth('created_at', $mois->month)
                    ->count();
                $data[] = [
                    'month' => $mois->format('M Y'),
                    'count' => $count
                ];
            }
        } elseif ($type === 'regions') {
            // Données par région
            $data = Bachelier::select('region', DB::raw('COUNT(*) as count'))
                ->groupBy('region')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'region' => $item->region,
                        'count' => $item->count
                    ];
                });
        } else {
            // Données par genre
            $data = Bachelier::select('genre', DB::raw('COUNT(*) as count'))
                ->groupBy('genre')
                ->get()
                ->map(function ($item) {
                    return [
                        'genre' => $item->genre,
                        'count' => $item->count
                    ];
                });
        }

        return response()->json($data);
    }

    /**
     * Obtenir les statistiques détaillées
     */
    public function getDetailedStats()
    {
        // Top des régions
        $top_regions = Bachelier::select('region', DB::raw('COUNT(*) as count'))
            ->groupBy('region')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Répartition des boursiers
        $boursiers_par_statut = Bachelier::select('status_boursier', DB::raw('COUNT(*) as count'))
            ->groupBy('status_boursier')
            ->get();

        // Âge moyen par région
        $age_moyen_par_region = Bachelier::select('region', DB::raw('AVG(age) as age_moyen'))
            ->groupBy('region')
            ->orderBy('age_moyen', 'desc')
            ->get();

        return response()->json([
            'top_regions' => $top_regions,
            'boursiers_par_statut' => $boursiers_par_statut,
            'age_moyen_par_region' => $age_moyen_par_region
        ]);
    }

    private function getRegionFromVille($ville)
    {
        // Répartition des villes par région officielle (selon le formulaire de candidature)
        $regions = [
            // District d'Abidjan
            'Abidjan' => 'Abidjan',
            'Grand-Bassam' => 'Grands‑Ponts',
            'Anyama' => 'Agnéby‑Tiassa',
            'Bingerville' => 'Grands‑Ponts',
            'Dabou' => 'Grands‑Ponts',
            'Tiassalé' => 'Grands‑Ponts',
            'Grand-Lahou' => 'Grands‑Ponts',
            'Aboisso' => 'Sud‑Comoé',
            'Agboville' => 'Agnéby‑Tiassa',
            'Adzopé' => 'La Mé',
            
            // District de Yamoussoukro
            'Yamoussoukro' => 'Yamoussoukro',
            
            // Région des Savanes
            'Korhogo' => 'Poro',
            'Ferkessédougou' => 'Tchologo',
            'Tingréla' => 'Bounkani',
            'Boundiali' => 'Bagoué',
            'Odienné' => 'Kabadougou',
            'Bouna' => 'Bounkani',
            'Tanda' => 'Gontougo',
            'Koun-Fao' => 'Gontougo',
            'Doropo' => 'Bounkani',
            'Nassian' => 'Gontougo',
            'Minignan' => 'Folon',
            'Samatiguila' => 'Folon',
            'Ouangolodougou' => 'Tchologo',
            'Niellé' => 'Tchologo',
            'Sinématiali' => 'Tchologo',
            
            // Région de la Vallée du Bandama
            'Bouaké' => 'Gbêkê',
            'Katiola' => 'Hambol',
            'Mankono' => 'Béré',
            'Béoumi' => 'Gbêkê',
            'Sakassou' => 'Gbêkê',
            'M\'Bahiakro' => 'Iffou',
            'Dimbokro' => 'Nzi',
            'Oumé' => 'Marahoué',
            'Sinfra' => 'Marahoué',
            'Zuénoula' => 'Marahoué',
            'Vavoua' => 'Haut‑Sassandra',
            
            // Région du Zanzan
            'Bondoukou' => 'Gontougo',
            'Touba' => 'Bafing',
            
            // Région du Worodougou
            'Séguéla' => 'Worodougou',
            
            // Région du Bas-Sassandra
            'San-Pédro' => 'San‑Pédro',
            'Sassandra' => 'San‑Pédro',
            'Soubré' => 'Nawa',
            'Tabou' => 'San‑Pédro',
            
            // Région du Haut-Sassandra
            'Daloa' => 'Haut‑Sassandra',
            'Issia' => 'Haut‑Sassandra',
            
            // Région des Montagnes
            'Man' => 'Tonkpi',
            'Bangolo' => 'Guémon',
            'Danané' => 'Tonkpi',
            'Biankouma' => 'Tonkpi',
            'Guiglo' => 'Cavally',
            'Duékoué' => 'Guémon',
            'Bloléquin' => 'Cavally',
            'Toulepleu' => 'Cavally',
            'Tai' => 'Cavally',
            
            // Région du Gôh
            'Gagnoa' => 'Gôh',
            'Divo' => 'LôhDjiboua',
            'Lakota' => 'Gôh',
            
            // Région de l'Indénié-Djuablin
            'Abengourou' => 'Indénié‑Djuablin',
        ];

        return $regions[$ville] ?? 'Autre';
    }
}

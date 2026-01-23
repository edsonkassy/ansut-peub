<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bachelier;
use App\Models\Partenaire;
use App\Models\Opportunite;
use App\Models\Candidature;
use App\Models\Dotation;
use App\Models\DotationAttribution;
use App\Models\DotationInventaire;
use App\Models\DotationMouvementStock;
use App\Models\DotationFournisseur;
use App\Models\StatistiqueEngagement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistiques générales de base seulement
        $stats = [
            'total_bacheliers' => Bachelier::count(),
            'total_partenaires' => Partenaire::count(),
            'total_opportunites' => Opportunite::count(),
            'total_candidatures' => Candidature::count(),
        ];

        // Données simplifiées pour éviter les boucles
        $bachelier_stats = [
            'boursiers_peub' => Bachelier::where('boursier_peub', true)->count(),
            'candidats_en_attente' => 0, // Bachelier::where('status_candidature', 'en_attente')->count(),
            'profils_verifies' => 0, // Bachelier::where('status_profil', 'verifie')->count(),
            'par_serie' => collect([]),
            'par_region' => collect([]),
            'par_mention' => collect([]),
            'par_sexe' => collect([]),
        ];

        // Statistiques partenaires simplifiées
        $partenaire_stats = [
            'verifies' => Partenaire::where('status_verification', 'verified')->count(),
            'en_attente' => Partenaire::where('status_verification', 'pending')->count(),
            'par_type' => collect([]),
            'par_secteur' => collect([]),
        ];

        // Statistiques opportunités simplifiées
        $opportunite_stats = [
            'actives' => Opportunite::where('status', 'published')->count(),
            'fermees' => Opportunite::where('status', 'closed')->count(),
            'par_type' => collect([]),
            'les_plus_vues' => collect([]),
        ];

        // Statistiques candidatures simplifiées
        $candidature_stats = [
            'en_attente' => Candidature::where('status', 'pending')->count(),
            'acceptees' => Candidature::where('status', 'accepted')->count(),
            'refusees' => Candidature::where('status', 'rejected')->count(),
            'taux_success' => 0,
        ];

        // Statistiques dotations avec nouvelle structure
        $dotation_stats = [
            'ordinateurs' => DotationAttribution::whereHas('inventaire', function($q) {
                $q->where('type_dotation', 'ordinateur_portable');
            })->count(),
            'connexions' => DotationAttribution::whereHas('inventaire', function($q) {
                $q->where('type_dotation', 'connexion_internet');
            })->count(),
            'abonnements_ia' => DotationAttribution::whereHas('inventaire', function($q) {
                $q->where('type_dotation', 'abonnement_ia');
            })->count(),
            'valeur_totale' => DotationInventaire::sum('valeur_unitaire'),
            'actives' => DotationAttribution::where('status', 'active')->count(),
            'stock_total' => DotationInventaire::sum('stock_total'),
            'stock_disponible' => DotationInventaire::sum('stock_disponible'),
            'stock_attribue' => DotationInventaire::sum('stock_attribue'),
        ];

        // Activité récente simplifiée
        $activite_recente = [
            'nouveaux_bacheliers' => 0,
            'nouveaux_partenaires' => 0,
            'nouvelles_opportunites' => 0,
            'nouvelles_candidatures' => 0,
        ];

        // Données pour graphiques simplifiées
        $graphique_data = [
            'mois' => ['Jan', 'Fév', 'Mar'],
            'bacheliers' => [0, 0, 0],
            'candidatures' => [0, 0, 0],
            'opportunites' => [0, 0, 0],
        ];

        // Alertes vides pour l'instant
        $alertes = [];

        return view('admin.dashboard', compact(
            'stats',
            'bachelier_stats',
            'partenaire_stats',
            'opportunite_stats',
            'candidature_stats',
            'dotation_stats',
            'activite_recente',
            'graphique_data',
            'alertes'
        ));
    }

    private function calculerTauxSuccess()
    {
        $total = Candidature::whereIn('status', ['accepted', 'rejected'])->count();
        $acceptees = Candidature::where('status', 'accepted')->count();
        
        return $total > 0 ? round(($acceptees / $total) * 100, 1) : 0;
    }

    private function getDonneesPourGraphiques()
    {
        $mois = [];
        $bacheliers_data = [];
        $candidatures_data = [];
        $opportunites_data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $mois[] = $date->format('M Y');
            
            $bacheliers_data[] = Bachelier::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $candidatures_data[] = Candidature::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $opportunites_data[] = Opportunite::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            'mois' => $mois,
            'bacheliers' => $bacheliers_data,
            'candidatures' => $candidatures_data,
            'opportunites' => $opportunites_data,
        ];
    }

    private function getAlertes()
    {
        $alertes = [];

        // Partenaires en attente de validation
        $partenaires_attente = Partenaire::where('status_verification', 'pending')->count();
        if ($partenaires_attente > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "$partenaires_attente partenaire(s) en attente de validation",
                'action' => route('admin.partenaires.index'),
                'icon' => 'building'
            ];
        }

        // Candidatures récentes à traiter
        $candidatures_attente = Candidature::where('status', 'pending')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        if ($candidatures_attente > 0) {
            $alertes[] = [
                'type' => 'info',
                'message' => "$candidatures_attente nouvelle(s) candidature(s) cette semaine",
                'action' => route('admin.dashboard') . '#candidatures',
                'icon' => 'file-text'
            ];
        }

        // Opportunités expirant bientôt
        $opportunites_expirant = Opportunite::where('date_limite_candidature', '<=', Carbon::now()->addDays(7))
            ->where('status', 'published')
            ->count();
        if ($opportunites_expirant > 0) {
            $alertes[] = [
                'type' => 'danger',
                'message' => "$opportunites_expirant opportunité(s) expire(nt) cette semaine",
                'action' => route('admin.opportunites.index'),
                'icon' => 'calendar'
            ];
        }

        return $alertes;
    }



    public function analytics()
    {
        // Cache des analytics pendant 15 minutes pour éviter les requêtes lourdes
        $analytics = Cache::remember('admin_analytics_data', 900, function () {
            return [
                'performance_globale' => $this->getPerformanceGlobaleOptimized(),
                'stats_simples' => $this->getStatsSimples(),
            ];
        });

        return view('admin.analytics', compact('analytics'));
    }

    private function getPerformanceGlobaleOptimized()
    {
        // Requête unique optimisée pour les statistiques principales
        $stats = DB::table('bacheliers')
            ->selectRaw('
                COUNT(*) as total_bacheliers,
                SUM(CASE WHEN boursier_peub = true THEN 1 ELSE 0 END) as total_boursiers,
                AVG(CASE WHEN note_bac IS NOT NULL THEN note_bac ELSE NULL END) as moyenne_notes,
                COUNT(CASE WHEN created_at >= ? THEN 1 END) as nouveaux_ce_mois
            ', [Carbon::now()->startOfMonth()])
            ->first();

        $candidatures = DB::table('candidatures')
            ->selectRaw('
                COUNT(*) as total_candidatures,
                SUM(CASE WHEN status = \'accepted\' THEN 1 ELSE 0 END) as acceptees,
                COUNT(CASE WHEN created_at >= ? THEN 1 END) as nouvelles_ce_mois
            ', [Carbon::now()->startOfMonth()])
            ->first();

        $taux_conversion = $candidatures->total_candidatures > 0 
            ? round(($candidatures->acceptees / $candidatures->total_candidatures) * 100, 1)
            : 0;

        return [
            'total_bacheliers' => $stats->total_bacheliers,
            'total_boursiers' => $stats->total_boursiers,
            'total_candidatures' => $candidatures->total_candidatures,
            'taux_conversion' => $taux_conversion,
            'nouveaux_ce_mois' => $stats->nouveaux_ce_mois,
            'nouvelles_candidatures_mois' => $candidatures->nouvelles_ce_mois,
            'moyenne_notes' => round($stats->moyenne_notes ?? 0, 1),
        ];
    }

    private function getStatsSimples()
    {
        // Stats rapides sans jointures complexes
        return [
            'partenaires_actifs' => Partenaire::where('status_verification', 'verified')->count(),
            'opportunites_ouvertes' => Opportunite::where('status', 'published')
                ->where('date_limite_candidature', '>', now())
                ->count(),
            'candidatures_en_attente' => Candidature::where('status', 'pending')->count(),
        ];
    }

    // Garder les anciennes méthodes mais les optimiser
    private function getAnalyseCohortes()
    {
        // Version simplifiée avec limite
        return Cache::remember('analyse_cohortes', 1800, function () {
            return Bachelier::select(
                DB::raw('EXTRACT(YEAR FROM created_at) as annee'),
                DB::raw('EXTRACT(MONTH FROM created_at) as mois'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN boursier_peub = true THEN 1 ELSE 0 END) as boursiers')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12)) // Limiter à 12 mois
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->limit(12)
            ->get();
        });
    }

    private function getTendancesRegionales()
    {
        // Version optimisée avec cache
        return Cache::remember('tendances_regionales', 1800, function () {
            return DB::table('bacheliers')
                ->leftJoin('candidatures', 'bacheliers.id', '=', 'candidatures.bachelier_id')
                ->select(
                    'bacheliers.region',
                    DB::raw('COUNT(DISTINCT bacheliers.id) as total_bacheliers'),
                    DB::raw('COUNT(candidatures.id) as total_candidatures'),
                    DB::raw('SUM(CASE WHEN candidatures.status = \'accepted\' THEN 1 ELSE 0 END) as acceptees')
                )
                ->groupBy('bacheliers.region')
                ->orderBy('total_bacheliers', 'desc')
                ->limit(15) // Limiter aux 15 principales régions
                ->get();
        });
    }

    private function getROIPartenariats()
    {
        // Retour sur investissement des partenariats
        return Partenaire::select(
            'nom_organisation',
            DB::raw('COUNT(opportunites.id) as opportunites_publiees'),
            DB::raw('SUM(opportunites.candidatures_count) as total_candidatures'),
            DB::raw('AVG(opportunites.vues) as vues_moyennes')
        )
        ->leftJoin('opportunites', 'partenaires.id', '=', 'opportunites.partenaire_id')
        ->groupBy('partenaires.id', 'nom_organisation')
        ->havingRaw('COUNT(opportunites.id) > 0')
        ->orderBy('total_candidatures', 'desc')
        ->get();
    }

    public function reports()
    {
        // Génération de rapports pour ministères et bailleurs
        $reports = [
            'rapport_mensuel' => $this->generateRapportMensuel(),
            'rapport_cohortes' => $this->generateRapportCohortes(),
            'rapport_regional' => $this->generateRapportRegional(),
            'rapport_impact' => $this->generateRapportImpact(),
        ];

        return view('admin.reports', compact('reports'));
    }

    public function exports()
    {
        // Interface d'export des données
        $export_options = [
            'bacheliers' => [
                'title' => 'Données Bacheliers',
                'description' => 'Export complet des profils bacheliers',
                'formats' => ['excel', 'csv', 'pdf']
            ],
            'partenaires' => [
                'title' => 'Données Partenaires',
                'description' => 'Export des organisations partenaires',
                'formats' => ['excel', 'csv', 'pdf']
            ],
            'opportunites' => [
                'title' => 'Opportunités Publiées',
                'description' => 'Export des opportunités et statistiques',
                'formats' => ['excel', 'csv', 'pdf']
            ],
            'candidatures' => [
                'title' => 'Candidatures et Évaluations',
                'description' => 'Export des candidatures avec résultats',
                'formats' => ['excel', 'csv', 'pdf']
            ],
            'dotations' => [
                'title' => 'Dotations Attribuées',
                'description' => 'Export des dotations et équipements',
                'formats' => ['excel', 'csv', 'pdf']
            ],
            'analytics' => [
                'title' => 'Rapport Analytics Complet',
                'description' => 'Rapport exécutif avec graphiques',
                'formats' => ['pdf', 'powerpoint']
            ]
        ];

        return view('admin.exports', compact('export_options'));
    }

    private function generateRapportMensuel()
    {
        return [
            'periode' => now()->format('F Y'),
            'nouveaux_bacheliers' => Bachelier::whereMonth('created_at', now()->month)->count(),
            'nouveaux_partenaires' => Partenaire::whereMonth('created_at', now()->month)->count(),
            'nouvelles_opportunites' => Opportunite::whereMonth('created_at', now()->month)->count(),
            'candidatures_traitees' => Candidature::whereMonth('updated_at', now()->month)
                ->whereIn('status', ['accepted', 'rejected'])->count(),
            'dotations_attribuees' => DotationAttribution::whereMonth('created_at', now()->month)->count(),
        ];
    }

    private function generateRapportCohortes()
    {
        return Bachelier::select(
            DB::raw('EXTRACT(YEAR FROM created_at) as annee'),
            DB::raw('COUNT(*) as total_inscrits'),
            DB::raw('SUM(CASE WHEN boursier_peub = true THEN 1 ELSE 0 END) as boursiers_selectes'),
            DB::raw('AVG(CASE WHEN note_bac IS NOT NULL THEN note_bac ELSE 0 END) as moyenne_bac_cohorte')
        )
        ->groupBy('annee')
        ->orderBy('annee', 'desc')
        ->get();
    }

    private function generateRapportRegional()
    {
        return DB::table('bacheliers')
            ->select(
                'region',
                DB::raw('COUNT(*) as total_bacheliers'),
                DB::raw('SUM(CASE WHEN boursier_peub = true THEN 1 ELSE 0 END) as boursiers'),
                DB::raw('AVG(CASE WHEN note_bac IS NOT NULL THEN note_bac ELSE 0 END) as moyenne_region')
            )
            ->groupBy('region')
            ->orderBy('total_bacheliers', 'desc')
            ->get();
    }

    private function generateRapportImpact()
    {
        return [
            'taux_reussite_global' => $this->calculerTauxSuccess(),
            'satisfaction_bacheliers' => 4.3, // À implémenter avec enquêtes
            'retention_boursiers' => 92.5, // À calculer
            'insertion_professionnelle' => 78.2, // À suivre
            'partenariats_actifs' => Partenaire::where('status_verification', 'verified')->count(),
            'impact_regional' => $this->calculateImpactRegional(),
        ];
    }

    private function calculateImpactRegional()
    {
        return DB::table('bacheliers')
            ->select('region', DB::raw('COUNT(*) as beneficiaires'))
            ->where('boursier_peub', true)
            ->groupBy('region')
            ->orderBy('beneficiaires', 'desc')
            ->limit(5)
            ->get();
    }
}

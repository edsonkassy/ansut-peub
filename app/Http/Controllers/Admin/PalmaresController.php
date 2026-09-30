<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PalmaresBac;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Espace admin : consultation du palmarès officiel et suivi des inscriptions.
 */
class PalmaresController extends Controller
{
    public function index(Request $request)
    {
        $annee = (int) $request->get('annee', 2026);

        $palmares = $this->baseQuery($request, $annee)
            ->orderBy('palmares_bac.drena')
            ->orderBy('palmares_bac.rang_drena')
            ->orderBy('palmares_bac.sexe')
            ->paginate(50)
            ->withQueryString();

        $base = PalmaresBac::where('annee_bac', $annee);
        $stats = [
            'total' => (clone $base)->count(),
            'femmes' => (clone $base)->where('sexe', 'F')->count(),
            'hommes' => (clone $base)->where('sexe', 'M')->count(),
            'inscrits' => DB::table('bacheliers')
                ->join('palmares_bac', 'palmares_bac.id', '=', 'bacheliers.palmares_bac_id')
                ->where('palmares_bac.annee_bac', $annee)
                ->where('bacheliers.bac_verifie', true)->count(),
            'a_revoir' => DB::table('bacheliers')
                ->join('palmares_bac', 'palmares_bac.id', '=', 'bacheliers.palmares_bac_id')
                ->where('palmares_bac.annee_bac', $annee)
                ->where('bacheliers.bac_verification_statut', 'divergent')->count(),
        ];
        $stats['non_inscrits'] = $stats['total'] - $stats['inscrits'] - $stats['a_revoir'];

        return view('admin.palmares.index', [
            'palmares' => $palmares,
            'stats' => $stats,
            'annee' => $annee,
            'drenas' => PalmaresBac::where('annee_bac', $annee)->distinct()->orderBy('drena')->pluck('drena'),
            'series' => PalmaresBac::where('annee_bac', $annee)->distinct()->orderBy('serie')->pluck('serie'),
        ]);
    }

    /**
     * Données de la carte du palmarès (pastilles géolocalisées par DRENA), pour
     * l'admin uniquement. Contrairement à LandingMapController (carte publique),
     * les noms complets sont toujours affichés ici : pas de logique de consentement,
     * l'admin a déjà accès à toutes les données des bacheliers. Respecte les mêmes
     * filtres que le tableau (baseQuery), donc reflète toujours ce qui est affiché.
     *
     * Les DRENA absentes de config/drena.php (valeurs venant telles quelles du CSV
     * DECO, non normalisées à l'import) sont loguées et renvoyées dans
     * drenas_non_localisees plutôt que silencieusement ignorées.
     */
    public function carteData(Request $request)
    {
        $annee = (int) $request->get('annee', 2026);
        $drenas = config('drena');

        $rows = $this->baseQuery($request, $annee)->get();

        $drenasNonLocalisees = [];
        $markers = $rows->map(function ($p) use ($drenas, &$drenasNonLocalisees) {
            $centre = $drenas[$p->drena] ?? null;
            if (!$centre) {
                if (!in_array($p->drena, $drenasNonLocalisees, true)) {
                    $drenasNonLocalisees[] = $p->drena;
                }
                return null;
            }

            // Décalage stable (basé sur le matricule), comme sur la carte publique :
            // les pastilles d'une même DRENA ne se superposent pas.
            $h = crc32($p->matricule);
            $angle = ($h % 360) * M_PI / 180;
            $rayon = 0.03 + (($h >> 8) % 1000) / 1000 * 0.10;

            return [
                'name' => "{$p->nom} {$p->prenoms}",
                'matricule' => $p->matricule,
                'gender' => $p->sexe === 'F' ? 'female' : 'male',
                'serie' => 'Série ' . $p->serie,
                'drena' => $p->drena,
                'rang' => (int) $p->rang_drena,
                'mention' => $p->mention === 'ims' ? 'IMS' : ($p->mention ? ucfirst(str_replace('_', ' ', $p->mention)) : 'Sans mention'),
                'points' => (int) $p->points,
                'inscrit' => $p->bachelier_id !== null,
                'bachelier_id' => $p->bachelier_id,
                'lat' => round($centre['lat'] + sin($angle) * $rayon, 5),
                'lng' => round($centre['lng'] + cos($angle) * $rayon, 5),
            ];
        })->filter()->values();

        if (!empty($drenasNonLocalisees)) {
            Log::warning('Carte palmarès admin : DRENA non reconnues (absentes de config/drena.php)', [
                'annee' => $annee,
                'drenas' => $drenasNonLocalisees,
            ]);
        }

        return response()->json([
            'markers' => $markers,
            'drenas_non_localisees' => $drenasNonLocalisees,
        ]);
    }

    public function export(Request $request)
    {
        $annee = (int) $request->get('annee', 2026);
        $rows = $this->baseQuery($request, $annee)
            ->orderBy('palmares_bac.drena')->orderBy('palmares_bac.rang_drena')->get();

        $handle = fopen('php://memory', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['DRENA', 'Rang', 'Matricule', 'Nom', 'Prénoms', 'Sexe', 'Date naissance', 'Série',
                          'Mention', 'Points /400', 'Établissement', 'Inscrit sur PEUB'], ';');
        foreach ($rows as $r) {
            fputcsv($handle, [
                $r->drena, $r->rang_drena, $r->matricule, $r->nom, $r->prenoms, $r->sexe,
                $r->date_naissance, $r->serie, $r->mention === 'ims' ? 'IMS' : $r->mention, $r->points, $r->etablissement,
                match ($r->bac_verification_statut) { 'verifie' => 'Oui (vérifié)', 'divergent' => 'À revoir', default => 'Non' },
            ], ';');
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="palmares_bac_' . $annee . '_' . date('Y-m-d') . '.csv"',
        ]);
    }

    private function baseQuery(Request $request, int $annee)
    {
        $query = PalmaresBac::query()
            ->leftJoin('bacheliers', 'bacheliers.palmares_bac_id', '=', 'palmares_bac.id')
            ->select('palmares_bac.*', 'bacheliers.id as bachelier_id', 'bacheliers.bac_verification_statut')
            ->where('palmares_bac.annee_bac', $annee);

        if ($request->filled('drena')) {
            $query->where('palmares_bac.drena', $request->drena);
        }
        if ($request->filled('sexe')) {
            $query->where('palmares_bac.sexe', $request->sexe);
        }
        if ($request->filled('serie')) {
            $query->where('palmares_bac.serie', $request->serie);
        }
        if ($request->filled('mention')) {
            $request->mention === 'aucune'
                ? $query->whereNull('palmares_bac.mention')
                : $query->where('palmares_bac.mention', $request->mention);
        }
        if ($request->filled('inscrit')) {
            match ($request->inscrit) {
                'oui' => $query->where('bacheliers.bac_verification_statut', 'verifie'),
                'revoir' => $query->where('bacheliers.bac_verification_statut', 'divergent'),
                'non' => $query->whereNull('bacheliers.id'),
                default => null,
            };
        }
        if ($request->filled('search')) {
            $s = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->whereRaw('LOWER(palmares_bac.nom) like ?', [$s])
                  ->orWhereRaw('LOWER(palmares_bac.prenoms) like ?', [$s])
                  ->orWhereRaw('LOWER(palmares_bac.matricule) like ?', [$s]);
            });
        }

        return $query;
    }
}

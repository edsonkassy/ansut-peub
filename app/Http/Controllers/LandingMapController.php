<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Pastilles de la carte de la landing (section "Nos boursiers").
 * Renvoie TOUT le palmarès officiel de l'année (2 050 pour 2026), avec un indicateur
 * `inscrit` : true si l'élève s'est inscrit sur PEUB et que son matricule a été vérifié.
 * Public. Le nom n'est affiché (« Prénom + initiale ») que pour les élèves INSCRITS qui ont coché
 * la case de consentement. Tous les autres restent anonymes (« Meilleur(e) bachelier(ère) »).
 */
class LandingMapController extends Controller
{
    public function index(int $annee)
    {
        $data = Cache::remember("landing.carte.v3.{$annee}", now()->addMinutes(10), function () use ($annee) {
            $drenas = config('drena');

            return DB::table('palmares_bac')
                ->leftJoin('bacheliers', function ($join) {
                    $join->on('bacheliers.palmares_bac_id', '=', 'palmares_bac.id')
                         ->where('bacheliers.bac_verifie', true);
                })
                ->where('palmares_bac.annee_bac', $annee)
                ->get([
                    'palmares_bac.matricule', 'palmares_bac.prenoms', 'palmares_bac.nom',
                    'palmares_bac.sexe', 'palmares_bac.serie', 'palmares_bac.drena',
                    'palmares_bac.rang_drena', 'bacheliers.id as bachelier_id',
                    'bacheliers.consentement_carte_publique as consentement',
                ])
                ->map(function ($p) use ($drenas) {
                    $centre = $drenas[$p->drena] ?? null;
                    if (!$centre) {
                        return null;
                    }

                    // Décalage stable (basé sur le matricule) : les pastilles d'une même DRENA ne se superposent pas
                    $h = crc32($p->matricule);
                    $angle = ($h % 360) * M_PI / 180;
                    $rayon = 0.03 + (($h >> 8) % 1000) / 1000 * 0.10;

                    $afficherNom = $p->bachelier_id !== null && (bool) $p->consentement;
                    $prenom = Str::title(Str::of($p->prenoms)->explode(' ')->first());
                    $initiale = Str::upper(Str::substr($p->nom, 0, 1));

                    return [
                        'name' => $afficherNom
                            ? "{$prenom} {$initiale}."
                            : ($p->sexe === 'F' ? 'Meilleure bachelière' : 'Meilleur bachelier'),
                        'avatar' => $afficherNom ? Str::upper(Str::substr($prenom, 0, 1) . $initiale) : '★',
                        'gender' => $p->sexe === 'F' ? 'female' : 'male',
                        'serie' => 'Série ' . $p->serie,
                        'commune' => Str::title(strtolower($p->drena)),
                        'rang' => (int) $p->rang_drena,
                        'inscrit' => $p->bachelier_id !== null,
                        'lat' => round($centre['lat'] + sin($angle) * $rayon, 5),
                        'lng' => round($centre['lng'] + cos($angle) * $rayon, 5),
                    ];
                })
                ->filter()
                ->values()
                ->all();
        });

        return response()->json($data);
    }
}

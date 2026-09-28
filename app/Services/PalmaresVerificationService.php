<?php

namespace App\Services;

use App\Models\Bachelier;
use App\Models\PalmaresBac;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Vérifie qu'un bachelier qui s'inscrit figure bien dans le palmarès officiel.
 *
 * Statuts :
 *  - verifie   : matricule trouvé ET nom + date de naissance concordent
 *  - divergent : matricule trouvé mais nom ou date de naissance différents (à revoir par un admin)
 *  - absent    : matricule absent de la liste. Ce n'est PAS un refus : d'autres bacheliers
 *                que les 2 050 du palmarès peuvent s'inscrire, ils sont simplement « non vérifiés ».
 */
class PalmaresVerificationService
{
    public function verify(array $data): array
    {
        $matricule = PalmaresBac::normalizeMatricule($data['matricule_bac'] ?? '');
        $palmares = $matricule !== '' ? PalmaresBac::where('matricule', $matricule)->first() : null;

        if (!$palmares) {
            return ['status' => 'absent', 'palmares' => null, 'official' => [], 'divergences' => [],
                    'note' => 'Matricule absent du palmarès officiel.'];
        }

        $divergences = [];

        if (!$this->nomMatches($data['nom'] ?? '', $palmares->nom)) {
            $divergences[] = "nom déclaré « {$data['nom']} » ≠ officiel « {$palmares->nom} »";
        }

        try {
            $brute = trim((string) ($data['date_naissance'] ?? ''));
            $declaree = preg_match('#^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$#', $brute)
                ? Carbon::createFromFormat('d/m/Y', $brute)->toDateString()
                : Carbon::parse($brute)->toDateString();
        } catch (\Throwable $e) {
            $declaree = null;
        }
        if ($declaree !== $palmares->date_naissance->toDateString()) {
            $divergences[] = "date de naissance déclarée {$declaree} ≠ officielle {$palmares->date_naissance->toDateString()}";
        }

        if (!empty($divergences)) {
            return ['status' => 'divergent', 'palmares' => $palmares, 'official' => [],
                    'divergences' => $divergences, 'note' => implode(' ; ', $divergences)];
        }

        // Vérifié : les données scolaires officielles remplacent celles déclarées
        // (évite qu'un candidat gonfle sa note pour améliorer son score PEUB).
        $official = [
            'note_bac' => $palmares->points,
            'serie_bac' => $palmares->serie,
            'annee_bac' => (int) $palmares->annee_bac,
            // Mention officielle du palmarès (ims, passable, assez_bien, bien, tres_bien)
            'mention' => $palmares->mention,
        ];

        $ecarts = [];
        $noteDecl = $data['note_bac'] ?? 0;
        $serieDecl = strtoupper($data['serie_bac'] ?? '');
        if ((float) $noteDecl !== (float) $palmares->points) {
            $ecarts[] = "note déclarée {$noteDecl} → officielle {$palmares->points}";
        }
        if ($serieDecl !== $palmares->serie) {
            $ecarts[] = "série déclarée {$serieDecl} → officielle {$palmares->serie}";
        }

        return ['status' => 'verifie', 'palmares' => $palmares, 'official' => $official,
                'divergences' => $ecarts,
                'note' => $ecarts ? 'Données scolaires corrigées : ' . implode(' ; ', $ecarts) : null];
    }

    /**
     * À appeler APRÈS la création du profil bachelier.
     * $consentementCarte : case cochée à l'inscription (affichage « Prénom + initiale » sur la carte publique).
     */
    public function markBachelier(Bachelier $bachelier, array $result, bool $consentementCarte = false): void
    {
        // withoutEvents : évite un recalcul de score inutile ; forceFill : pas besoin de toucher $fillable
        Bachelier::withoutEvents(function () use ($bachelier, $result, $consentementCarte) {
            $bachelier->forceFill([
                'consentement_carte_publique' => $consentementCarte,
                'consentement_carte_at' => $consentementCarte ? now() : null,
                'bac_verifie' => $result['status'] === 'verifie',
                'bac_verifie_at' => $result['status'] === 'verifie' ? now() : null,
                'palmares_bac_id' => $result['palmares']?->id,
                'bac_verification_statut' => $result['status'],
                'bac_verification_note' => $result['note'],
            ])->save();
        });
    }

    private function nomMatches(string $declare, string $officiel): bool
    {
        $a = $this->normalize($declare);
        $b = $this->normalize($officiel);
        if ($a === '' || $b === '') {
            return false;
        }
        return $a === $b || (strlen($a) >= 3 && strlen($b) >= 3 && (str_contains($a, $b) || str_contains($b, $a)));
    }

    private function normalize(string $value): string
    {
        return preg_replace('/[^A-Z]/', '', strtoupper(Str::ascii($value)));
    }
}

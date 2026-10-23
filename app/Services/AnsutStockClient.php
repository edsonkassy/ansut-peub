<?php

namespace App\Services;

use App\Models\Bachelier;
use App\Models\Dotation;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Client de l'API ANSUT Stock (gestion des stocks d'équipements).
 * Les ordinateurs portables sont suivis à l'unité par ANSUT Stock : c'est lui qui réserve l'appareil
 * au point de retrait, envoie le code de retrait au bachelier par SMS et confirme la remise (webhooks).
 * Authentification : jeton OAuth2 client credentials (client Keycloak du partenaire PEUB), mis en cache.
 */
class AnsutStockClient
{
    public function configured(): bool
    {
        return filled(config('services.ansut_stock.base_url')) && filled(config('services.ansut_stock.client_id'));
    }

    /** Points de retrait actifs : [code => « Nom (ville) »]. */
    public function pointsDeRetrait(): array
    {
        return Cache::remember('ansut_stock.points_retrait', 600, function () {
            $sites = $this->get('/v1/sites', ['filter[type]' => 'PICKUP_POINT', 'filter[isActive]' => 'true', 'limit' => 100])['items'] ?? [];
            return collect($sites)->mapWithKeys(fn ($s) => [$s['code'] => trim($s['name'] . (empty($s['city']) ? '' : ' (' . $s['city'] . ')'))])->all();
        });
    }

    /**
     * Demande la réservation d'un ordinateur pour cette dotation. Idempotent : rejouer la même dotation
     * renvoie l'attribution existante. Lève AnsutStockException avec un message lisible (rupture, point inconnu…).
     *
     * @return array{id: string, number: string, status: string}
     */
    public function attribuer(Dotation $dotation, string $codePointRetrait): array
    {
        /** @var Bachelier $b */
        $b = $dotation->bachelier;
        $inv = $dotation->inventaire;
        $telephone = $b->telephone_eleve ?: $b->telephone_parent;
        if (! $telephone) {
            throw new AnsutStockException('Le bachelier n\'a pas de numéro de téléphone : le code de retrait ne pourrait pas lui être envoyé.');
        }
        if (! $inv->marque || ! $inv->modele) {
            throw new AnsutStockException('Renseignez la marque et le modèle de cet article (identiques au catalogue ANSUT Stock).');
        }

        $body = array_filter([
            'externalRef' => self::referenceDotation($dotation->id),
            'pickupSiteCode' => $codePointRetrait,
            'brand' => $inv->marque,
            'model' => $inv->modele,
            'beneficiary' => array_filter([
                'externalRef' => 'PEUB-BAC-' . $b->id,
                'fullName' => trim($b->nom . ' ' . $b->prenoms),
                'phone' => self::telephone($telephone),
                'email' => $b->email_eleve ?: null,
                'idDocType' => $b->piece_identite_type ?: null,
            ]),
        ]);

        $response = $this->http()->post('/v1/distributions', $body);
        if ($response->successful()) {
            return $response->json();
        }
        $detail = $response->json('message');
        $detail = is_array($detail) ? implode(' ', $detail) : $detail;
        throw new AnsutStockException(match ($response->status()) {
            409 => 'Aucun ordinateur de ce modèle n\'est disponible à ce point de retrait (ou référence déjà utilisée).',
            404 => 'Point de retrait inconnu d\'ANSUT Stock.',
            400 => 'ANSUT Stock a refusé la demande : ' . ($detail ?: 'données invalides') . '.',
            401, 403 => 'Accès à ANSUT Stock refusé : vérifiez la clé d\'API de la PEUB.',
            default => 'ANSUT Stock est indisponible (' . $response->status() . '). Réessayez plus tard.',
        });
    }

    /** Référence d'une dotation chez ANSUT Stock (externalRef), et l'inverse pour les webhooks. */
    public static function referenceDotation(int $id): string
    {
        return 'PEUB-DOT-' . $id;
    }

    public static function dotationDepuisReference(?string $ref): ?int
    {
        return $ref && preg_match('/^PEUB-DOT-(\d+)$/', $ref, $m) ? (int) $m[1] : null;
    }

    /** « 07 00 00 00 00 » → « +2250700000000 » ; un numéro déjà international est conservé. */
    public static function telephone(string $numero): string
    {
        $chiffres = preg_replace('/\D/', '', $numero);
        if (str_starts_with(trim($numero), '+')) return '+' . $chiffres;
        if (str_starts_with($chiffres, '225') && strlen($chiffres) === 13) return '+' . $chiffres;
        return strlen($chiffres) === 10 ? '+225' . $chiffres : $chiffres;
    }

    private function get(string $path, array $query = []): array
    {
        return $this->http()->get($path, $query)->throw()->json();
    }

    private function http()
    {
        return Http::baseUrl(rtrim(config('services.ansut_stock.base_url'), '/'))
            ->withToken($this->token())
            ->acceptJson()
            ->timeout(15);
    }

    private function token(): string
    {
        $cached = Cache::get('ansut_stock.token');
        if ($cached) return $cached;
        try {
            $res = Http::asForm()->timeout(10)->post(config('services.ansut_stock.token_url'), [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.ansut_stock.client_id'),
                'client_secret' => config('services.ansut_stock.client_secret'),
            ])->throw()->json();
        } catch (RequestException $e) {
            throw new AnsutStockException('Connexion à ANSUT Stock impossible : identifiants de la PEUB refusés ou serveur injoignable.');
        }
        // Marge de 30 s avant l'expiration annoncée.
        Cache::put('ansut_stock.token', $res['access_token'], max(30, ($res['expires_in'] ?? 300) - 30));
        return $res['access_token'];
    }
}

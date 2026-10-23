<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\DotationAttribution;
use App\Services\AnsutStockClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Webhooks d'ANSUT Stock : suivi des ordinateurs attribués (réservé, remis, remplacé, annulé).
 * Signature : X-Signature = sha256=HMAC-SHA256(secret, "<X-Webhook-Timestamp>.<corps brut>"),
 * horodatage de moins de 5 minutes, chaque livraison traitée une seule fois (X-Webhook-Id).
 */
class AnsutStockWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $secret = (string) config('services.ansut_stock.webhook_secret');
        $timestamp = (string) $request->header('X-Webhook-Timestamp');
        $signature = (string) $request->header('X-Signature');
        $expected = 'sha256=' . hash_hmac('sha256', $timestamp . '.' . $request->getContent(), $secret);

        if ($secret === '' || ! hash_equals($expected, $signature)) {
            return response()->json(['message' => 'Signature invalide'], 401);
        }
        if (! ctype_digit($timestamp) || abs(time() - (int) $timestamp) > 300) {
            return response()->json(['message' => 'Horodatage expiré'], 401);
        }
        $id = (string) $request->header('X-Webhook-Id');
        if ($id !== '' && ! Cache::add('ansut_stock.webhook.' . $id, true, now()->addDay())) {
            return response()->json(['status' => 'déjà traité']);
        }

        $type = $request->input('type');
        $data = $request->input('data', []);
        $dotation = ($dotationId = AnsutStockClient::dotationDepuisReference($data['externalRef'] ?? null))
            ? DotationAttribution::find($dotationId)
            : null;
        if (! $dotation) {
            // Événement sans dotation PEUB (test, autre plateforme) : accusé de réception sans effet.
            return response()->json(['status' => 'ignoré']);
        }

        $suivi = $dotation->donnees_specifiques ?? [];
        $suivi['ansut_stock'] = array_merge($suivi['ansut_stock'] ?? [], ['dernier_evenement' => $type, 'recu_le' => now()->toIso8601String()]);

        match ($type) {
            'distribution.replaced' => $suivi['ansut_stock']['numero'] = $data['number'] ?? ($suivi['ansut_stock']['numero'] ?? null),
            'distribution.delivered' => $suivi['ansut_stock']['remis_le'] = $data['deliveredAt'] ?? now()->toIso8601String(),
            default => null,
        };
        $dotation->donnees_specifiques = $suivi;

        if ($type === 'distribution.delivered') {
            $dotation->identifiant_unique = $data['serialNumber'] ?? $data['imei'] ?? $dotation->identifiant_unique;
            $dotation->save();
            if ($dotation->status !== 'active') {
                $dotation->activer(); // e-mail « dotation attribuée » au bachelier, comme une activation manuelle
            }
        } elseif ($type === 'distribution.cancelled') {
            $dotation->save();
            $dotation->suspendre('Retrait annulé par ANSUT Stock' . (empty($data['reason']) ? '' : ' : ' . $data['reason']));
        } else {
            $dotation->save();
        }

        Log::info('Webhook ANSUT Stock traité', ['type' => $type, 'dotation_id' => $dotation->id]);
        return response()->json(['status' => 'ok']);
    }
}

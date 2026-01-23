<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIResponsesService
{
    private string $apiKey;
    private ?string $baseUrl;
    private ?string $organization;
    private int $timeoutSeconds;

    public function __construct()
    {
        $this->apiKey = config('openai.api_key');
        $this->organization = config('openai.organization');
        $this->baseUrl = config('services.openai.base_url');
        $this->timeoutSeconds = (int) config('services.openai.timeout', 60);
    }

    public function createResponse(array $messages, array $tools = [], ?string $preference = null, array $options = []): array
    {
        $model = $this->selectModel($preference);

        $start = microtime(true);
        $payload = [
            'model' => $model,
            // Expect fully formed input items from caller (each with role + content[] items with type/text)
            'input' => $messages,
        ];

        if (!empty($tools)) {
            $payload['tools'] = $tools;
        }

        $url = rtrim($this->baseUrl ?: 'https://api.openai.com', '/') . '/v1/responses';

        $response = Http::withHeaders($this->buildHeaders())
            ->timeout($this->timeoutSeconds)
            ->post($url, array_replace_recursive($payload, $options));

        $latencyMs = (int) ((microtime(true) - $start) * 1000);

        if (!$response->ok()) {
            Log::error('OpenAI Responses API error', [
                'status' => $response->status(),
                'body' => $response->json(),
                'latency_ms' => $latencyMs,
                'model' => $model,
            ]);
            throw new \RuntimeException('OpenAI Responses API error: ' . $response->status());
        }

        $json = $response->json();
        Log::info('OpenAI Responses API ok', [ 'latency_ms' => $latencyMs, 'model' => $model ]);
        return $json;
    }

    private function selectModel(?string $preference): string
    {
        $models = config('openai.models');
        if ($preference === 'fast') {
            return $models['fast'] ?? 'gpt-5-nano';
        }
        return $models['default'] ?? 'gpt-5-mini';
    }

    private function buildHeaders(): array
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];
        if (!empty($this->organization)) {
            $headers['OpenAI-Organization'] = $this->organization;
        }
        return $headers;
    }
}



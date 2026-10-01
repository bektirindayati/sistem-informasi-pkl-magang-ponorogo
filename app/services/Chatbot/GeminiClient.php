<?php

namespace App\Services\Chatbot;

use App\Exceptions\ChatbotException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

/** Satu-satunya tempat yang bicara langsung dengan Gemini API. */
class GeminiClient
{
    private const HOST = 'generativelanguage.googleapis.com';

    /** Status sementara yang layak dicoba ulang. */
    private const STATUS_ULANG = [408, 429, 503];

    /**
     * @param  array  $contents  riwayat + pesan baru, format Gemini
     * @return string|null balasan, null jika Gemini tidak memberi teks
     *
     * @throws ChatbotException
     */
    public function generate(string $systemInstruction, array $contents): ?string
    {
        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            throw new ChatbotException(
                'Maaf, fitur AI belum dikonfigurasi oleh admin (GEMINI_API_KEY belum diisi).',
                500
            );
        }

        $model = config('services.gemini.model', 'gemini-3.5-flash');

        try {
            $response = $this->http($apiKey)->post(
                'https://' . self::HOST . "/v1beta/models/{$model}:generateContent",
                [
                    'system_instruction' => ['parts' => [['text' => $systemInstruction]]],
                    'contents' => $contents,
                   'generationConfig' => [
    'temperature' => 0.4,
    'maxOutputTokens' => 2048,
    'thinkingConfig' => [
        'thinkingLevel' => 'minimal',
    ],
],
                ]
            );
        } catch (Throwable $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);

            throw new ChatbotException(
                'Maaf, terjadi kendala koneksi ke asisten AI. Silakan coba lagi.',
                503,
                $e
            );
        }

        if ($response->failed()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new ChatbotException(
                'Maaf, asisten AI sedang bermasalah. Coba lagi sebentar lagi ya.'
            );
        }

        $teks = data_get($response->json(), 'candidates.0.content.parts.0.text');

        return filled($teks) ? trim($teks) : null;
    }

  private function http(string $apiKey): PendingRequest
{
    return Http::withHeaders([
        'x-goog-api-key' => $apiKey,
    ])
        ->acceptJson()
        ->asJson()
        ->timeout(20)
        ->withOptions([
            'curl' => [
                CURLOPT_RESOLVE => [
                    'generativelanguage.googleapis.com:443:172.217.119.4',
                ],
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            ],
        ]);
}

    private function opsiResolveDns(): array
    {
        $ips = gethostbynamel(self::HOST);

        if (empty($ips)) {
            throw new RuntimeException('Tidak dapat menemukan IP Gemini melalui DNS.');
        }

        return [
            'curl' => [
                CURLOPT_RESOLVE => [self::HOST . ':443:' . $ips[0]],
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            ],
        ];
    }
}
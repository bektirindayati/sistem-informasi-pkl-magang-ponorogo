<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Terima pesan dari widget chat, teruskan ke Gemini API,
     * lalu kembalikan balasan sebagai JSON.
     */
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            // riwayat percakapan dikirim dari JS supaya AI ingat konteks
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|in:user,model',
            'history.*.text' => 'required_with:history|string',
        ]);

        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            return response()->json([
                'reply' => 'Maaf, fitur AI belum dikonfigurasi oleh admin (GEMINI_API_KEY belum diisi).',
            ], 500);
        }

        // Instruksi AI (system prompt) tidak ditulis di sini —
        // isinya ada di resources/views/prompts/chatbot-system.blade.php
        // supaya bisa diedit tanpa menyentuh file controller ini.
        $daftarDinas = Dinas::pluck('nama_dinas')->implode(', ');

        $systemInstruction = view('prompts.chatbot-system', [
            'daftarDinas' => $daftarDinas,
        ])->render();

        // Susun riwayat percakapan + pesan baru dalam format Gemini
        $contents = [];
        foreach ($request->input('history', []) as $item) {
            $contents[] = [
                'role' => $item['role'],
                'parts' => [['text' => $item['text']]],
            ];
        }
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $request->input('message')]],
        ];

        // Nama model diambil dari config, bukan hardcode — supaya bisa
        // diganti lewat .env tanpa ubah kode kalau Google merilis versi baru.
        $model = config('services.gemini.model', 'gemini-3.5-flash');

try {
    // PHP cURL di komputer ini mengalami masalah DNS.
    // Ambil IP Gemini secara dinamis agar tidak perlu hardcode IP Google.
    $geminiHost = 'generativelanguage.googleapis.com';
    $geminiIps = gethostbynamel($geminiHost);

    if (empty($geminiIps)) {
        throw new \RuntimeException('Tidak dapat menemukan IP Gemini melalui DNS.');
    }

    $geminiIp = $geminiIps[0];

$curlResolve = [
    "{$geminiHost}:443:{$geminiIp}",
];

    // Coba request maksimal 3 kali untuk menangani
    // 503 (high demand), 429 (rate limit), dan timeout.
    $maxAttempts = 3;
    $response = null;

    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        try {
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_RESOLVE => $curlResolve,
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
            ])->withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post(
                "https://{$geminiHost}/v1beta/models/{$model}:generateContent",
                [
                    'system_instruction' => [
                        'parts' => [['text' => $systemInstruction]],
                    ],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'maxOutputTokens' => 512,
                    ],
                ]
            );

            // Berhasil → hentikan retry.
            if ($response->successful()) {
                break;
            }

            // Retry untuk error yang sifatnya sementara.
            if (in_array($response->status(), [408, 429, 503])) {
                Log::warning('Gemini API retry', [
                    'attempt' => $attempt,
                    'status' => $response->status(),
                ]);

                if ($attempt < $maxAttempts) {
                    sleep($attempt * 2);
                    continue;
                }
            }

            // Error selain 408/429/503 tidak perlu diulang.
            break;

        } catch (\Throwable $e) {
            Log::warning('Gemini API connection retry', [
                'attempt' => $attempt,
                'message' => $e->getMessage(),
            ]);

            if ($attempt < $maxAttempts) {
                sleep($attempt * 2);
                continue;
            }

            throw $e;
        }
    }

            if ($response->failed()) {
                Log::error('Gemini API error', ['body' => $response->body()]);
                return response()->json([
                    'reply' => 'Maaf, asisten AI sedang bermasalah. Coba lagi sebentar lagi ya.',
                ], 500);
            }

            $data = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text']
                ?? 'Maaf, saya belum bisa menjawab itu. Coba tanyakan dengan cara lain ya.';

            return response()->json(['reply' => trim($reply)]);
        } catch (\Throwable $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);
            return response()->json([
                'reply' => 'Maaf, terjadi kendala koneksi ke asisten AI. Silakan coba lagi.',
            ], 500);
        }
    }
}
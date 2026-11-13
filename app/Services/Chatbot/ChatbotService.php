<?php

namespace App\Services\Chatbot;

use App\Exceptions\ChatbotException;
use App\Models\Dinas;

/** Menyusun percakapan dan instruksi AI, lalu meminta balasan ke Gemini. */
class ChatbotService
{
    private const BALASAN_KOSONG = 'Maaf, saya belum bisa menjawab itu. Coba tanyakan dengan cara lain ya.';

    public function __construct(private GeminiClient $gemini)
    {
    }

    /**
     * @param  array  $riwayat  [['role' => 'user|model', 'text' => '...'], ...]
     *
     * @throws ChatbotException
     */
    public function jawab(string $pesan, array $riwayat): string
    {
        return $this->gemini->generate(
            $this->instruksiSistem(),
            $this->susunPercakapan($pesan, $riwayat)
        ) ?? self::BALASAN_KOSONG;
    }

    /** Isi instruksi ada di resources/views/prompts/chatbot-system.blade.php. */
    private function instruksiSistem(): string
    {
        return view('prompts.chatbot-system', [
            'daftarDinas' => Dinas::pluck('nama_dinas')->implode(', '),
        ])->render();
    }

    private function susunPercakapan(string $pesan, array $riwayat): array
    {
        $contents = array_map(fn ($item) => [
            'role' => $item['role'],
            'parts' => [['text' => $item['text']]],
        ], $riwayat);

        $contents[] = ['role' => 'user', 'parts' => [['text' => $pesan]]];

        return $contents;
    }
}
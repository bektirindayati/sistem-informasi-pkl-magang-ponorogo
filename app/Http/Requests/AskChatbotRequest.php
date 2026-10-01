<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Pesan dari widget chat. Riwayat dikirim dari browser (server tidak
 * menyimpan percakapan), jadi dianggap tidak tepercaya: jumlah, panjang,
 * dan role-nya dibatasi di sini.
 */
class AskChatbotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // chatbot terbuka untuk pengunjung yang belum login
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:1000'],

            'history' => ['nullable', 'array', 'max:20'],
            'history.*.role' => ['required', 'in:user,model'],
            'history.*.text' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Tulis pertanyaanmu dulu ya.',
            'message.max' => 'Pertanyaan terlalu panjang, maksimal 1000 karakter.',
        ];
    }

    /** Widget membaca field "reply", jadi error validasi pun dikirim sebagai reply. */
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();

        $pesan = $errors->has('message')
            ? $errors->first('message')
            : 'Riwayat percakapan tidak valid. Muat ulang halaman lalu coba lagi.';

        throw new HttpResponseException(response()->json(['reply' => $pesan], 422));
    }

    public function pesan(): string
    {
        return trim($this->validated('message'));
    }

    /** Riwayat bersih: teks di-trim dan selalu diawali giliran user (syarat Gemini). */
    public function riwayat(): array
    {
        return collect($this->validated('history') ?? [])
            ->map(fn ($item) => ['role' => $item['role'], 'text' => trim($item['text'])])
            ->filter(fn ($item) => $item['text'] !== '')
            ->skipWhile(fn ($item) => $item['role'] !== 'user')
            ->values()
            ->all();
    }
}
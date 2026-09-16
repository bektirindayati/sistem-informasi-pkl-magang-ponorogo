<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDinasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_dinas' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'alamat' => ['nullable', 'string'],
            // Checkbox HTML tidak mengirim apa pun kalau tidak dicentang,
            // makanya di controller/view kita pastikan selalu ada nilainya
            // (lihat hidden input di form). 'boolean' menerima 0/1/"on"/dst.
            'status' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_dinas.required' => 'Nama dinas wajib diisi.',
        ];
    }
}
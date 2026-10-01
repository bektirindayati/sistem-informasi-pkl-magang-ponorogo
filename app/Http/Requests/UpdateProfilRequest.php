<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Ubah data akademik profil (dipakai untuk auto-fill form pendaftaran). */
class UpdateProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nim_nisn' => ['nullable', 'string', 'max:50'],
            'instansi' => ['nullable', 'string', 'max:255'],
            'jurusan'  => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nim_nisn.max' => 'NIM / NISN maksimal 50 karakter.',
            'instansi.max' => 'Asal instansi atau universitas maksimal 255 karakter.',
            'jurusan.max'  => 'Jurusan atau program studi maksimal 255 karakter.',
        ];
    }
}
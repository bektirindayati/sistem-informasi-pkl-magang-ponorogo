<?php

namespace App\Http\Requests\Profil;

use Illuminate\Foundation\Http\FormRequest;

/** Ubah data profil pengguna (dipakai sebagai data default form pendaftaran). */
class UpdateProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['nullable', 'string', 'max:255'],
            'nim_nisn'     => ['nullable', 'string', 'max:50'],
            'instansi'     => ['nullable', 'string', 'max:255'],
            'jurusan'      => ['nullable', 'string', 'max:255'],
            'no_hp'        => ['nullable', 'string', 'max:20'],
            'alamat'       => ['nullable', 'string', 'max:1000'],
            'kabupaten'    => ['nullable', 'string', 'max:100'],
            'provinsi'     => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',
            'nim_nisn.max'     => 'NIM / NISN maksimal 50 karakter.',
            'instansi.max'     => 'Asal instansi atau universitas maksimal 255 karakter.',
            'jurusan.max'      => 'Jurusan atau program studi maksimal 255 karakter.',
            'no_hp.max'        => 'No. HP maksimal 20 karakter.',
            'alamat.max'       => 'Alamat maksimal 1000 karakter.',
            'kabupaten.max'    => 'Kabupaten / kota maksimal 100 karakter.',
            'provinsi.max'     => 'Provinsi maksimal 100 karakter.',
        ];
    }
}
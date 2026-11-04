<?php

namespace App\Http\Requests\Admin\Dinas;

use Illuminate\Foundation\Http\FormRequest;

/** Tambah dinas (UpdateDinasRequest mewarisi aturan ini). */
class StoreDinasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses dijaga middleware permission:kelola-dinas di route
    }

    public function rules(): array
    {
        return [
            'nama_dinas' => ['required', 'string', 'max:255'],
            'deskripsi'  => ['nullable', 'string'],

            // Kop surat
            'alamat'     => ['nullable', 'string', 'max:500'],
            'telepon'    => ['nullable', 'string', 'max:50'],
            'faksimile'  => ['nullable', 'string', 'max:50'],
            'email'      => ['nullable', 'email', 'max:255'],
            'website'    => ['nullable', 'string', 'max:255'],
            'logo'       => ['nullable', 'string', 'max:255'],

            // Pejabat penandatangan surat balasan
            'nama_pejabat'    => ['nullable', 'string', 'max:255'],
            'jabatan_pejabat' => ['nullable', 'string', 'max:255'],
            'pangkat_pejabat' => ['nullable', 'string', 'max:100'],
            'golongan_pejabat'=> ['nullable', 'string', 'max:20'],
            'nip_pejabat'     => ['nullable', 'string', 'max:50'],


            // Checkbox HTML tidak mengirim apa pun jika tidak dicentang, makanya
            // form memakai hidden input bernilai 0. 'boolean' menerima 0/1.
            'status' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_dinas.required' => 'Nama dinas wajib diisi.',
            'nama_dinas.max'      => 'Nama dinas maksimal 255 karakter.',
            'email.email'         => 'Format email tidak valid.',
            'telepon.max'         => 'Telepon maksimal 50 karakter.',
            'faksimile.max'       => 'Faksimile maksimal 50 karakter.',
            'nip_pejabat.max'     => 'NIP maksimal 50 karakter.',
        ];
    }
}
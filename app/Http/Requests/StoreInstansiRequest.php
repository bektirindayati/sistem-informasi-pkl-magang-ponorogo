<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstansiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // BARU: wajib pilih dinas pemilik bidang — sebelumnya tidak
            // divalidasi sama sekali (makanya bisa kosong/null tersimpan).
            'dinas_id' => ['required', 'integer', 'exists:dinases,id'],
            'nama_bidang' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'dinas_id.required' => 'Dinas pemilik bidang wajib dipilih.',
            'dinas_id.exists' => 'Dinas yang dipilih tidak valid.',
            'nama_bidang.required' => 'Nama bidang wajib diisi.',
        ];
    }
}
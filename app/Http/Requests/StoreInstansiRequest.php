<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Tambah bidang. Admin dinas selalu dipaksa memakai dinas miliknya. */
class StoreInstansiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Hanya super admin yang memilih dinas lewat form;
            // admin dinas memakai dinas_id miliknya sendiri.
            'dinas_id' => [
                $this->user()->isSuperAdmin() ? 'required' : 'nullable',
                'integer',
                'exists:dinases,id',
            ],
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

    public function dataBidang(): array
    {
        $data = $this->validated();
        $admin = $this->user();

        return [
            'dinas_id' => $admin->isSuperAdmin() ? $data['dinas_id'] : $admin->dinas_id,
            'nama_bidang' => $data['nama_bidang'],
            'deskripsi' => $data['deskripsi'] ?? null,
        ];
    }
}
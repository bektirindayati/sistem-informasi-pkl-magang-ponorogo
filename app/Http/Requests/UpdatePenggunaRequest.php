<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Ubah role pengguna (khusus super admin). */
class UpdatePenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'role' => ['required', 'in:user,admin,super_admin'],
            // Admin dinas WAJIB punya dinas.
            'dinas_id' => [
                Rule::requiredIf(fn () => $this->input('role') === 'admin'),
                'nullable',
                'exists:dinases,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'dinas_id.required' => 'Admin dinas wajib ditugaskan ke salah satu dinas.',
            'dinas_id.exists' => 'Dinas yang dipilih tidak valid.',
        ];
    }

    /** Selain role 'admin', dinas_id dikosongkan. */
    public function dataPengguna(): array
    {
        $data = $this->validated();

        if ($data['role'] !== 'admin') {
            $data['dinas_id'] = null;
        }

        return $data;
    }
}
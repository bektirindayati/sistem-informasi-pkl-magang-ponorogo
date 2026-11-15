<?php

namespace App\Http\Requests\Admin\Pengguna;

use App\Models\User;
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
            'role' => [
                'required',
                'in:user,admin,super_admin',
                // Perubahan yang diizinkan bergantung pada role pengguna saat ini.
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! in_array($value, $this->roleDiizinkan(), true)) {
                        $fail('Perubahan role tersebut tidak diizinkan.');
                    }
                },
            ],
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

    /**
     * Role tujuan yang boleh dipilih, berdasarkan role pengguna saat ini:
     * - super_admin : permanen, tidak ada perubahan yang diizinkan
     * - admin       : tetap admin, atau naik menjadi super_admin
     * - user        : tetap user, atau menjadi admin
     */
    private function roleDiizinkan(): array
    {
        $roleSaatIni = User::find($this->route('id'))?->role ?: 'user';

        return match ($roleSaatIni) {
            'super_admin' => [],
            'admin'       => ['admin', 'super_admin'],
            default       => ['user', 'admin'],
        };
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
<?php

namespace App\Http\Requests\Profil;

use App\Http\Requests\Concerns\AturanDataDiri;
use Illuminate\Foundation\Http\FormRequest;

/** Ubah data profil pengguna (dipakai sebagai data default form pendaftaran). */
class UpdateProfilRequest extends FormRequest
{
    use AturanDataDiri;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->aturanDataDiri('nullable');
    }

    public function messages(): array
    {
        return $this->pesanDataDiri();
    }
}
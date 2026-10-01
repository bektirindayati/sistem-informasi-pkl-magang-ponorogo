<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Dipakai semua halaman admin yang punya kotak pencarian (?search=). */
class PencarianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function kata(): ?string
    {
        return $this->validated()['search'] ?? null;
    }
}
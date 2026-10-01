<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MemeriksaPendaftaranMilikUser;
use Illuminate\Foundation\Http\FormRequest;

/** Hapus draft: hanya pemilik, status harus draft. */
class DestroyDraftRequest extends FormRequest
{
    use MemeriksaPendaftaranMilikUser;

    protected function statusDiizinkan(): array
    {
        return ['draft'];
    }

    protected function pesanStatusDitolak(): string
    {
        return 'Hanya pendaftaran berstatus draft yang dapat dihapus lewat sini.';
    }

    public function rules(): array
    {
        return [];
    }
}
<?php

namespace App\Http\Requests;

/** Lihat detail pendaftar. */
class ShowPendaftarRequest extends AdminPendaftaranRequest
{
    protected function pesanAksesDitolak(): string
    {
        return 'Anda tidak memiliki akses ke pendaftaran dinas lain.';
    }

    public function rules(): array
    {
        return [];
    }
}
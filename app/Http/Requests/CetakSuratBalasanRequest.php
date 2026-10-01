<?php

namespace App\Http\Requests;

/** Cetak surat balasan magang. */
class CetakSuratBalasanRequest extends AdminPendaftaranRequest
{
    protected function pesanAksesDitolak(): string
    {
        return 'Anda tidak memiliki akses untuk mencetak surat pendaftar dinas lain.';
    }

    public function rules(): array
    {
        return [];
    }
}
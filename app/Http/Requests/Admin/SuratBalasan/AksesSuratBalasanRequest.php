<?php

namespace App\Http\Requests\Admin\SuratBalasan;

use App\Http\Requests\Admin\Pendaftar\AdminPendaftaranRequest;

/**
 * Akses admin ke surat balasan satu pendaftaran: dinas harus sesuai
 * (super admin: semua) dan pendaftarannya sudah diterima.
 */
class AksesSuratBalasanRequest extends AdminPendaftaranRequest
{
    public function authorize(): bool
    {
        return parent::authorize()
            && in_array($this->pendaftaran()->status, ['diterima', 'selesai'], true);
    }

    protected function pesanAksesDitolak(): string
    {
        return 'Surat balasan hanya dapat diurus untuk pendaftaran yang sudah diterima di dinas Anda.';
    }
}
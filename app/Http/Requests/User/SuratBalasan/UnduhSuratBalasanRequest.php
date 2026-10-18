<?php

namespace App\Http\Requests\User\SuratBalasan;

use App\Http\Requests\Concerns\MemeriksaPendaftaranMilikUser;
use Illuminate\Foundation\Http\FormRequest;

/** Peserta mengunduh surat balasan miliknya: harus pemilik dan pendaftarannya diterima. */
class UnduhSuratBalasanRequest extends FormRequest
{
    use MemeriksaPendaftaranMilikUser;

    protected function statusDiizinkan(): array
    {
        return ['diterima', 'selesai'];
    }

    protected function pesanStatusDitolak(): string
    {
        return 'Surat balasan hanya tersedia untuk pendaftaran yang diterima.';
    }

    public function rules(): array
    {
        return [];
    }
}
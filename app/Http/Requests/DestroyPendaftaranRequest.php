<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MemeriksaPendaftaranMilikUser;
use App\Models\PendaftaranMagang;
use Illuminate\Foundation\Http\FormRequest;

/** Batalkan / hapus pengajuan: hanya pemilik, status belum diterima. */
class DestroyPendaftaranRequest extends FormRequest
{
    use MemeriksaPendaftaranMilikUser;

    protected function statusDiizinkan(): array
    {
        return PendaftaranMagang::STATUS_BOLEH_HAPUS;
    }

    protected function pesanStatusDitolak(): string
    {
        return 'Pendaftaran yang sudah diterima/selesai tidak dapat dibatalkan.';
    }

    public function rules(): array
    {
        return [];
    }
}
<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MemeriksaPendaftaranMilikUser;
use App\Models\PendaftaranMagang;
use Illuminate\Foundation\Http\FormRequest;

/** Membuka form edit: hanya pemilik, status draft/revisi. */
class EditPendaftaranRequest extends FormRequest
{
    use MemeriksaPendaftaranMilikUser;

    protected function statusDiizinkan(): array
    {
        return PendaftaranMagang::STATUS_BOLEH_EDIT;
    }

    protected function pesanStatusDitolak(): string
    {
        return 'Pendaftaran yang sudah dikirim tidak dapat diubah lagi.';
    }

    public function rules(): array
    {
        return [];
    }
}
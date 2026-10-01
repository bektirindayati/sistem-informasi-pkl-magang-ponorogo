<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MemeriksaPendaftaranMilikUser;
use App\Models\PendaftaranMagang;

/**
 * Update / kirim ulang (status draft atau revisi).
 * Surat pengantar tidak wajib diunggah ulang jika sudah ada.
 */
class UpdatePendaftaranRequest extends BasePendaftaranRequest
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
        $suratWajib = empty($this->pendaftaran()->surat_pengantar);

        return array_merge($this->aturanData('required'), $this->aturanBerkas($suratWajib));
    }

    public function dataPendaftaran(): array
    {
        return array_merge(parent::dataPendaftaran(), $this->dataKirim());
    }
}
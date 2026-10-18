<?php

namespace App\Http\Requests\Pendaftaran;

use App\Models\PendaftaranMagang;

class DraftPendaftaranRequest extends BasePendaftaranRequest
{
    public function authorize(): bool
    {
        return $this->pesanTerkunci() === null;
    }

    public function pendaftaran(): PendaftaranMagang
{
    $terakhir = $this->pendaftaranTerakhir();

    if ($terakhir?->status === 'draft') {
        return $terakhir;
    }

    return new PendaftaranMagang([
        'user_id' => $this->user()->id,
        'status'  => 'draft',
    ]);
}

    public function rules(): array
    {
        return array_merge(
            $this->aturanData('nullable'),
            $this->aturanBerkas(false)
        );
    }

    public function dataPendaftaran(): array
    {
        return array_merge(
            parent::dataPendaftaran(),
            [
                'status' => 'draft',
                'user_id' => $this->user()->id,
            ]
        );
    }
}
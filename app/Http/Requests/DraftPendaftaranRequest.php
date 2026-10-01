<?php

namespace App\Http\Requests;

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

        // Jika sudah punya draft, gunakan draft yang sama.
        if ($terakhir && $terakhir->status === 'draft') {
            return $terakhir;
        }

        // Jika belum punya draft, buat record baru.
        $baru = new PendaftaranMagang();
        $baru->user_id = $this->user()->id;
        $baru->status = 'draft';

        return $baru;
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
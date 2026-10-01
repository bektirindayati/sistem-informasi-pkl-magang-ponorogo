<?php

namespace App\Http\Requests;

use App\Models\PendaftaranMagang;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Kirim pendaftaran: semua field wajib.
 * Jika user sudah punya draft (mis. hasil auto-save), draft itu yang
 * dikirim, bukan membuat record baru. Surat pengantar tidak wajib
 * diunggah ulang kalau draft tersebut sudah punya.
 */
class StorePendaftaranRequest extends BasePendaftaranRequest
{
    public function authorize(): bool
    {
        return $this->pesanTerkunci() === null;
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            redirect()->route('user.pendaftaran.status')->with('error', $this->pesanTerkunci())
        );
    }

    public function pendaftaran(): PendaftaranMagang
    {
        $terakhir = $this->pendaftaranTerakhir();

        if ($terakhir && $terakhir->status === 'draft') {
            return $terakhir;
        }

        $baru = new PendaftaranMagang();
        $baru->user_id = $this->user()->id;

        return $baru;
    }

    public function rules(): array
    {
        $suratWajib = empty($this->pendaftaran()->surat_pengantar);

        return array_merge($this->aturanData('required'), $this->aturanBerkas($suratWajib));
    }

    public function dataPendaftaran(): array
    {
        return array_merge(
            parent::dataPendaftaran(),
            $this->dataKirim(),
            ['user_id' => $this->user()->id]
        );
    }
}
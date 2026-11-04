<?php

namespace App\Http\Requests\Pendaftaran;

use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Kirim pendaftaran: semua field wajib.
 * Jika user sudah punya draft (mis. hasil auto-save), draft itu yang
 * dikirim, bukan membuat record baru. Surat pengantar tidak wajib
 * diunggah ulang kalau draft tersebut sudah punya.
 * (authorize, pendaftaran, rules, dan dataStatus mengikuti Base.)
 */
class StorePendaftaranRequest extends BasePendaftaranRequest
{
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            redirect()->route('user.pendaftaran.status')->with('error', $this->pesanTerkunci())
        );
    }
}
<?php

namespace App\Http\Requests\Pendaftaran;

use Illuminate\Http\Exceptions\HttpResponseException;

/** Simpan draft / auto-save: semua field boleh kosong. */
class DraftPendaftaranRequest extends BasePendaftaranRequest
{
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $this->pesanTerkunci(),
            ], 403)
        );
    }

    public function rules(): array
    {
        return array_merge(
            $this->aturanData('nullable'),
            $this->aturanBerkas(false)
        );
    }

    protected function dataStatus(): array
    {
        return ['status' => 'draft'];
    }
}
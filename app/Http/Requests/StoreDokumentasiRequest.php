<?php

namespace App\Http\Requests;

/** Tambah dokumentasi: minimal 1 foto, tiap foto punya uraian. */
class StoreDokumentasiRequest extends BaseDokumentasiRequest
{
    public function rules(): array
    {
        return array_merge($this->aturanKegiatan(), $this->aturanFoto(true));
    }
}
<?php

namespace App\Http\Requests\Concerns;

use App\Models\PendaftaranMagang;

trait MemeriksaPendaftaranMilikUser
{
    private ?PendaftaranMagang $pendaftaranDimuat = null;

    /** Daftar status yang boleh melakukan aksi ini. */
    abstract protected function statusDiizinkan(): array;

    /** Pesan 403 jika statusnya tidak diizinkan. */
    abstract protected function pesanStatusDitolak(): string;

    /** Mendukung route {id} dan route surat balasan {pendaftaran}. */
    public function pendaftaran(): PendaftaranMagang
    {
        return $this->pendaftaranDimuat ??= PendaftaranMagang::findOrFail(
            $this->route('id') ?? $this->route('pendaftaran')
        );
    }

    public function authorize(): bool
    {
        return $this->milikUser()
            && in_array($this->pendaftaran()->status, $this->statusDiizinkan(), true);
    }

    protected function failedAuthorization()
    {
        // Milik orang lain dibuat 404 supaya keberadaan data tidak bocor.
        abort(
            $this->milikUser() ? 403 : 404,
            $this->milikUser() ? $this->pesanStatusDitolak() : ''
        );
    }

    private function milikUser(): bool
    {
        return (int) $this->pendaftaran()->user_id === (int) $this->user()->id;
    }
}
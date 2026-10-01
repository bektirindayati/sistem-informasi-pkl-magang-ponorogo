<?php

namespace App\Http\Requests\Concerns;

use App\Models\PendaftaranMagang;

/**
 * Dipakai request yang bekerja pada pendaftaran tertentu (route {id}):
 * memastikan pendaftaran milik user yang login DAN statusnya diizinkan.
 */
trait MemeriksaPendaftaranMilikUser
{
    private ?PendaftaranMagang $pendaftaranDimuat = null;

    /** Daftar status yang boleh melakukan aksi ini. */
    abstract protected function statusDiizinkan(): array;

    /** Pesan 403 jika statusnya tidak diizinkan. */
    abstract protected function pesanStatusDitolak(): string;

    public function pendaftaran(): PendaftaranMagang
    {
        return $this->pendaftaranDimuat ??= PendaftaranMagang::findOrFail($this->route('id'));
    }

    public function authorize(): bool
    {
        $pendaftaran = $this->pendaftaran();

        return $this->milikUser()
            && in_array($pendaftaran->status, $this->statusDiizinkan(), true);
    }

    protected function failedAuthorization()
    {
        abort(403, $this->milikUser() ? $this->pesanStatusDitolak() : '');
    }

    private function milikUser(): bool
    {
        return (int) $this->pendaftaran()->user_id === (int) $this->user()->id;
    }
}
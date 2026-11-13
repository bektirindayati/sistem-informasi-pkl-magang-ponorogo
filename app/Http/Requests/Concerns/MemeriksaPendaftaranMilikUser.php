<?php

namespace App\Http\Requests\Concerns;

use App\Models\PendaftaranMagang;
use Illuminate\Http\Exceptions\HttpResponseException;

trait MemeriksaPendaftaranMilikUser
{
    private ?PendaftaranMagang $pendaftaranDimuat = null;

    /** Daftar status yang boleh melakukan aksi ini. */
    abstract protected function statusDiizinkan(): array;

    /** Pesan 403 jika status pendaftaran tidak diizinkan. */
    abstract protected function pesanStatusDitolak(): string;

    /**
     * Memeriksa apakah akun pengguna sedang diblokir.
     */
   protected function akunDiblokir(): bool
{
    return $this->user()?->isBlocked() ?? false;
}
    /**
     * Pesan ketika pengguna mencoba mengakses fitur
     * saat akunnya diblokir.
     */
    protected function pesanAksesDitolak(): string
{
    return 'Akun Anda sedang diblokir. Silakan hubungi administrator untuk informasi lebih lanjut.';
}

    /** Mendukung route {id} dan route surat balasan {pendaftaran}. */
    public function pendaftaran(): PendaftaranMagang
    {
        return $this->pendaftaranDimuat ??= PendaftaranMagang::findOrFail(
            $this->route('id') ?? $this->route('pendaftaran')
        );
    }

    public function authorize(): bool
    {
        return !$this->akunDiblokir()
            && $this->milikUser()
            && in_array(
                $this->pendaftaran()->status,
                $this->statusDiizinkan(),
                true
            );
    }

    protected function failedAuthorization()
    {
        // Akun diblokir: arahkan ke halaman status.
        if ($this->akunDiblokir()) {
            throw new HttpResponseException(
                redirect()
                    ->route('user.pendaftaran.status')
                    ->with('error', $this->pesanAksesDitolak())
            );
        }

        // Milik orang lain dibuat 404 supaya keberadaan data tidak bocor.
        abort(
            $this->milikUser() ? 403 : 404,
            $this->milikUser() ? $this->pesanStatusDitolak() : ''
        );
    }

    private function milikUser(): bool
    {
        return (int) $this->pendaftaran()->user_id
            === (int) $this->user()->id;
    }
}

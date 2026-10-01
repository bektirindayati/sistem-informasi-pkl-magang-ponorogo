<?php

namespace App\Http\Requests\Concerns;

/**
 * Aturan akses admin: super admin boleh semua, admin dinas hanya boleh
 * data milik dinasnya sendiri.
 */
trait MemeriksaAksesDinasAdmin
{
    /** dinas_id pemilik data yang sedang diakses. */
    abstract protected function dinasIdTarget();

    /** Pesan 403 jika admin mengakses data dinas lain. */
    abstract protected function pesanAksesDitolak(): string;

    public function authorize(): bool
    {
        $admin = $this->user();

        if ($admin->isSuperAdmin()) {
            return true;
        }

        return $admin->dinas_id !== null
            && (int) $this->dinasIdTarget() === (int) $admin->dinas_id;
    }

    protected function failedAuthorization()
    {
        abort(403, $this->pesanAksesDitolak());
    }
}
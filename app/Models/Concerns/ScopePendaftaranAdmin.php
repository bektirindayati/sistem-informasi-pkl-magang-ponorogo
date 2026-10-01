<?php

namespace App\Models\Concerns;

use App\Models\User;

/**
 * Scope query pendaftaran untuk halaman admin.
 * Pasang di model PendaftaranMagang dengan: use ScopePendaftaranAdmin;
 */
trait ScopePendaftaranAdmin
{
    /** Super admin melihat semua, admin dinas hanya dinasnya sendiri. */
    public function scopeUntukAdmin($query, User $admin)
    {
        return $admin->isSuperAdmin()
            ? $query
            : $query->where('dinas_id', $admin->dinas_id);
    }

    /** Draft belum dianggap pengajuan, jadi tidak tampil di admin. */
    public function scopeSudahDikirim($query)
    {
        return $query->whereIn('status', ['pending', 'revisi', 'diterima', 'ditolak']);
    }

    public function scopeCariNama($query, ?string $kata)
    {
        return $query->when($kata, fn ($q) => $q->where('nama_lengkap', 'like', "%{$kata}%"));
    }

    /** Pending, atau diterima tapi belum lewat tanggal_selesai. */
    public function scopeAktif($query)
    {
        $hariIni = now()->toDateString();

        return $query->where(function ($q) use ($hariIni) {
            $q->where('status', 'pending')
              ->orWhere(function ($q2) use ($hariIni) {
                  $q2->where('status', 'diterima')
                     ->where(function ($q3) use ($hariIni) {
                         $q3->whereNull('tanggal_selesai')
                            ->orWhereDate('tanggal_selesai', '>=', $hariIni);
                     });
              });
        });
    }

    /** Diterima dan tanggal_selesai sudah lewat. */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'diterima')
            ->whereDate('tanggal_selesai', '<', now()->toDateString());
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }
}
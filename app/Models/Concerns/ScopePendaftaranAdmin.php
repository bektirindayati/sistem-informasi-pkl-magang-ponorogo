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
    return $query->whereIn('status', [
        'pending',
        'revisi',
        'diterima',
        'ditolak',
        'selesai',
    ]);
}

   public function scopeCariNama($query, ?string $kata)
{
    return $query->when($kata, fn ($q) => $q->where(fn ($q) => $q
        ->where('nama_lengkap', 'like', "%{$kata}%")
        ->orWhere('nim_nisn', 'like', "%{$kata}%")
        ->orWhereHas('anggotas', fn ($a) => $a
            ->where('nama_lengkap', 'like', "%{$kata}%")
            ->orWhere('nim_nisn', 'like', "%{$kata}%"))));
}

    /** Pending, atau diterima tapi belum lewat tanggal_selesai. */
   /**
 * Pending dan diterima yang belum ditandai selesai.
 */
public function scopeAktif($query)
{
    return $query->whereIn('status', ['pending', 'diterima']);
}

    public function scopeSedangMagang($query)
    {
        $hariIni = now()->toDateString();

        return $query->where('status', 'diterima')
            ->where(function ($q) use ($hariIni) {
                $q->whereNull('tanggal_selesai')
                  ->orWhereDate('tanggal_selesai', '>=', $hariIni);
            });
    }

    /**
 * Pendaftar yang sudah ditandai selesai oleh admin.
 */
public function scopeSelesai($query)
{
    return $query->where('status', 'selesai');
}

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }
}
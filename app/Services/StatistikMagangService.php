<?php

namespace App\Services;

use App\Models\AnggotaPendaftaran;
use App\Models\PendaftaranMagang;
use Illuminate\Database\Eloquent\Builder;

/** Statistik magang: beranda publik & super admin (semua dinas) atau admin dinas (satu dinas). */
class StatistikMagangService
{
    private const JUMLAH_TAHUN = 5;

    public function ringkasan(): array
    {
        $dasar = fn (): Builder => PendaftaranMagang::query()->sudahDikirim();

        $tahunIni = now()->year;
        $tahun    = range($tahunIni - self::JUMLAH_TAHUN + 1, $tahunIni);

        $perTahun = $dasar()
            ->where('created_at', '>=', now()->setYear($tahun[0])->startOfYear())
            ->get(['id', 'created_at', 'status'])
            ->groupBy(fn ($p) => $p->created_at->year);

        $grafik = collect($tahun)->map(function (int $t) use ($perTahun) {
            $baris = $perTahun->get($t, collect());

            return [
                'tahun'     => $t,
                'pendaftar' => $baris->count(),
                'diterima'  => $baris->whereIn('status', ['diterima', 'selesai'])->count(),
            ];
        })->all();

        $ini = end($grafik);

        return [
            'totalPendaftar'     => $dasar()->count(),
            'pemagangAktif'      => $this->orang($dasar()->sedangMagang()),
            'alumniSelesai'      => $this->orang($dasar()->selesai()),
            'tahunSekarang'      => $tahunIni,
            'pendaftarTahunIni'  => $ini['pendaftar'],
            'diterimaTahunIni'   => $ini['diterima'],
            'persentaseDiterima' => $ini['pendaftar'] > 0 ? (int) round($ini['diterima'] / $ini['pendaftar'] * 100) : 0,
            'statistikMagang'    => $grafik,
        ];
    }

    /** Jumlah orang: ketua + seluruh anggota dari pengajuan pada query. */
    private function orang(Builder $pengajuan): int
    {
        return (clone $pengajuan)->count()
            + AnggotaPendaftaran::whereIn('pendaftaran_magang_id', (clone $pengajuan)->select('id'))->count();
    }
}
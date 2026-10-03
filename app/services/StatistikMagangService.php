<?php

namespace App\Services;

use App\Models\Dinas;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Collection;

class StatistikMagangService
{
    /**
     * Kumpulkan semua data statistik untuk halaman utama dalam satu
     * array siap-pakai. Controller tinggal panggil method ini.
     *
     * Pakai scope asli dari PendaftaranMagang (sudahDikirim, selesai)
     * supaya definisinya konsisten dengan dashboard admin — bukan
     * query where() mentah lagi.
     */
    public function ringkasan(): array
    {
        $tahunSekarang = now()->year;

        return [
            // ===== KARTU UTAMA — snapshot kondisi HARI INI =====
            'totalPendaftar' => PendaftaranMagang::sudahDikirim()->count(),
            'pemagangAktif' => PendaftaranMagang::sedangMagang()->count(),
            'alumniSelesai' => PendaftaranMagang::selesai()->count(),

            // ===== RINGKASAN TAHUN INI & GRAFIK — histori per tahun,
            //       dari kapan pendaftaran dibuat (created_at), BUKAN
            //       status hari ini. Jangan pakai sedangMagang()/selesai()
            //       di sini, karena periode magang tahun-tahun lama
            //       sudah pasti lewat sekarang — pakai status diterima
            //       biasa supaya tetap merepresentasikan "diterima di
            //       tahun itu". =====
            'tahunSekarang' => $tahunSekarang,
            'pendaftarTahunIni' => $this->hitungPengajuan($tahunSekarang),
            'diterimaTahunIni' => $this->hitungDiterima($tahunSekarang),
            'persentaseDiterima' => $this->persentase(
                $this->hitungDiterima($tahunSekarang),
                $this->hitungPengajuan($tahunSekarang)
            ),
            'statistikMagang' => $this->grafikLimaTahun($tahunSekarang),
        ];
    }

    /**
     * Rekap jumlah per dinas (sepanjang waktu, bukan per tahun) —
     * dipakai untuk badge di kartu "Instansi Tujuan".
     * Hasilnya: [dinas_id => ['pendaftar' => x, 'aktif' => y, 'selesai' => z]]
     */
    public function rekapPerDinas(): Collection
    {
        $totalPerDinas = $this->groupByDinas(PendaftaranMagang::sudahDikirim());
        $aktifPerDinas = $this->groupByDinas(PendaftaranMagang::sedangMagang());
        $selesaiPerDinas = $this->groupByDinas(PendaftaranMagang::selesai());

        return Dinas::pluck('id')->mapWithKeys(fn ($dinasId) => [
            $dinasId => [
                'pendaftar' => $totalPerDinas[$dinasId] ?? 0,
                'aktif' => $aktifPerDinas[$dinasId] ?? 0,
                'selesai' => $selesaiPerDinas[$dinasId] ?? 0,
            ],
        ]);
    }

    private function groupByDinas($query): Collection
    {
        return $query->selectRaw('dinas_id, COUNT(*) as total')
            ->groupBy('dinas_id')
            ->pluck('total', 'dinas_id');
    }

    /**
     * "Pengajuan" histori per tahun — pakai scope sudahDikirim() yang
     * sama dengan dashboard admin (draft tidak dihitung).
     */
    private function hitungPengajuan(?int $tahun = null): int
    {
        $query = PendaftaranMagang::sudahDikirim();

        if ($tahun) {
            $query->whereYear('created_at', $tahun);
        }

        return $query->count();
    }

    /**
     * "Diterima" histori per tahun — status diterima biasa, TIDAK
     * pakai sedangMagang() (lihat penjelasan di ringkasan() di atas).
     */
    private function hitungDiterima(?int $tahun = null): int
    {
        $query = PendaftaranMagang::where('status', 'diterima');

        if ($tahun) {
            $query->whereYear('created_at', $tahun);
        }

        return $query->count();
    }

    private function persentase(int $bagian, int $total): int
    {
        return $total > 0 ? (int) round(($bagian / $total) * 100) : 0;
    }

    /**
     * Data grafik 5 tahun terakhir (termasuk tahun ini).
     * Format tiap item cocok dengan script Chart.js yang sudah ada
     * di welcome.blade.php: item.tahun, item.pendaftar, item.diterima.
     */
    private function grafikLimaTahun(int $tahunSekarang): Collection
    {
        return collect(range($tahunSekarang - 4, $tahunSekarang))
            ->map(fn ($tahun) => [
                'tahun' => (string) $tahun,
                'pendaftar' => $this->hitungPengajuan($tahun),
                'diterima' => $this->hitungDiterima($tahun),
            ]);
    }
}
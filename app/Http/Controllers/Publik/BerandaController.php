<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use App\Models\DokumentasiMagang;
use App\Services\StatistikMagangService;

class BerandaController extends Controller
{
    public function __construct(
        private StatistikMagangService $statistik
    ) {
    }

    public function __invoke()
    {
        // Statistik utama halaman beranda
        $ringkasan = $this->statistik->ringkasan();

        // Rekap statistik masing-masing dinas
        $rekapPerDinas = $this->statistik->rekapPerDinas();

        // Ambil dinas aktif dan pasangkan rekap statistiknya
        $dinases = Dinas::aktif()
            ->get()
            ->each(function ($dinas) use ($rekapPerDinas) {
                $dinas->rekap = $rekapPerDinas->get($dinas->id, [
                    'pendaftar' => 0,
                    'aktif' => 0,
                    'selesai' => 0,
                ]);
            });

        return view('welcome', [
            ...$ringkasan,

            'dinases' => $dinases,

            // Urut menurut waktu acara, bukan waktu unggah
            'dokumentasis' => DokumentasiMagang::with('fotos')
                ->terbaru()
                ->take(6)
                ->get(),
        ]);
    }
}
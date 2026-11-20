<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use App\Models\DokumentasiMagang;
use App\Services\StatistikMagangService;

class BerandaController extends Controller
{
    public function __invoke(StatistikMagangService $statistik)
    {
        // Ambil dinas aktif (pembatasan 6 kartu dilakukan di view)
        $dinases = Dinas::aktif()->get();

        return view('welcome', [
            'dinases' => $dinases,

            // Urut menurut waktu acara, bukan waktu unggah
            'dokumentasis' => DokumentasiMagang::with('fotos')
                ->terbaru()
                ->take(3)
                ->get(),

            // Total seluruh dokumentasi, untuk tombol "Lihat semua" bila lebih dari 3
            'totalDokumentasi' => DokumentasiMagang::count(),

            // Ringkasan statistik halaman beranda
            'statistik' => $statistik->ringkasan(),
        ]);
    }
}
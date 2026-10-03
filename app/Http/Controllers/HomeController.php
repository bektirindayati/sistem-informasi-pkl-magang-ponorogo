<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\DokumentasiMagang;
use App\Services\StatistikMagangService;

class HomeController extends Controller
{
    /**
     * Laravel otomatis "suntik" StatistikMagangService ke sini
     * (dependency injection) — tidak perlu bikin instance manual.
     */
    public function index(StatistikMagangService $statistikMagangService)
    {
        $rekapPerDinas = $statistikMagangService->rekapPerDinas();

        // Sama seperti closure lama: cuma dinas yang status-nya aktif.
        $dinases = Dinas::where('status', true)
            ->orderBy('nama_dinas')
            ->get()
            ->each(function ($dinas) use ($rekapPerDinas) {
                $dinas->rekap = $rekapPerDinas[$dinas->id] ?? [
                    'pendaftar' => 0,
                    'aktif' => 0,
                    'selesai' => 0,
                ];
            });

        $dokumentasis = DokumentasiMagang::with('fotos')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', [
            ...$statistikMagangService->ringkasan(),
            'dinases' => $dinases,
            'dokumentasis' => $dokumentasis,
        ]);
    }
}
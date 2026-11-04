<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\DokumentasiMagang;

/** Halaman detail dokumentasi untuk pengunjung (tanpa login). */
class DokumentasiPublikController extends Controller
{
    public function show(DokumentasiMagang $dokumentasi)
    {
        $dokumentasi->load('fotos');

        $lainnya = DokumentasiMagang::with('fotos')
            ->whereKeyNot($dokumentasi->id)
            ->terbaru()
            ->take(3)
            ->get();

        return view('dokumentasi.show', compact('dokumentasi', 'lainnya'));
    }
}
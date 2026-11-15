<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\DokumentasiMagang;

/** Halaman dokumentasi untuk pengunjung (tanpa login). */
class DokumentasiPublikController extends Controller
{
    /** Daftar seluruh dokumentasi, terbaru lebih dulu, dengan pagination. */
    public function index()
    {
        $dokumentasis = DokumentasiMagang::with('fotos')
            ->terbaru()
            ->paginate(12);

        return view('dokumentasi.index', compact('dokumentasis'));
    }

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
<?php

namespace App\Http\Controllers;

use App\Models\DokumentasiMagang;

/** Halaman detail dokumentasi untuk pengunjung (tanpa login). */
class DokumentasiPublikController extends Controller
{
    public function show(DokumentasiMagang $dokumentasi)
    {
        $dokumentasi->load('fotos');

        $lainnya = DokumentasiMagang::with('fotos')
            ->whereKeyNot($dokumentasi->id)
            ->latest()
            ->take(3)
            ->get();

        return view('dokumentasi.show', compact('dokumentasi', 'lainnya'));
    }
}
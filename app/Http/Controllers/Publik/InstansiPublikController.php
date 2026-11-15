<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Dinas;

/** Daftar seluruh dinas aktif untuk pengunjung (tanpa login), dengan pagination. */
class InstansiPublikController extends Controller
{
    public function __invoke()
    {
        $dinases = Dinas::aktif()
            ->orderBy('id')
            ->paginate(12);

        return view('instansi.index', compact('dinases'));
    }
}
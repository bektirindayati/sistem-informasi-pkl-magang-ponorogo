<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Surat\SuratBalasanService;
use Illuminate\Http\Request;

/** Peserta membuka/mengunduh surat balasan resmi miliknya setelah diterbitkan. */
class SuratBalasanUserController extends Controller
{
    public function __invoke(Request $request, $pendaftaran, SuratBalasanService $surat)
    {
        $milikku = $request->user()->pendaftarans()->findOrFail($pendaftaran);

        return $surat->berkasFinal($milikku, $request->boolean('unduh'));
    }
}
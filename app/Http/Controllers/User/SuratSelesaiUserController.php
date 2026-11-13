<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Surat\SuratSelesaiService;
use Illuminate\Http\Request;

class SuratSelesaiUserController extends Controller
{
    public function __invoke(Request $request, int $pendaftaran, SuratSelesaiService $surat)
    {
        $p = $request->user()->pendaftarans()->findOrFail($pendaftaran);

        return $surat->berkasFinal($p, $request->boolean('unduh'));   // 404 bila belum diterbitkan
    }
}
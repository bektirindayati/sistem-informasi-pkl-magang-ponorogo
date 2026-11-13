<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Surat\SertifikatService;
use Illuminate\Http\Request;

class SertifikatUserController extends Controller
{
    public function __invoke(Request $request, int $pendaftaran, SertifikatService $sertifikat)
    {
        $p = $request->user()->pendaftarans()->with('sertifikatFiles')->findOrFail($pendaftaran);

        return $sertifikat->berkasSertifikat($p, $request->integer('berkas') ?: null, $request->boolean('unduh'));
    }
}
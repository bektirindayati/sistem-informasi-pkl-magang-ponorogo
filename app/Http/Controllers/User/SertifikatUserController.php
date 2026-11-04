<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Surat\SertifikatService;
use Illuminate\Http\Request;

/** Peserta membuka/mengunduh sertifikat miliknya setelah diterbitkan. */
class SertifikatUserController extends Controller
{
    public function __invoke(Request $request, $pendaftaran, SertifikatService $sertifikat)
    {
        $milikku = $request->user()->pendaftarans()->findOrFail($pendaftaran);

        return $sertifikat->berkasFinal($milikku, $request->boolean('unduh'));
    }
}
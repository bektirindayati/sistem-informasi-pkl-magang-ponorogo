<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SuratBalasan\UnduhSuratBalasanRequest;
use App\Services\Surat\SuratBalasanService;

/** Peserta yang diterima mengunduh surat balasan (PDF final) setelah diterbitkan admin. */
class SuratBalasanUserController extends Controller
{
    public function __invoke(UnduhSuratBalasanRequest $request, SuratBalasanService $service)
    {
        $pendaftaran = $request->pendaftaran();
        $surat = $pendaftaran->suratBalasan;

        abort_unless($surat?->sudahTerbit() && $surat->file_final, 404, 'Surat balasan belum diterbitkan.');

        return $service->responsPdf($surat->file_final, $service->namaFile($pendaftaran), 'attachment');
    }
}
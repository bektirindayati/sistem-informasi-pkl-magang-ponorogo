<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SuratBalasan\AksesSuratBalasanRequest;
use App\Http\Requests\Admin\SuratBalasan\UnggahSuratBalasanRequest;
use App\Services\PendaftaranNotifier;
use App\Services\Surat\SuratBalasanService;
use Illuminate\Http\RedirectResponse;

/** Admin: unduh draf Word, lihat PDF tersimpan, dan atur status surat (diproses / diterbitkan). */
class SuratBalasanAdminController extends Controller
{
    public function __construct(private SuratBalasanService $surat)
    {
    }

    public function draf(AksesSuratBalasanRequest $request)
    {
        return $this->surat->draf($request->pendaftaran());
    }

    public function show(AksesSuratBalasanRequest $request)
    {
        return $this->surat->berkasFinal(
            $request->pendaftaran(),
            $request->boolean('unduh'),
            untukAdmin: true
        );
    }

    public function status(
        UnggahSuratBalasanRequest $request,
        PendaftaranNotifier $notifier
    ): RedirectResponse {
        $pendaftaran = $request->pendaftaran();
        $status = $request->validated('status_surat');

        $baruTerbit = $this->surat->simpanStatus(
            $pendaftaran,
            $status,
            $request->file('surat_balasan')
        );

        $emailGagal = $baruTerbit && ! $notifier->kirim($pendaftaran, 'surat');

        $respons = back()->with('success', $status === 'diterbitkan'
            ? 'Surat balasan berhasil diterbitkan. Pendaftar dapat mengunduhnya melalui dashboard.'
            : 'Status surat disimpan: surat sedang diproses.');

        return $emailGagal
            ? $respons->with('error', 'Surat tersimpan, tetapi email ke pendaftar gagal dikirim. Periksa pengaturan email (MAIL_*) di .env.')
            : $respons;
    }
}

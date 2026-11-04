<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sertifikat\SertifikatAdminRequest;
use App\Services\PendaftaranNotifier;
use App\Services\Surat\SertifikatService;
use Illuminate\Http\RedirectResponse;

/** Admin: unduh draf sertifikat (Word), lihat PDF tersimpan, dan atur status (diproses / diterbitkan). */
class SertifikatAdminController extends Controller
{
    public function __construct(private SertifikatService $sertifikat)
    {
    }

    public function draf(SertifikatAdminRequest $request)
    {
        return $this->sertifikat->draf($request->pendaftaran());
    }

    public function show(SertifikatAdminRequest $request)
    {
        return $this->sertifikat->berkasFinal($request->pendaftaran(), $request->boolean('unduh'), untukAdmin: true);
    }

    public function status(SertifikatAdminRequest $request, PendaftaranNotifier $notifier): RedirectResponse
    {
        $pendaftaran = $request->pendaftaran();
        $status = $request->validated('status_sertifikat');

        $baruTerbit = $this->sertifikat->simpanStatus($pendaftaran, $status, $request->file('sertifikat'));
        $emailGagal = $baruTerbit && ! $notifier->kirim($pendaftaran, 'sertifikat');

      $respons = back()->with('success', $status === 'diterbitkan'
    ? 'Sertifikat berhasil diterbitkan dan dapat diunduh oleh pendaftar melalui dashboard.'
    : 'Status sertifikat disimpan: sertifikat sedang diproses.');

        return $emailGagal
            ? $respons->with('error', 'Sertifikat tersimpan, tetapi email ke pendaftar gagal dikirim. Periksa pengaturan email (MAIL_*) di .env.')
            : $respons;
    }
}
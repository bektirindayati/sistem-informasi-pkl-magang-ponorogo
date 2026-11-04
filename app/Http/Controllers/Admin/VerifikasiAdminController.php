<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PencarianRequest;
use App\Http\Requests\Admin\Pendaftar\UpdateStatusRequest;
use App\Models\PendaftaranMagang;
use App\Services\PendaftaranNotifier;
use App\Services\Surat\SertifikatService;
use App\Services\Surat\SuratBalasanService;

/** Verifikasi berkas: daftar pengajuan masuk (+ pencarian nama) dan ubah status. */
class VerifikasiAdminController extends Controller
{
    public function index(PencarianRequest $request)
    {
        $pendaftars = PendaftaranMagang::latest()
            ->untukAdmin($request->user())
            ->sudahDikirim()
            ->cariNama($request->kata())
            ->paginate(10)
            ->withQueryString();

        return view('admin.verifikasi.index', compact('pendaftars'));
    }

    public function update(
        UpdateStatusRequest $request,
        SuratBalasanService $surat,
        SertifikatService $sertifikat,
        PendaftaranNotifier $notifier
    ) {
        $pendaftaran = $request->pendaftaran();
        $sebelumnya = $pendaftaran->status;
        $data = $request->dataStatus();
        $baru = $data['status'];

        $pendaftaran->update($data);

        // Dokumen yang sudah terbit ditarik bila statusnya tidak lagi memenuhi syarat.
        if (! in_array($baru, ['diterima', 'selesai'], true) && $pendaftaran->suratTerbit()) {
            $surat->tarik($pendaftaran);
        }
        if ($baru !== 'selesai' && $pendaftaran->sertifikatTerbit()) {
            $sertifikat->tarik($pendaftaran);
        }

        // Baru diterima -> email pemberitahuan (di dashboard, pop-up muncul otomatis).
        $perluEmail = $baru === 'diterima' && ! in_array($sebelumnya, ['diterima', 'selesai'], true);
        $emailGagal = $perluEmail && ! $notifier->kirim($pendaftaran, 'diterima');

        $respons = back()->with('success', match (true) {
            $perluEmail         => 'Pendaftaran diterima. Pendaftar sudah mendapat pemberitahuan di dashboard dan email; unduh draf surat balasan dari panel Surat Balasan.',
            $baru === 'selesai' => 'Magang ditandai selesai. Panel Sertifikat kini tersedia.',
            default             => 'Status verifikasi berkas berhasil diperbarui!',
        });

        return $emailGagal
            ? $respons->with('error', 'Status tersimpan, tetapi email notifikasi gagal dikirim. Periksa pengaturan email (MAIL_*) di .env.')
            : $respons;
    }
}
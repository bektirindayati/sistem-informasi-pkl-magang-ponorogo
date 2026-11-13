<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PencarianRequest;
use App\Http\Requests\Admin\Pendaftar\UpdateStatusRequest;
use App\Models\PendaftaranMagang;
use App\Services\PendaftaranNotifier;
use App\Services\Surat\SertifikatService;
use App\Services\Surat\SuratBalasanService;
use App\Services\Surat\SuratSelesaiService;
use Illuminate\Http\RedirectResponse;

/** Verifikasi berkas: daftar pengajuan masuk (+ pencarian nama) dan ubah status. */
class VerifikasiAdminController extends Controller
{
    /**
     * Menampilkan daftar pengajuan yang sudah dikirim.
     */
    public function index(PencarianRequest $request)
    {
        $pendaftars = PendaftaranMagang::withCount('anggotas')
            ->latest()
            ->untukAdmin($request->user())
            ->sudahDikirim()
            ->cariNama($request->kata())
            ->paginate(10)
            ->withQueryString();

        return view('admin.verifikasi.index', compact('pendaftars'));
    }

    /**
     * Memperbarui status verifikasi pendaftaran.
     */
    public function update(
    UpdateStatusRequest $request,
    SuratBalasanService $surat,
    SuratSelesaiService $selesai,
    SertifikatService $sertifikat,
    PendaftaranNotifier $notifier
) {
    $pendaftaran = $request->pendaftaran();
    $sebelumnya  = $pendaftaran->status;
    $data        = $request->dataStatus();
    $baru        = $data['status'];

    $pendaftaran->update($data);

    // Dokumen yang sudah terbit ditarik bila status tidak lagi "diterima" atau "selesai".
    if (! in_array($baru, ['diterima', 'selesai'], true)) {
        if ($pendaftaran->suratTerbit()) {
            $surat->tarik($pendaftaran);
        }
        if ($pendaftaran->suratSelesaiTerbit()) {
            $selesai->tarik($pendaftaran);
        }
        if ($pendaftaran->sertifikatTerbit()) {
            $sertifikat->tarik($pendaftaran);
        }
    }

    // Email hanya satu kali: saat pertama kali diterima.
    $baruDiterima = $baru === 'diterima' && ! in_array($sebelumnya, ['diterima', 'selesai'], true);
    $emailGagal = $baruDiterima && ! $notifier->diterima($pendaftaran);

    $respons = back()->with('success', match (true) {
        $baruDiterima => 'Pendaftaran diterima. Email notifikasi telah dijadwalkan; surat balasan dan dokumen lain dapat disiapkan di panel di bawah.',
        $baru === 'selesai' => 'Magang ditandai selesai.',
        default             => 'Status verifikasi berkas berhasil diperbarui!',
    });

    return $emailGagal
    ? $respons->with('error', 'Status tersimpan, tetapi email notifikasi gagal dijadwalkan. Periksa pengaturan email (MAIL_*) di .env.')
    : $respons;
}
}

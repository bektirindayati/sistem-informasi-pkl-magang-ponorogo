<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\PendaftaranMagang;
use App\Services\PendaftaranNotifier;
use Illuminate\Http\RedirectResponse;

trait TanggapiStatusDokumen
{
    protected function tanggapan(string $status, string $nama): RedirectResponse
    {
        return back()->with('success', $status === 'diterbitkan'
            ? "{$nama} berhasil diterbitkan. Pendaftar dapat mengunduhnya melalui dashboard."
            : "Status {$nama} disimpan: sedang diproses.");
    }

    /** Kirim email dokumen (PDF terlampir) ke pendaftar. Hanya untuk dokumen yang sudah diterbitkan. */
    protected function kirimEmail(PendaftaranMagang $p, PendaftaranNotifier $notifier, string $jenis, string $nama): RedirectResponse
    {
        $terbit = match ($jenis) {
            'balasan'    => $p->suratTerbit(),
            'selesai'    => $p->suratSelesaiTerbit(),
            'sertifikat' => $p->sertifikatTerbit(),
        };

        if (! $terbit) {
            return back()->with('error', "{$nama} belum diterbitkan, jadi emailnya belum bisa dikirim.");
        }

        return $notifier->dokumen($p, $jenis)
            ? back()->with('success', "Email {$nama} sudah dijadwalkan untuk dikirim ke pendaftar.")
            : back()->with('error', "Email {$nama} gagal dijadwalkan (alamat email pendaftar kosong atau antrean bermasalah).");
    }
}
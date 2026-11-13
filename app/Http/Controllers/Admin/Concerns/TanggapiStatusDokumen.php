<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\PendaftaranMagang;
use App\Services\PendaftaranNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait TanggapiStatusDokumen
{
    protected function tanggapan(
        Request $request,
        PendaftaranNotifier $notifier,
        PendaftaranMagang $p,
        string $jenis,
        string $status,
        string $nama
    ): RedirectResponse {
        $terbit = $status === 'diterbitkan';
        $email  = $terbit && $request->boolean('kirim_email') ? $notifier->dokumen($p, $jenis) : null;

        $respons = back()->with('success', ($terbit
            ? "{$nama} berhasil diterbitkan. Pendaftar dapat mengunduhnya melalui dashboard."
            : "Status {$nama} disimpan: sedang diproses.") . ($email ? ' Email sedang dikirim ke pendaftar.' : ''));

        return $email === false
            ? $respons->with('error', "{$nama} tersimpan, tetapi email tidak dapat dijadwalkan (alamat email kosong atau antrean bermasalah).")
            : $respons;
    }
}
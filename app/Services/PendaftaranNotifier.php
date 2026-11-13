<?php

namespace App\Services;

use App\Mail\DokumenTerbit;
use App\Mail\NotifikasiPendaftaran;
use App\Models\PendaftaranMagang;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 * Menjadwalkan email ke ketua/pendaftar lewat antrean.
 * Nilai true berarti email berhasil dijadwalkan, bukan sudah terkirim.
 */
class PendaftaranNotifier
{
    public function diterima(PendaftaranMagang $p): bool
    {
        return $this->jadwalkan($p, new NotifikasiPendaftaran($p));
    }

    /** @param 'balasan'|'selesai'|'sertifikat' $jenis */
    public function dokumen(PendaftaranMagang $p, string $jenis): bool
    {
        return $this->jadwalkan($p, new DokumenTerbit($p, $jenis));
    }

    private function jadwalkan(PendaftaranMagang $p, Mailable $surel): bool
    {
        $email = $p->user?->email;

        if (blank($email)) {
            return false;
        }

        try {
            Mail::to($email)->send($surel);   // Mailable ShouldQueue otomatis masuk antrean

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}
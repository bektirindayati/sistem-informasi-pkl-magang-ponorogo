<?php

namespace App\Services;

use App\Mail\NotifikasiPendaftaran;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\Mail;

/** Kirim email notifikasi ke pendaftar. Kegagalan email tidak boleh menggagalkan aksi admin. */
class PendaftaranNotifier
{
    /** @return bool true jika email terkirim */
    public function kirim(PendaftaranMagang $p, string $jenis): bool
    {
        $email = $p->user?->email;

        if (blank($email)) {
            return false;
        }

        try {
            Mail::to($email)->send(new NotifikasiPendaftaran($p, $jenis));

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}
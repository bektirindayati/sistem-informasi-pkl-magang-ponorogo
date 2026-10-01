<?php

namespace App\Http\Controllers\Concerns;

use App\Models\PendaftaranMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Semua urusan upload / hapus berkas pendaftaran ada di sini. */
trait KelolaBerkasPendaftaran
{
    /** Nama kolom (sekaligus nama folder) berkas pendaftaran. */
    protected function daftarBerkas(): array
    {
        return ['surat_pengantar', 'proposal'];
    }

    /** Upload berkas baru (jika ada) dan hapus berkas lama yang digantikan. */
    protected function simpanBerkas(PendaftaranMagang $pendaftaran, Request $request): void
    {
        foreach ($this->daftarBerkas() as $kolom) {
            if (! $request->hasFile($kolom)) {
                continue;
            }

            if ($pendaftaran->{$kolom}) {
                Storage::disk('public')->delete($pendaftaran->{$kolom});
            }

            $pendaftaran->{$kolom} = $request->file($kolom)->store($kolom, 'public');
        }
    }

    /** Hapus record beserta semua berkasnya. */
    protected function hapusPendaftaran(PendaftaranMagang $pendaftaran): void
    {
        foreach ($this->daftarBerkas() as $kolom) {
            if ($pendaftaran->{$kolom}) {
                Storage::disk('public')->delete($pendaftaran->{$kolom});
            }
        }

        $pendaftaran->delete();
    }
}
<?php

namespace App\Services\Surat;

use App\Models\PendaftaranMagang;

/** Surat keterangan selesai magang. Data & blok kelompok sama dengan surat balasan. */
class SuratSelesaiService extends SuratBalasanService
{
    protected function profil(): array
    {
        return [
            'file'   => 'file_surat_selesai',
            'status' => 'status_surat_selesai',
            'waktu'  => 'surat_selesai_terbit_at',
            'nama'   => 'Surat_Selesai_Magang',
            'folder' => 'surat-selesai',
            'boleh'  => ['diterima', 'selesai'],
        ];
    }

    protected function template(PendaftaranMagang $p): string
    {
        return (string) config('surat.template.selesai' . ($p->tipe === 'kelompok' ? '_kelompok' : ''));
    }
}
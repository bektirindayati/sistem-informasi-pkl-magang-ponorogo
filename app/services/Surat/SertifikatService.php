<?php

namespace App\Services\Surat;

use App\Models\PendaftaranMagang;
use Carbon\Carbon;

class SertifikatService extends DokumenService
{
    protected function profil(): array
    {
        return [
            'file'   => 'file_sertifikat',
            'status' => 'status_sertifikat',
            'waktu'  => 'sertifikat_terbit_at',
            'nama'   => 'Sertifikat',
            'folder' => 'sertifikat',
            'boleh'  => ['selesai'],
        ];
    }

    protected function template(PendaftaranMagang $p): string
    {
        return (string) config('surat.template.sertifikat');
    }

    /**
     * Data sertifikat yang diisi otomatis oleh SIMANTARA.
     * Nomor naskah dan tanda tangan dibiarkan untuk sistem Kominfo.
     */
    protected function data(PendaftaranMagang $p): array
    {
        $dinas = $p->dinas;

        return [
            // Data peserta
            'nama_lengkap' => $this->isi($p->nama_lengkap),

            // Bidang penempatan
            'bidang' => $this->isi(
                trim(
                    (string) preg_replace(
                        '/^bidang\s+/i',
                        '',
                        (string) $p->divisi
                    )
                )
            ),

            // Instansi tujuan
            'nama_dinas' => $this->namaDinasLengkap($p),

            // Periode pelaksanaan magang
            'periode' => $this->periode($p),

            // Data pejabat penandatangan
            'jabatan_pengirim' => $this->isi($dinas?->jabatan_pejabat),
            'nama_pengirim'    => $this->isi($dinas?->nama_pejabat),
            'pangkat'          => $this->isi($dinas?->pangkat_pejabat),
            'gol'              => $this->isi($dinas?->golongan_pejabat),
            'nip_pengirim'     => $this->isi($dinas?->nip_pejabat),
        ];
    }

    /**
     * Format:
     * 03 Agustus - 10 Desember 2026
     *
     * Jika tahun berbeda:
     * 03 Agustus 2026 - 10 Desember 2027
     */
    private function periode(PendaftaranMagang $p): string
    {
        if (! $p->tanggal_mulai || ! $p->tanggal_selesai) {
            return self::KOSONG;
        }

        $mulai = Carbon::parse($p->tanggal_mulai)->locale('id');
        $selesai = Carbon::parse($p->tanggal_selesai)->locale('id');

        return $mulai->year === $selesai->year
            ? $mulai->translatedFormat('d F') . ' - ' . $selesai->translatedFormat('d F Y')
            : $mulai->translatedFormat('d F Y') . ' - ' . $selesai->translatedFormat('d F Y');
    }
}

<?php

namespace App\Services;

use App\Models\PendaftaranMagang;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratBalasanGenerator
{
    /**
     * Isi template surat dengan data pendaftar.
     *
     * @return string|null path file hasil, null jika template tidak ditemukan
     */
    public function buat(PendaftaranMagang $pendaftaran): ?string
    {
        $templatePath = storage_path('app/templates/surat_template.docx');

        if (! file_exists($templatePath)) {
            return null;
        }

        $surat = new TemplateProcessor($templatePath);

        $surat->setValue('tanggal', date('d F Y'));
        $surat->setValue('universitas', $pendaftaran->universitas ?? $pendaftaran->instansi ?? 'Universitas Negeri Surabaya');
        $surat->setValue('tanggal_surat_kampus', '20 Mei 2026');
        $surat->setValue('nomor_surat_kampus', '123/UNB/DT/2026');
        $surat->setValue('nama', $pendaftaran->nama_lengkap ?? 'Nama Peserta');
        $surat->setValue('nim', $pendaftaran->nim_nisn ?? 'NIM/NISN');
        $surat->setValue('prodi', $pendaftaran->jurusan ?? 'Program Studi');
        $surat->setValue('tanggal_mulai', $pendaftaran->tanggal_mulai . ' s.d. ' . $pendaftaran->tanggal_selesai);

        $direktori = storage_path('app/public');

        if (! is_dir($direktori)) {
            mkdir($direktori, 0777, true);
        }

        $kode = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($pendaftaran->nim_nisn ?? $pendaftaran->id));
        $path = $direktori . '/Surat_Keterangan_Magang_' . $kode . '.docx';

        $surat->saveAs($path);

        return $path;
    }
}
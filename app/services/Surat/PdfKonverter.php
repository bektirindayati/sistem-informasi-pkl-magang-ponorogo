<?php

namespace App\Services\Surat;

use App\Exceptions\SuratBalasanException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Throwable;

/** Mengubah file DOCX menjadi PDF memakai LibreOffice (mode headless). */
class PdfKonverter
{
    /**
     * @param  string  $docx  path file .docx
     * @return string path file .pdf (di folder yang sama dengan $docx)
     *
     * @throws SuratBalasanException
     */
    public function ubahKePdf(string $docx): string
    {
        // Profil LibreOffice sementara & unik: mencegah bentrok jika dua admin
        // membuat draf bersamaan, dan tidak butuh folder HOME milik user web server.
        $profil = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'lo_' . Str::random(12);
        File::ensureDirectoryExists($profil);

        $proses = new Process([
            config('surat.soffice'),
            '--headless', '--norestore', '--nolockcheck', '--nodefault',
            '-env:UserInstallation=' . $this->uri($profil),
            '--convert-to', 'pdf',
            '--outdir', dirname($docx),
            $docx,
        ], null, ['HOME' => $profil], null, (float) config('surat.batas_waktu', 90));

        try {
            $proses->run();
        } catch (Throwable $e) {
            throw new SuratBalasanException('LibreOffice tidak dapat dijalankan: ' . $e->getMessage(), 0, $e);
        } finally {
            File::deleteDirectory($profil);
        }

        $pdf = preg_replace('/\.docx$/i', '.pdf', $docx);

        if (! $proses->isSuccessful() || ! is_file($pdf)) {
            $detail = trim($proses->getErrorOutput() ?: $proses->getOutput());

            throw new SuratBalasanException(
                'Konversi ke PDF gagal. Pastikan LibreOffice terpasang dan SOFFICE_PATH benar.'
                . ($detail !== '' ? ' (' . Str::limit($detail, 200) . ')' : '')
            );
        }

        return $pdf;
    }

    /** Path -> URI file:// (Linux dan Windows). */
    private function uri(string $path): string
    {
        $path = str_replace(['\\', ' '], ['/', '%20'], $path);

        return 'file://' . (str_starts_with($path, '/') ? '' : '/') . $path;
    }
}
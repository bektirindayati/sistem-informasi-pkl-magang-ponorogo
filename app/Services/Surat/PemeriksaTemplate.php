<?php

namespace App\Services\Surat;

use App\Models\TemplateSurat;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

/** Memeriksa template DOCX yang diunggah: struktur, kode, dan uji coba pengisian. */
class PemeriksaTemplate
{
    public function __construct(private ContohSurat $contoh)
    {
    }

    /** @return array{kode: string[], peringatan: string[]} */
    public function periksa(UploadedFile $berkas, string $jenis): array
    {
        $path = $berkas->getRealPath();

        $this->periksaStruktur($path);

        try {
            $kode = array_values(array_unique((new TemplateProcessor($path))->getVariables()));
        } catch (\Throwable $e) {
            $this->gagal('Template tidak dapat dibaca: ' . $e->getMessage());
        }

        $blok = array_filter($kode, fn ($k) => str_starts_with($k, 'blok_') || str_starts_with($k, '/'));
        if ($blok) {
            $this->gagal('Template tidak boleh memakai blok. Untuk daftar peserta, gunakan satu baris contoh di dalam tabel.');
        }

        $hilang = array_diff(TemplateSurat::SLOT[$jenis]['wajib'], $kode);
        if ($hilang) {
            $this->gagal('Kode wajib belum ada di template: ' . implode(', ', array_map(fn ($k) => '${' . $k . '}', $hilang)) . '.');
        }

        // Uji coba: template harus bisa diisi dengan data contoh (termasuk penggandaan baris tabel).
        try {
            @unlink($this->contoh->docx($jenis, $path, null));
        } catch (\Throwable $e) {
            $this->gagal('Template tidak dapat diisi: ' . $e->getMessage());
        }

        $dikenal = collect(config('kode_template'))->flatMap(fn ($grup) => array_keys($grup))->all();

        $peringatan = array_map(
            fn ($k) => 'Kode ${' . $k . '} tidak dikenal oleh sistem dan akan dibiarkan apa adanya. Periksa kemungkinan salah ketik.',
            array_values(array_diff($kode, $dikenal))
        );

        return ['kode' => $kode, 'peringatan' => $peringatan];
    }

    private function periksaStruktur(string $path): void
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true || $zip->locateName('word/document.xml') === false) {
            $this->gagal('Berkas bukan dokumen Word (.docx) yang valid.');
        }

        $ukuran = 0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $ukuran += $zip->statIndex($i)['size'];
        }

        $adaMakro = $zip->locateName('word/vbaProject.bin') !== false;
        $zip->close();

        if ($adaMakro) {
            $this->gagal('Template tidak boleh berisi makro.');
        }
        if ($ukuran > 50 * 1024 * 1024) {
            $this->gagal('Isi dokumen terlalu besar.');
        }
    }

    private function gagal(string $pesan): never
    {
        throw ValidationException::withMessages(['template' => $pesan]);
    }
}
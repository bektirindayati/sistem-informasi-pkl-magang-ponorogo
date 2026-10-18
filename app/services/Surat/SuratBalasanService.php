<?php

namespace App\Services\Surat;

use App\Exceptions\SuratBalasanException;
use App\Models\PendaftaranMagang;
use App\Models\SuratBalasan;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Alur surat balasan: draf -> pratinjau -> terbit (PDF final) -> diunduh peserta.
 * Perubahan dilakukan pada DATA surat, lalu draf dibuat ulang; PDF tidak diedit langsung.
 */
class SuratBalasanService
{
    public function __construct(
        private SuratBalasanGenerator $generator,
        private PdfKonverter $konverter,
    ) {
    }

    /** Surat milik pendaftaran; kalau belum ada dibuat dengan isian awal dari data pendaftar & dinas. */
    public function ambilAtauBuat(PendaftaranMagang $pendaftaran): SuratBalasan
    {
        return $pendaftaran->suratBalasan ?? $pendaftaran->suratBalasan()->create($this->dataAwal($pendaftaran));
    }

    /**
     * Simpan data surat lalu buat ulang draf PDF-nya.
     *
     * @throws SuratBalasanException jika data tersimpan tetapi PDF gagal dibuat
     */
    public function perbarui(SuratBalasan $surat, array $data): void
    {
        $surat->update($data);

        try {
            $this->buatDraf($surat);
        } catch (SuratBalasanException $e) {
            throw new SuratBalasanException('Data surat tersimpan, tetapi draf PDF gagal dibuat. ' . $e->getMessage(), 0, $e);
        }
    }

    public function buatDraf(SuratBalasan $surat): void
    {
        $tujuan = $this->folder($surat) . '/draf.pdf';

        $pdf = $this->bikinPdf($surat, draf: true);

        try {
            $this->disk()->put($tujuan, file_get_contents($pdf));
        } finally {
            @unlink($pdf);
        }

        $surat->update(['file_draf' => $tujuan]);
    }

    /**
     * Terbitkan: PDF bertanda tangan yang diunggah admin dipakai sebagai file final;
     * jika tidak ada, sistem membuat PDF final (tanpa tanda "DRAF") dari data surat.
     */
    public function terbitkan(SuratBalasan $surat, ?UploadedFile $pdfTtd, User $admin): void
    {
        $nama = 'surat-balasan-' . now()->format('YmdHis') . '.pdf';
        $tujuan = $this->folder($surat) . '/' . $nama;

        if ($pdfTtd) {
            $this->disk()->putFileAs($this->folder($surat), $pdfTtd, $nama);
        } else {
            $pdf = $this->bikinPdf($surat, draf: false);

            try {
                $this->disk()->put($tujuan, file_get_contents($pdf));
            } finally {
                @unlink($pdf);
            }
        }

        $surat->update([
            'status' => SuratBalasan::TERBIT,
            'file_final' => $tujuan,
            'diterbitkan_oleh' => $admin->id,
            'diterbitkan_pada' => now(),
        ]);
    }

    /** Kembalikan ke draf (mis. ada kesalahan setelah terbit): file final dihapus. */
    public function tarik(SuratBalasan $surat): void
    {
        if ($surat->file_final) {
            $this->disk()->delete($surat->file_final);
        }

        $surat->update([
            'status' => SuratBalasan::DRAF,
            'file_final' => null,
            'diterbitkan_oleh' => null,
            'diterbitkan_pada' => null,
        ]);
    }

    /** Kirim PDF ke browser ($disposisi: inline = tampil, attachment = unduh). */
    public function responsPdf(string $path, string $namaFile, string $disposisi = 'inline'): StreamedResponse
    {
        abort_unless($this->disk()->exists($path), 404, 'File surat tidak ditemukan.');

        return $this->disk()->response($path, $namaFile, [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-store, max-age=0',
        ], $disposisi);
    }

    public function namaFile(PendaftaranMagang $pendaftaran, string $awalan = 'Surat_Balasan'): string
    {
        return $awalan . '_' . Str::slug($pendaftaran->nama_lengkap ?: 'peserta', '_') . '.pdf';
    }

    // ---------- pembantu ----------

  private function dataAwal(PendaftaranMagang $p): array
{
    $dinas = $p->dinas;

    $jenisKegiatan = str_contains(
        (string) $p->kategori,
        'Siswa'
    )
        ? 'Praktik Kerja Lapangan'
        : 'Magang';

    return [
        'tanggal_surat' => now()
            ->timezone(config('surat.zona_waktu'))
            ->toDateString(),

        // Data instansi
        'instansi' => $p->instansi,
        'nama_instansi' => $p->instansi,

        // Data peserta
        'nama' => $p->nama_lengkap,
        'nim_nisn' => $p->nim_nisn,
        'jurusan' => $p->jurusan,
        'tanggal_mulai' => $p->tanggal_mulai,
        'tanggal_selesai' => $p->tanggal_selesai,
        'sebutan' => str_contains(
            (string) $p->kategori,
            'Siswa'
        ) ? 'siswa' : 'mahasiswa',

        // Jenis kegiatan
        'jenis_kegiatan' => $jenisKegiatan,

        // Penandatangan dari dinas
        'nama_penandatangan' => $dinas?->nama_pejabat,
        'jabatan_penandatangan' => $dinas?->jabatan_pejabat,
        'pangkat_penandatangan' => $dinas?->pangkat_pejabat,
        'nip_penandatangan' => $dinas?->nip_pejabat,
    ];
}

    /** DOCX -> PDF; berkas sementara DOCX dihapus, PDF sementara dihapus oleh pemanggil. */
    private function bikinPdf(SuratBalasan $surat, bool $draf): string
    {
        $docx = $this->generator->buat($surat, $draf);

        try {
            return $this->konverter->ubahKePdf($docx);
        } finally {
            @unlink($docx);
        }
    }

    private function disk(): FilesystemAdapter
    {
        return Storage::disk(config('surat.disk'));
    }

    private function folder(SuratBalasan $surat): string
    {
        return trim(config('surat.folder'), '/') . '/' . $surat->pendaftaran_magang_id;
    }
}
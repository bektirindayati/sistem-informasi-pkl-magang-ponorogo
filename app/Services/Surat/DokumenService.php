<?php

namespace App\Services\Surat;

use App\Models\AnggotaPendaftaran;
use App\Models\PendaftaranMagang;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dasar dokumen resmi pendaftar (surat balasan, sertifikat) dengan alur yang sama:
 *  1. draf()         : DOCX terisi otomatis dari template Word; placeholder urusan dinas dibiarkan.
 *  2. simpanStatus() : admin mengunggah PDF final dan/atau mengubah status (diproses / diterbitkan).
 *  3. berkasFinal()  : pendaftar mengunduh PDF resmi setelah diterbitkan.
 */
abstract class DokumenService
{
    protected const KOSONG = '..........';

    /**
     * 'file'   => kolom PDF, 'status' => kolom status, 'waktu' => kolom waktu terbit,
     * 'nama'   => awalan nama berkas, 'folder' => folder simpan,
     * 'boleh'  => status pendaftaran yang boleh memakai dokumen ini.
     */
    abstract protected function profil(): array;

    abstract protected function template(PendaftaranMagang $p): string;

    /** Jenis template di pengaturan: balasan, balasan_kelompok, selesai, selesai_kelompok, sertifikat. */
    abstract protected function jenisTemplate(PendaftaranMagang $p): string;

    /** Placeholder grup A (diisi sistem). Placeholder urusan dinas tidak boleh disentuh. */
    abstract protected function data(PendaftaranMagang $p): array;

    /** Hook untuk turunan: olah blok/baris template sebelum placeholder biasa diisi. */
    protected function olah(TemplateProcessor $dokumen, PendaftaranMagang $p): void
    {
    }

    /** Ketua + anggota, urut: ketua selalu pertama. */
    protected function pesertaDokumen(PendaftaranMagang $p): Collection
    {
        $p->loadMissing('anggotas');

        return $p->anggotas
            ->map(fn ($a) => $a->only(AnggotaPendaftaran::KOLOM))
            ->prepend($p->only(AnggotaPendaftaran::KOLOM))
            ->values();
    }

    protected function pastikanBoleh(PendaftaranMagang $p): void
    {
        abort_unless(
            in_array($p->status, $this->profil()['boleh'], true),
            404,
            'Draf belum tersedia untuk status pendaftaran ini.'
        );
    }

    protected function pathTemplate(PendaftaranMagang $p): string
    {
        $template = app(TemplateResolver::class)->path(
            $this->jenisTemplate($p),
            $p->dinas_id
        ) ?: $this->template($p);

        throw_unless(
            is_file($template),
            \RuntimeException::class,
            'Template tidak ditemukan di: ' . ($template ?: '(pengaturan config/surat.php kosong)')
        );

        return $template;
    }

    /** Mengisi template, mengembalikan path DOCX sementara. */
    protected function isiTemplate(string $template, array $data, ?callable $olah = null): string
    {
        // Nama yang memuat tanda & atau < tidak boleh merusak dokumen.
        Settings::setOutputEscapingEnabled(true);

        $dokumen = new TemplateProcessor($template);

        if ($olah) {
            $olah($dokumen);
        }

        foreach ($data as $placeholder => $nilai) {
            $dokumen->setValue($placeholder, $nilai);
        }

        $sementara = tempnam(sys_get_temp_dir(), 'dok');
        $dokumen->saveAs($sementara);

        return $sementara;
    }

    /** Mengisi template tertentu dengan data pendaftaran (pratinjau dan uji coba); mengembalikan path DOCX sementara. */
    public function isiDari(string $template, PendaftaranMagang $p): string
    {
        return $this->isiTemplate(
            $template,
            $this->data($p),
            fn ($dokumen) => $this->olah($dokumen, $p)
        );
    }

    /** Unduh draf DOCX untuk dilengkapi dan ditandatangani dinas. */
    public function draf(PendaftaranMagang $p): BinaryFileResponse
    {
        $this->pastikanBoleh($p);

        $sementara = $this->isiTemplate(
            $this->pathTemplate($p),
            $this->data($p),
            fn ($dokumen) => $this->olah($dokumen, $p)
        );

        return response()
            ->download($sementara, $this->namaBerkas($p, 'docx'))
            ->deleteFileAfterSend(true);
    }

    /**
     * PDF resmi: tampil di tab baru, atau unduh jika $unduh = true.
     * Pendaftar hanya bisa membuka dokumen yang sudah diterbitkan; admin ($untukAdmin)
     * boleh melihat PDF yang sudah diunggah meski belum diterbitkan.
     */
    public function berkasFinal(
        PendaftaranMagang $p,
        bool $unduh = false,
        bool $untukAdmin = false
    ): Response {
        $path = $p->{$this->profil()['file']};

        abort_unless(
            $untukAdmin ? filled($path) : $this->terbit($p),
            404,
            'Dokumen belum tersedia.'
        );

        $disk = Storage::disk(PendaftaranMagang::DISK_SURAT);

        abort_unless(
            $disk->exists($path),
            404,
            'File tidak ditemukan. Hubungi admin.'
        );

        $nama = $this->namaBerkas($p, 'pdf');

        $respons = $unduh
            ? $disk->download($path, $nama)
            : $disk->response($path, $nama, ['Content-Type' => 'application/pdf']);

        $respons->headers->set('Cache-Control', 'no-store, max-age=0');

        return $respons;
    }

    /**
     * Simpan status dokumen. $pdf (opsional) menggantikan PDF yang tersimpan.
     * "diterbitkan" membuat dokumen bisa diunduh pendaftar;
     * "diproses" menariknya tanpa menghapus PDF.
     */
    public function simpanStatus(
        PendaftaranMagang $p,
        string $status,
        ?UploadedFile $pdf = null
    ): void {
        $k = $this->profil();
        $sudahTerbit = $this->terbit($p);
        $disk = Storage::disk(PendaftaranMagang::DISK_SURAT);

        $lama = $p->{$k['file']};
        $pathBaru = null;

        // Simpan PDF baru jika admin mengunggah penggantinya.
        if ($pdf) {
            $pathBaru = $pdf->store($k['folder'], PendaftaranMagang::DISK_SURAT);

            if (! $pathBaru) {
                throw new \RuntimeException('PDF gagal disimpan.');
            }

            $p->forceFill([$k['file'] => $pathBaru]);
        }

        $pathFinal = $p->{$k['file']};

        // Dokumen tidak boleh diterbitkan tanpa PDF yang benar-benar tersedia.
        if (
            $status === 'diterbitkan'
            && (! filled($pathFinal) || ! $disk->exists($pathFinal))
        ) {
            if ($pathBaru) {
                $disk->delete($pathBaru);
            }

            throw ValidationException::withMessages([
                $k['status'] => 'Dokumen tidak dapat diterbitkan karena PDF final belum tersedia.',
            ]);
        }

        $terbitkan = $status === 'diterbitkan';
        $baru = $terbitkan && (! $sudahTerbit || $pdf !== null);

        $p->forceFill([
            $k['status'] => $terbitkan ? 'diterbitkan' : 'diproses',
            $k['waktu']  => $terbitkan ? ($baru ? now() : $p->{$k['waktu']}) : null,
        ])->save();

        // Hapus PDF lama setelah perubahan database berhasil disimpan.
        if ($pathBaru && $lama && $lama !== $pathBaru) {
            $disk->delete($lama);
        }
    }

    /** Tarik dari pendaftar: status kembali "diproses", PDF tetap tersimpan. */
    public function tarik(PendaftaranMagang $p): void
    {
        $k = $this->profil();

        $p->forceFill([
            $k['status'] => 'diproses',
            $k['waktu'] => null,
        ])->save();
    }

    // ---------- Pembantu untuk turunan ----------

    protected function terbit(PendaftaranMagang $p): bool
    {
        $k = $this->profil();

        return $p->{$k['status']} === 'diterbitkan'
            && filled($p->{$k['file']});
    }

    protected function namaBerkas(PendaftaranMagang $p, string $ekstensi): string
    {
        return $this->profil()['nama']
            . '_'
            . Str::slug($p->nama_lengkap ?: 'peserta', '_')
            . '.'
            . $ekstensi;
    }

    protected function isi(?string $nilai): string
    {
        return filled($nilai) ? (string) $nilai : self::KOSONG;
    }

    protected function tanggal($nilai, string $format = 'j F Y'): string
    {
        return $nilai
            ? Carbon::parse($nilai)->locale('id')->translatedFormat($format)
            : self::KOSONG;
    }

    protected function namaDinas(PendaftaranMagang $p): string
    {
        return (string) ($p->dinas_tujuan ?: ($p->dinas?->nama_dinas ?? ''));
    }

    /** "Dinas ... Kabupaten Ponorogo", tanpa mengulang bila nama dinas sudah memuat wilayah. */
    protected function namaDinasLengkap(PendaftaranMagang $p): string
    {
        $dinas = $this->namaDinas($p);
        $wilayah = (string) config('surat.wilayah');

        return $wilayah !== ''
            && ! str_contains(mb_strtolower($dinas), mb_strtolower($wilayah))
                ? trim($dinas . ' ' . $wilayah)
                : $dinas;
    }
}

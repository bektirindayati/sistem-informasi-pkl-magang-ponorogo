<?php

namespace App\Services\Surat;

use App\Models\PendaftaranMagang;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Validation\ValidationException; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SertifikatService extends DokumenService
{
    protected function profil(): array
    {
        return [
            'file' => null,
            'status' => 'status_sertifikat',
            'waktu'  => 'sertifikat_terbit_at',
            'nama'   => 'Sertifikat',
            'folder' => 'sertifikat',
            'boleh'  => ['diterima', 'selesai'],
        ];
    }

    protected function jenisTemplate(PendaftaranMagang $p): string
    {
        return 'sertifikat';
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
     * Kelompok: ZIP berisi satu DOCX per peserta.
     * Mandiri: DOCX biasa.
     */
    public function draf(PendaftaranMagang $p): BinaryFileResponse
    {
        if ($p->tipe !== 'kelompok') {
            return parent::draf($p);
        }

        $this->pastikanBoleh($p);
        $template = $this->pathTemplate($p);
        $data = $this->data($p);

        $zipPath = tempnam(sys_get_temp_dir(), 'zip');
        $zip = new \ZipArchive();

        throw_unless(
            $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true,
            \RuntimeException::class,
            'Berkas ZIP gagal dibuat.'
        );

        $sementara = [];

        try {
            foreach ($this->pesertaDokumen($p) as $i => $peserta) {
                $file = $this->isiTemplate($template, array_merge($data, [
                    'nama_lengkap' => $this->isi($peserta['nama_lengkap']),
                ]));

                $sementara[] = $file;

                $namaPeserta = Str::slug(
                    $peserta['nama_lengkap'] ?: 'peserta',
                    '_'
                );

                $zip->addFile(
                    $file,
                    sprintf('%02d_Sertifikat_%s.docx', $i + 1, $namaPeserta)
                );
            }

            $zip->close();
        } catch (\Throwable $e) {
            $zip->close();

            foreach ($sementara as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }

            @unlink($zipPath);

            throw $e;
        }

        foreach ($sementara as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        return response()
            ->download(
                $zipPath,
                'Sertifikat_' . Str::slug($p->nama_lengkap ?: 'kelompok', '_') . '_kelompok.zip'
            )
            ->deleteFileAfterSend(true);
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
    protected function terbit(PendaftaranMagang $p): bool
{
    return $p->sertifikatTerbit();
}

/**
 * @param array<string, \Illuminate\Http\UploadedFile|null> $pdfs  kunci peserta ('ketua' atau id anggota) => PDF
 * @param string[] $hapus  kunci peserta yang sertifikatnya dihapus
 */
public function simpanSertifikat(PendaftaranMagang $p, string $status, array $pdfs = [], array $hapus = []): void
{
    $k    = $this->profil();
    $disk = Storage::disk(PendaftaranMagang::DISK_SURAT);

    $p->load('anggotas', 'sertifikatFiles');
    $sudahTerbit = $p->sertifikatTerbit();
    $peserta     = $p->pesertaSertifikat()->keyBy('kunci');

    // Hanya peserta milik pendaftaran ini yang diterima; entri kosong diabaikan.
    $pdfs  = collect($pdfs)->filter(fn ($f, $kunci) => $f && $peserta->has($kunci))->all();
    $hapus = collect($hapus)->map(fn ($v) => (string) $v)->filter(fn ($v) => $peserta->has($v))->all();

    $jumlah = $peserta->filter(fn ($x, $kunci) => isset($pdfs[$kunci])
        || ($x['file'] && ! in_array((string) $kunci, $hapus, true)))->count();

    if ($status === 'diterbitkan' && $jumlah === 0) {
        throw ValidationException::withMessages([
            $k['status'] => 'Sertifikat tidak dapat diterbitkan karena belum ada PDF.',
        ]);
    }

    $baru  = [];
    $buang = [];

    try {
        DB::transaction(function () use ($p, $peserta, $pdfs, $hapus, $status, $k, $sudahTerbit, &$baru, &$buang) {
            foreach ($peserta as $kunci => $x) {
                $ganti = isset($pdfs[$kunci]);

                if ($x['file'] && ($ganti || in_array((string) $kunci, $hapus, true))) {
                    $buang[] = $x['file']->file;
                    $x['file']->delete();
                }

                if ($ganti) {
                    $path = $pdfs[$kunci]->store($k['folder'], PendaftaranMagang::DISK_SURAT);
                    throw_unless($path, \RuntimeException::class, 'PDF gagal disimpan.');
                    $baru[] = $path;

                    $nama = Str::limit($pdfs[$kunci]->getClientOriginalName(), 150, '');

                    $p->sertifikatFiles()->create([
                        'file'                   => $path,
                        'nama'                   => Str::endsWith(Str::lower($nama), '.pdf') ? $nama : $nama . '.pdf',
                        'anggota_pendaftaran_id' => (string) $kunci === 'ketua' ? null : (int) $kunci,
                    ]);
                }
            }

            $terbitkan = $status === 'diterbitkan';
            $perbarui  = $terbitkan && (! $sudahTerbit || $pdfs !== []);

            $p->forceFill([
                $k['status'] => $terbitkan ? 'diterbitkan' : 'diproses',
                $k['waktu']  => $terbitkan ? ($perbarui ? now() : $p->{$k['waktu']}) : null,
            ])->save();
        });
    } catch (\Throwable $e) {
        $disk->delete($baru);

        throw $e;
    }

    $disk->delete($buang);   // setelah database berhasil
    $p->unsetRelation('sertifikatFiles');
}

public function berkasSertifikat(PendaftaranMagang $p, ?int $berkasId, bool $unduh = false, bool $untukAdmin = false): Response
{
    abort_unless($untukAdmin || $p->sertifikatTerbit(), 404, 'Sertifikat belum tersedia.');

    $files = $p->sertifikatFiles;
    $f     = $berkasId ? $files->firstWhere('id', $berkasId) : ($files->count() === 1 ? $files->first() : null);

    if (! $f) {
        abort_if($files->isEmpty() || $berkasId, 404, 'Berkas tidak ditemukan.');

        return $this->arsip($p);
    }

    $disk = Storage::disk(PendaftaranMagang::DISK_SURAT);
    abort_unless($disk->exists($f->file), 404, 'File tidak ditemukan. Hubungi admin.');

    $pemilik = $p->pesertaSertifikat()->first(fn ($x) => $x['file']?->id === $f->id);
    $nama    = 'Sertifikat_' . Str::slug($pemilik['nama'] ?? pathinfo($f->nama, PATHINFO_FILENAME), '_') . '.pdf';

    $respons = $unduh
        ? $disk->download($f->file, $nama)
        : $disk->response($f->file, $nama, ['Content-Type' => 'application/pdf']);

    $respons->headers->set('Cache-Control', 'no-store, max-age=0');

    return $respons;
}

public function arsip(PendaftaranMagang $p): BinaryFileResponse
{
    $disk    = Storage::disk(PendaftaranMagang::DISK_SURAT);
    $zipPath = tempnam(sys_get_temp_dir(), 'zip');
    $zip     = new \ZipArchive();

    throw_unless(
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true,
        \RuntimeException::class,
        'Berkas ZIP gagal dibuat.'
    );

    foreach ($p->pesertaSertifikat()->filter(fn ($x) => $x['file'])->values() as $i => $x) {
        if ($disk->exists($x['file']->file)) {
            $zip->addFile($disk->path($x['file']->file), sprintf('%02d_Sertifikat_%s.pdf', $i + 1, Str::slug($x['nama'], '_')));
        }
    }

    $zip->close();

    return response()
        ->download($zipPath, 'Sertifikat_' . Str::slug($p->nama_lengkap ?: 'kelompok', '_') . '.zip')
        ->deleteFileAfterSend(true);
}}

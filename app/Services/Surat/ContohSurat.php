<?php

namespace App\Services\Surat;

use App\Models\AnggotaPendaftaran;
use App\Models\Dinas;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

/** Surat contoh dari data fiktif untuk pratinjau dan uji coba template. */
class ContohSurat
{
    private const LAYANAN = [
        'balasan'          => SuratBalasanService::class,
        'balasan_kelompok' => SuratBalasanService::class,
        'selesai'          => SuratSelesaiService::class,
        'selesai_kelompok' => SuratSelesaiService::class,
        'sertifikat'       => SertifikatService::class,
    ];

    /** @return string path DOCX sementara */
    public function docx(string $jenis, string $templatePath, ?Dinas $dinas): string
    {
        return app(self::LAYANAN[$jenis])->isiDari($templatePath, $this->pendaftaran($jenis, $dinas ?? $this->dinasContoh()));
    }

    /** DOCX -> PDF (isi berkas). Butuh LibreOffice; atur LIBREOFFICE_PATH di .env bila tidak ada di PATH. */
    public function pdf(string $docx): string
    {
        $kerja = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pratinjau_' . Str::random(10);
        File::makeDirectory($kerja . '/profil', 0777, true);

        try {
            copy($docx, $kerja . '/contoh.docx');

            $profil = str_replace('\\', '/', $kerja . '/profil');
            $proses = new Process([
                (string) config('surat.libreoffice', 'soffice'),
                '-env:UserInstallation=file://' . (str_starts_with($profil, '/') ? '' : '/') . $profil,
                '--headless', '--norestore', '--nologo',
                '--convert-to', 'pdf', '--outdir', $kerja, $kerja . '/contoh.docx',
            ]);
            $proses->setTimeout(100);
            $proses->run();

            $pdf = $kerja . '/contoh.pdf';

            if (! is_file($pdf)) {
                throw new \RuntimeException(
                    'LibreOffice tidak dapat membuat PDF. Pastikan terpasang dan LIBREOFFICE_PATH di .env benar. '
                    . Str::limit(trim($proses->getErrorOutput() ?: $proses->getOutput()), 200)
                );
            }

            return file_get_contents($pdf);
        } finally {
            File::deleteDirectory($kerja);
        }
    }

    private function pendaftaran(string $jenis, Dinas $dinas): PendaftaranMagang
    {
        $kelompok = str_ends_with($jenis, '_kelompok');

        $p = new PendaftaranMagang([
            'tipe'                    => $kelompok ? 'kelompok' : 'mandiri',
            'kategori'                => 'Mahasiswa',
            'nama_lengkap'            => 'Contoh Nama Ketua',
            'nim_nisn'                => '24050970001',
            'instansi'                => 'Universitas Contoh',
            'jurusan'                 => 'S1 Teknik Informatika',
            'no_hp'                   => '081234567890',
            'dinas_tujuan'            => $dinas->nama_dinas,
            'divisi'                  => 'Informasi dan Komunikasi Publik',
            'tanggal_mulai'           => now()->addDays(14)->toDateString(),
            'tanggal_selesai'         => now()->addDays(104)->toDateString(),
            'nomor_surat_pengantar'   => 'B/123/UN.01/2026',
            'tanggal_surat_pengantar' => now()->subDays(7)->toDateString(),
            'jabatan_penerima'        => 'Direktur Pendidikan',
            'kota_tujuan'             => 'Surabaya',
        ]);

        $anggota = collect($kelompok ? [['Contoh Anggota Satu', '24050970002'], ['Contoh Anggota Dua', '24050970003']] : [])
            ->map(fn ($a) => new AnggotaPendaftaran([
                'nama_lengkap' => $a[0], 'nim_nisn' => $a[1], 'instansi' => 'Universitas Contoh',
                'jurusan' => 'S1 Teknik Informatika', 'no_hp' => '081200000000', 'nomor_surat_pengantar' => 'B/124/UN.01/2026',
            ]));

        return $p->setRelation('dinas', $dinas)->setRelation('anggotas', $anggota);
    }

    private function dinasContoh(): Dinas
    {
        return (new Dinas)->forceFill([
            'nama_dinas'       => 'Dinas Contoh Kabupaten Ponorogo',
            'alamat'           => 'Jl. Contoh Nomor 1, Ponorogo, Jawa Timur 63411',
            'telepon'          => '0352-000000',
            'faksimile'        => '0352-000001',
            'website'          => 'https://contoh.ponorogo.go.id',
            'email'            => 'dinas@ponorogo.go.id',
            'jabatan_pejabat'  => 'Kepala Dinas Contoh',
            'nama_pejabat'     => 'Nama Pejabat, S.Kom., M.M.',
            'pangkat_pejabat'  => 'Pembina Utama Muda',
            'golongan_pejabat' => 'IV/c',
            'nip_pejabat'      => '19700101 199501 1 001',
        ]);
    }
}
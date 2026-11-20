<?php

namespace App\Services\Surat;

use App\Models\PendaftaranMagang;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Surat balasan magang. Alurnya ada di DokumenService.
 *
 * Isi surat    : resources/templates/surat_balasan_magang.docx
 * Pengaturan   : config/surat.php
 * Kop          : data Dinas (alamat, telepon, faksimile, website, email)
 * Penandatangan: data pejabat dari profil Dinas.
 */
class SuratBalasanService extends DokumenService
{
    protected function profil(): array
    {
        return [
            'file'   => 'file_surat_balasan',
            'status' => 'status_surat',
            'waktu'  => 'surat_balasan_terbit_at',
            'nama'   => 'Surat_Balasan',
            'folder' => 'surat-balasan',
            'boleh'  => ['diterima', 'selesai'],
        ];
    }

    /**
     * Template siswa (PKL) bila tersedia, selain itu template magang.
     */
    protected function template(PendaftaranMagang $p): string
    {
        $pkl   = str_contains((string) $p->kategori, 'Siswa') && config('surat.template.pkl');
        $kunci = ($pkl ? 'pkl' : 'magang') . ($p->tipe === 'kelompok' ? '_kelompok' : '');

        return (string) config("surat.template.{$kunci}");
    }

    /**
     * Menyiapkan data untuk mengisi placeholder template DOCX.
     */
    protected function data(PendaftaranMagang $p): array
    {
        $dinas = $p->dinas;

        return [
            // =========================================
            // KOP SURAT (PROFIL DINAS)
            // =========================================
            'kop_nama_dinas' => Str::upper($this->namaDinas($p)),
            'kop_alamat'     => $this->satuBaris($dinas?->alamat),

            'kop_kontak' => $this->gabung([
                filled($dinas?->telepon)
                    ? 'Telepon ' . $dinas->telepon
                    : null,

                filled($dinas?->faksimile)
                    ? 'Faksimile ' . $dinas->faksimile
                    : null,
            ]),

            'kop_web_email' => $this->gabung([
                filled($dinas?->website)
                    ? 'Laman ' . $this->tanpaSkema($dinas->website)
                    : null,

                filled($dinas?->email)
                    ? 'Pos-el ' . $dinas->email
                    : null,
            ]),

            // =========================================
            // PEJABAT PENANDATANGAN SURAT BALASAN
            // =========================================
            'jabatan_pengirim' => $this->isi($dinas?->jabatan_pejabat),
            'nama_pengirim'    => $this->isi($dinas?->nama_pejabat),
            'pangkat'          => $this->isi($dinas?->pangkat_pejabat),
            'gol'              => $this->isi($dinas?->golongan_pejabat),
            'nip_pengirim'     => $this->isi($dinas?->nip_pejabat),

            // =========================================
// SURAT: TUJUAN DAN RUJUKAN SURAT PENGANTAR
// =========================================
'tanggal_surat' => $this->tanggal(today()),

// Yth.: jabatan penerima, lalu nama kampus/sekolah di baris bawah.
'nama_penerima' => $this->isi(
    implode("\n", array_filter([$p->jabatan_penerima, $p->instansi]))
),

'kota_tujuan' => Str::upper($this->isi($p->kota_tujuan)),

            'pengirim_surat' => $this->isi(
                trim($p->jabatan_penerima . ' ' . $p->instansi)
            ),

            'tgl_bln_thn'         => $this->tanggal($p->tanggal_surat_pengantar),
            'nomor_suratpengirim' => $this->isi($p->nomor_surat_pengantar),

            // =========================================
            // DATA PENDAFTAR DAN PERIODE MAGANG
            // =========================================
            'nama_dinas'      => $this->namaDinasLengkap($p),
            'nama_lengkap'    => $this->isi($p->nama_lengkap),
            'nim_nisn'        => $this->isi($p->nim_nisn),
            'jurusan'         => $this->isi($p->jurusan),
            'instansi'        => $this->isi($p->instansi),
            'tanggal_mulai'   => $this->tanggal($p->tanggal_mulai),
            'tanggal_selesai' => $this->tanggal($p->tanggal_selesai),
        ];
    }

    /**
     * Mengolah blok template sesuai jenis pendaftaran.
     */
    protected function olah(TemplateProcessor $dokumen, PendaftaranMagang $p): void
    {
        if ($p->tipe !== 'kelompok') {
            return;
        }

        $dokumen->cloneRowAndSetValues('no', $this->pesertaDokumen($p)->map(fn ($o, $i) => [
            'no'               => (string) ($i + 1),
            'nama_anggota'     => $this->isi($o['nama_lengkap']),
            'nim_anggota'      => $this->isi($o['nim_nisn']),
            'jurusan_anggota'  => $this->isi($o['jurusan']),
            'instansi_anggota' => $this->isi($o['instansi']),
        ])->all());
    }
    /**
     * Menghilangkan skema http/https dan garis miring terakhir dari URL.
     */
    private function tanpaSkema(string $url): string
    {
        return rtrim(
            (string) preg_replace('#^https?://#i', '', trim($url)),
            '/'
        );
    }

    /**
     * Mengubah teks dengan beberapa baris menjadi satu baris.
     */
    private function satuBaris(?string $teks): string
    {
        return trim(
            (string) preg_replace('/\s+/', ' ', (string) $teks)
        );
    }

    /**
     * Menggabungkan informasi yang tersedia dengan pemisah koma.
     */
    private function gabung(array $bagian): string
    {
        return implode(', ', array_filter($bagian));
    }
}

<?php

namespace App\Services\Surat;

use App\Exceptions\SuratBalasanException;
use App\Models\SuratBalasan;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

/** Mengisi template DOCX dengan data surat. Semua nilai berasal dari tabel, tidak ada yang ditulis tetap. */
class SuratBalasanGenerator
{
    private const KOSONG = '..........';

    /**
     * @return string path file .docx sementara (hapus setelah dipakai)
     *
     * @throws SuratBalasanException
     */
    public function buat(SuratBalasan $surat, bool $draf): string
    {
        $template = config('surat.template');

        if (! is_file($template)) {
            throw new SuratBalasanException("Template surat tidak ditemukan: {$template}");
        }

        // Nilai di-escape sendiri di bawah (karakter seperti & atau < merusak XML).
        Settings::setOutputEscapingEnabled(false);

        $dokumen = new TemplateProcessor($template);

        foreach ($this->nilai($surat, $draf) as $kunci => $isi) {
            $dokumen->setValue($kunci, htmlspecialchars((string) $isi, ENT_XML1 | ENT_QUOTES, 'UTF-8'));
        }

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'surat_' . Str::random(16) . '.docx';
        $dokumen->saveAs($path);

        return $path;
    }

    /** @return array<string, string> placeholder => isi */
    private function nilai(SuratBalasan $s, bool $draf): array
    {
        $p = $s->pendaftaran;
        $dinas = $p->dinas;

        $namaDinas = $p->dinas_tujuan ?: ($dinas?->nama_dinas ?? '');
        $siswa = str_contains((string) $p->kategori, 'Siswa');

        $mulai = $this->tanggal($p->tanggal_mulai);
        $selesai = $this->tanggal($p->tanggal_selesai);

        return [
            'tanda_draf'      => $draf ? 'DRAF - BELUM DITERBITKAN' : '',

            // Kop surat
            'kop_nama_dinas'  => Str::upper($namaDinas),
            'kop_alamat'      => $this->satuBaris($dinas?->alamat),
            'kop_kontak'      => $this->gabung([
                filled($dinas?->telepon) ? 'Telepon ' . $dinas->telepon : null,
                filled($dinas?->faksimile) ? 'Faksimile ' . $dinas->faksimile : null,
            ]),
            'kop_web_email'   => $this->gabung([
                filled($dinas?->website) ? 'Laman ' . $this->tanpaSkema($dinas->website) : null,
                filled($dinas?->email) ? 'Pos-el ' . $dinas->email : null,
            ]),

            // Surat
            'tanggal_surat'   => $this->tanggal($s->tanggal_surat) ?: self::KOSONG,
            'nomor_surat'     => $s->nomor_surat ?: self::KOSONG,
            'judul_surat'     => 'SURAT KETERANGAN ' . Str::upper($s->jenis_kegiatan),
            'nama_instansi'   => $s->nama_instansi ?: self::KOSONG,
            'kota_instansi'   => $s->kota_instansi ?: self::KOSONG,
            'rujukan'         => $this->rujukan($s, $namaDinas),

            // Identitas peserta
            'label_nama'        => 'Nama',
            'nama'              => $p->nama_lengkap ?: self::KOSONG,
            'label_nomor_induk' => $siswa ? 'NISN' : 'NIM',
            'nomor_induk'       => $p->nim_nisn ?: self::KOSONG,
            'label_jurusan'     => $siswa ? 'Jurusan' : 'Prodi',
            'jurusan'           => $p->jurusan ?: self::KOSONG,

            // Isi
            'sebutan_peserta' => $siswa ? 'siswa' : 'mahasiswa',
            'jenis_kegiatan'  => $s->jenis_kegiatan,
            'nama_dinas'      => $namaDinas,
            'periode'         => ($mulai && $selesai) ? "{$mulai} s.d. {$selesai}" : ($mulai ?: self::KOSONG),

            // Penandatangan
            'jabatan_penandatangan' => $s->jabatan_penandatangan ?: self::KOSONG,
            'nama_penandatangan'    => $s->nama_penandatangan ?: self::KOSONG,
            'pangkat_penandatangan' => (string) $s->pangkat_penandatangan,
            'nip_penandatangan'     => filled($s->nip_penandatangan) ? 'NIP. ' . $s->nip_penandatangan : '',
        ];
    }

    /** Kalimat rujukan; bentuknya menyesuaikan ada tidaknya nomor & tanggal surat permohonan. */
    private function rujukan(SuratBalasan $s, string $namaDinas): string
    {
        $instansi = $s->nama_instansi ?: self::KOSONG;
        $perihal = "perihal Permohonan Izin {$s->jenis_kegiatan} di {$namaDinas} Kabupaten Ponorogo atas nama:";

        if (filled($s->nomor_surat_pemohon) && $s->tanggal_surat_pemohon) {
            $pengirim = trim(($s->jabatan_pemohon ? $s->jabatan_pemohon . ' ' : '') . $instansi);

            return "Berdasarkan surat dari {$pengirim} tanggal {$this->tanggal($s->tanggal_surat_pemohon)} "
                . "Nomor : {$s->nomor_surat_pemohon} {$perihal}";
        }

        return "Berdasarkan permohonan dari {$instansi} {$perihal}";
    }

    /** Tanggal bahasa Indonesia ("13 Oktober 2026"), tidak bergantung pada APP_LOCALE. */
    private function tanggal($nilai): string
    {
        if (! $nilai) {
            return '';
        }

        return Carbon::parse($nilai)
            ->timezone(config('surat.zona_waktu'))
            ->locale('id')
            ->translatedFormat('j F Y');
    }

    /** "https://kominfo.ponorogo.go.id/" -> "kominfo.ponorogo.go.id" (gaya penulisan kop surat). */
    private function tanpaSkema(string $url): string
    {
        return rtrim((string) preg_replace('#^https?://#i', '', trim($url)), '/');
    }

    private function satuBaris(?string $teks): string
    {
        return trim((string) preg_replace('/\s+/', ' ', (string) $teks));
    }

    private function gabung(array $bagian): string
    {
        return implode(', ', array_filter($bagian));
    }
}
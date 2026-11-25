<?php

namespace App\Models;

use App\Models\Concerns\ScopePendaftaranAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PendaftaranMagang extends Model
{
    use HasFactory, ScopePendaftaranAdmin;

    /** Disk berkas yang diunggah peserta (surat pengantar & proposal). */
    public const DISK_BERKAS = 'public';

    /** Disk PDF surat resmi: privat, hanya bisa diakses lewat controller. */
    public const DISK_SURAT = 'local';

    public const KOLOM_BERKAS = ['surat_pengantar', 'proposal', 'cv'];

    public const STATUS_BOLEH_EDIT = ['draft', 'revisi'];

    public const STATUS_BOLEH_HAPUS = ['draft', 'pending', 'revisi', 'ditolak'];

    /** Nilai tersimpan => label tampilan. */
    public const KATEGORI = [
        'Mahasiswa'             => 'Mahasiswa (Magang Kuliah)',
        'Siswa (SMK/Sederajat)' => 'Siswa (SMK/Sederajat)',
    ];

    /** Status yang tidak boleh membuat pengajuan baru lewat form tambah. */
    public const PESAN_TERKUNCI = [
        'pending'  => 'Pendaftaran kamu masih dalam proses verifikasi.',
        'diterima' => 'Pendaftaran kamu sudah diterima dan belum dapat membuat pendaftaran baru.',
        'revisi'   => 'Pendaftaran kamu perlu direvisi terlebih dahulu sebelum mengirim ulang.',
    ];

    public const TIPE = [
        'mandiri'  => 'Mandiri / Sendiri',
        'kelompok' => 'Berkelompok',
    ];

    public const MAKS_PESERTA = 7;
    public const MAKS_ANGGOTA = self::MAKS_PESERTA - 1;

    protected $fillable = [
        'user_id',
        'dinas_id',
        'instansi_bidang_id',
        'tipe',

        'kategori',
        'nama_lengkap',
        'nim_nisn',
        'instansi',
        'jurusan',
        'no_hp',

        'tanggal_mulai',
        'tanggal_selesai',

       // Berkas dan identitas surat pengantar.
        'surat_pengantar',
        'nomor_surat_pengantar',
        'tanggal_surat_pengantar',
        'jabatan_penerima',
        'kota_tujuan',
        'proposal',

        'status',

        'dinas_tujuan',
        'divisi',

        'alasan_penolakan',
        'alasan_revisi',
    ];

    // File dan status surat resmi tidak dimasukkan ke $fillable.
    // Pengelolaannya dilakukan melalui service menggunakan forceFill().

    protected function casts(): array
    {
        return [
            'surat_balasan_terbit_at' => 'datetime',
            'sertifikat_terbit_at' => 'datetime',
            'surat_selesai_terbit_at' => 'datetime',
        ];
    }

    
protected static function booted(): void
{
    // Berkas peserta yang lama ikut terhapus saat diganti.
    static::updated(function (self $p) {
        foreach (self::KOLOM_BERKAS as $kolom) {
            if ($p->wasChanged($kolom) && $p->getOriginal($kolom)) {
                Storage::disk(self::DISK_BERKAS)->delete(
                    $p->getOriginal($kolom)
                );
            }
        }
    });

    // Hapus seluruh file sertifikat dan PDF surat pengantar anggota sebelum data pendaftaran dihapus.
static::deleting(function (self $p) {
    $p->sertifikatFiles->each(function ($file) {
        Storage::disk(self::DISK_SURAT)->delete($file->file);
    });

    Storage::disk(self::DISK_BERKAS)->delete(
        $p->anggotas->pluck('surat_pengantar')->filter()->all()
    );
});

    // Hapus berkas peserta dan PDF surat resmi saat pendaftaran dihapus.
    static::deleted(function (self $p) {
        Storage::disk(self::DISK_BERKAS)->delete(
            array_filter([$p->surat_pengantar, $p->proposal, $p->cv])
        );

        foreach ([
            'file_surat_balasan',
            'file_surat_selesai',
        ] as $kolom) {
            if (filled($p->{$kolom})) {
                Storage::disk(self::DISK_SURAT)->delete($p->{$kolom});
            }
        }
    });
}

/** Pesan jika status ini mengunci pembuatan pengajuan baru. */
public function pesanTerkunci(): ?string
{
    return self::PESAN_TERKUNCI[$this->status] ?? null;
}

/** Surat balasan resmi sudah diterbitkan. */
public function suratTerbit(): bool
{
    return $this->status_surat === 'diterbitkan'
        && filled($this->file_surat_balasan);
}

/**
 * Status surat balasan:
 * 'diproses'    = sudah diterima, PDF resmi belum diunggah
 * 'diterbitkan' = PDF resmi tersedia
 * null          = belum relevan
 */
public function statusSurat(): ?string
{
    if ($this->suratTerbit()) {
        return 'diterbitkan';
    }

    return $this->status === 'diterima' ? 'diproses' : null;
}

/** Sertifikat resmi sudah diterbitkan. */
public function sertifikatTerbit(): bool
{
    return $this->status_sertifikat === 'diterbitkan'
        && $this->sertifikatFiles()->exists();
}

/** Status sertifikat. */
public function statusSertifikat(): ?string
{
    if ($this->sertifikatTerbit()) {
        return 'diterbitkan';
    }

    return $this->status === 'selesai' ? 'diproses' : null;
}

/** Surat selesai magang sudah diterbitkan. */
public function suratSelesaiTerbit(): bool
{
    return $this->status_surat_selesai === 'diterbitkan'
        && filled($this->file_surat_selesai);
}

/** Status surat selesai magang. */
public function statusSuratSelesai(): ?string
{
    if ($this->suratSelesaiTerbit()) {
        return 'diterbitkan';
    }

    return $this->status === 'selesai' ? 'diproses' : null;
}

/** Tanggal selesai magang sudah terlewati. */
public function masaMagangBerakhir(): bool
{
    return filled($this->tanggal_selesai)
        && \Carbon\Carbon::parse($this->tanggal_selesai)->endOfDay()->isPast();
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function dinas()
{
    return $this->belongsTo(Dinas::class, 'dinas_id');
}

public function bidang()
{
    return $this->belongsTo(InstansiBidang::class, 'instansi_bidang_id');
}

public function anggotas()
{
    return $this->hasMany(AnggotaPendaftaran::class);
}

/** Daftar PDF sertifikat yang diterbitkan untuk pendaftaran ini. */
public function sertifikatFiles()
{
    return $this->hasMany(SertifikatFile::class)->orderBy('id');
}
/** Ketua (atau pendaftar mandiri) + anggota, masing-masing dengan sertifikatnya bila sudah diunggah. */
public function pesertaSertifikat(): Collection
{
    $this->loadMissing('anggotas', 'sertifikatFiles');
    $berkas = $this->sertifikatFiles->keyBy(fn ($f) => $f->anggota_pendaftaran_id ?? 'ketua');

    return $this->anggotas
        ->map(fn ($a) => [
            'kunci'   => (string) $a->id,
            'nama'    => $a->nama_lengkap,
            'peran'   => 'Anggota',
            'nim'     => $a->nim_nisn,
            'jurusan' => $a->jurusan,
            'file'    => $berkas->get($a->id),
        ])
        ->prepend([
            'kunci'   => 'ketua',
            'nama'    => $this->nama_lengkap,
            'peran'   => $this->tipe === 'kelompok' ? 'Ketua' : null,
            'nim'     => $this->nim_nisn,
            'jurusan' => $this->jurusan,
            'file'    => $berkas->get('ketua'),
        ])
        ->values();
}
}

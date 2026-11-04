<?php

namespace App\Models;

use App\Models\Concerns\ScopePendaftaranAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PendaftaranMagang extends Model
{
    use HasFactory, ScopePendaftaranAdmin;

    /** Disk berkas yang diunggah peserta (surat pengantar & proposal). */
    public const DISK_BERKAS = 'public';

    /** Disk PDF surat balasan resmi: privat, hanya bisa diakses lewat controller. */
    public const DISK_SURAT = 'local';

    public const KOLOM_BERKAS = ['surat_pengantar', 'proposal'];

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

    protected $fillable = [
        'user_id',
        'dinas_id',
        'instansi_bidang_id',

        'kategori',
        'nama_lengkap',
        'nim_nisn',
        'instansi',
        'jurusan',
        'no_hp',
        'alamat',
        'kabupaten',
        'provinsi',

        'tanggal_mulai',
        'tanggal_selesai',

        // Berkas & identitas surat pengantar (dipakai mengisi surat balasan)
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

    // PDF surat balasan (file_surat_balasan, surat_balasan_terbit_at) sengaja tidak
    // masuk $fillable: hanya diisi oleh SuratBalasanService lewat forceFill().

    protected function casts(): array
    {
        return [
            'surat_balasan_terbit_at' => 'datetime',
            'sertifikat_terbit_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Berkas lama ikut terhapus saat diganti (mis. autosave / revisi).
        static::updated(function (self $p) {
            foreach (self::KOLOM_BERKAS as $kolom) {
                if ($p->wasChanged($kolom) && $p->getOriginal($kolom)) {
                    Storage::disk(self::DISK_BERKAS)->delete($p->getOriginal($kolom));
                }
            }
        });

        // Berkas peserta dan PDF surat balasan ikut terhapus saat record dihapus.
        static::deleted(function (self $p) {
            Storage::disk(self::DISK_BERKAS)->delete(
                array_filter([$p->surat_pengantar, $p->proposal])
            );

            foreach (['file_surat_balasan', 'file_sertifikat'] as $kolom) {
    if (filled($p->{$kolom})) {
        Storage::disk(self::DISK_SURAT)->delete($p->{$kolom});
    }
}
        });
    }

    /** Pesan jika status ini mengunci pembuatan pengajuan baru, null jika boleh. */
    public function pesanTerkunci(): ?string
    {
        return self::PESAN_TERKUNCI[$this->status] ?? null;
    }

    /** Surat resmi sudah diterbitkan: statusnya "diterbitkan" dan PDF-nya ada. */
public function suratTerbit(): bool
{
    return $this->status_surat === 'diterbitkan' && filled($this->file_surat_balasan);
}

    /**
     * Status surat balasan untuk tampilan:
     * 'diproses'    = sudah diterima, PDF resmi belum diunggah
     * 'diterbitkan' = PDF resmi tersedia untuk diunduh pendaftar
     * null          = belum relevan (belum diterima)
     */
    public function statusSurat(): ?string
    {
        if ($this->suratTerbit()) {
            return 'diterbitkan';
        }

        return $this->status === 'diterima' ? 'diproses' : null;
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
    /** PDF sertifikat sudah diterbitkan: statusnya "diterbitkan" dan berkasnya ada. */
public function sertifikatTerbit(): bool
{
    return $this->status_sertifikat === 'diterbitkan' && filled($this->file_sertifikat);
}

/** 'diproses' | 'diterbitkan' setelah magang selesai; null selama belum selesai. */
public function statusSertifikat(): ?string
{
    if ($this->status !== 'selesai') {
        return null;
    }

    return $this->sertifikatTerbit() ? 'diterbitkan' : 'diproses';
}

/** Tanggal selesai magang sudah terlewati. */
public function masaMagangBerakhir(): bool
{
    return filled($this->tanggal_selesai)
        && \Carbon\Carbon::parse($this->tanggal_selesai)->endOfDay()->isPast();
}
}
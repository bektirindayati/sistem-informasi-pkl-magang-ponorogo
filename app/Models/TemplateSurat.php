<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TemplateSurat extends Model
{
    /** jenis => [judul, kunci config sistem, kode wajib]. Versi kelompok wajib punya baris tabel peserta. */
    public const SLOT = [
        'balasan'          => ['judul' => 'Surat Balasan (Mandiri)',           'config' => 'magang',           'wajib' => ['nama_lengkap']],
        'balasan_kelompok' => ['judul' => 'Surat Balasan (Kelompok)',          'config' => 'magang_kelompok',  'wajib' => ['no', 'nama_anggota']],
        'selesai'          => ['judul' => 'Surat Selesai Magang (Mandiri)',    'config' => 'selesai',          'wajib' => ['nama_lengkap']],
        'selesai_kelompok' => ['judul' => 'Surat Selesai Magang (Kelompok)',   'config' => 'selesai_kelompok', 'wajib' => ['no', 'nama_anggota']],
        'sertifikat'       => ['judul' => 'Sertifikat Magang',                 'config' => 'sertifikat',       'wajib' => ['nama_lengkap']],
    ];

    protected $fillable = [
        'dinas_id', 'jenis', 'status', 'file', 'nama_asli', 'kode', 'peringatan',
        'pratinjau', 'pratinjau_status', 'pratinjau_pesan', 'diunggah_oleh', 'diaktifkan_at',
    ];

    protected function casts(): array
    {
        return [
            'kode'          => 'array',
            'peringatan'    => 'array',
            'diaktifkan_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // File template dan PDF contohnya ikut terhapus bersama record.
        static::deleted(fn (self $t) => Storage::disk('local')->delete(array_filter([$t->file, $t->pratinjau])));
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeDraf($query)
    {
        return $query->where('status', 'draf');
    }

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }
}
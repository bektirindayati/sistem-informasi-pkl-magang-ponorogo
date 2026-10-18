<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratBalasan extends Model
{
    public const DRAF = 'draf';
    public const TERBIT = 'terbit';

    public const JENIS_KEGIATAN = ['Magang', 'Praktik Kerja Lapangan'];
protected $fillable = [
    'pendaftaran_magang_id',

    // Data surat
    'nomor_surat',
    'tanggal_surat',
    'nama_instansi',
    'kota_instansi',
    'jenis_kegiatan',

    // Data permohonan
    'jabatan_pemohon',
    'nomor_surat_pemohon',
    'tanggal_surat_pemohon',

    // Data penandatangan
    'nama_penandatangan',
    'jabatan_penandatangan',
    'pangkat_penandatangan',
    'nip_penandatangan',

    // Kolom lama yang masih ada di database
    'instansi',
    'nama',
    'nim_nisn',
    'jurusan',
    'tanggal_mulai',
    'tanggal_selesai',
    'sebutan',

    // Status dan berkas
    'status',
    'file_draf',
    'file_final',
    'diterbitkan_oleh',
    'diterbitkan_pada',
];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_surat_pemohon' => 'date',
            'diterbitkan_pada' => 'datetime',
        ];
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_magang_id');
    }

    public function penerbit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterbitkan_oleh');
    }

    public function sudahTerbit(): bool
    {
        return $this->status === self::TERBIT;
    }
}
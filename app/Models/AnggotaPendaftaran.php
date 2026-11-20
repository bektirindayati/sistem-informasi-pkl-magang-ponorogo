<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaPendaftaran extends Model
{
    /** Data diri + nomor surat pengantar. Ketua/mandiri memakai nama kolom yang sama di pendaftaran_magangs. */
    public const KOLOM = ['nama_lengkap', 'nim_nisn', 'instansi', 'jurusan', 'no_hp', 'nomor_surat_pengantar'];

    protected $table = 'anggota_pendaftarans';

    protected $fillable = ['nama_lengkap', 'nim_nisn', 'instansi', 'jurusan', 'no_hp', 'nomor_surat_pengantar', 'surat_pengantar'];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_magang_id');
    }
}
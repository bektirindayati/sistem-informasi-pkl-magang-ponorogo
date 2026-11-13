<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaPendaftaran extends Model
{
    /** Kolom data diri anggota (dipakai form, request, dan view). */
    public const KOLOM = ['nama_lengkap', 'nim_nisn', 'instansi', 'jurusan', 'no_hp'];

    protected $table = 'anggota_pendaftarans';
    protected $fillable = self::KOLOM;

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_magang_id');
    }
}
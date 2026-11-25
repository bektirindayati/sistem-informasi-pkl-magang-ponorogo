<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikatFile extends Model
{
    protected $fillable = [
        'file',
        'nama',
        'anggota_pendaftaran_id',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_magang_id');
    }

    public function anggota()
    {
        return $this->belongsTo(AnggotaPendaftaran::class, 'anggota_pendaftaran_id');
    }
}
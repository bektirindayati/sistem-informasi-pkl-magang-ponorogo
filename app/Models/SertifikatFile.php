<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikatFile extends Model
{
    protected $fillable = ['file', 'nama'];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_magang_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstansiBidang extends Model
{
    use HasFactory;

    protected $fillable = [
        'dinas_id',
        'nama_bidang',
        'deskripsi',
    ];

    public function dinas()
    {return $this->belongsTo(Dinas::class, 'dinas_id');}

    public function pendaftarans()
    {return $this->hasMany(PendaftaranMagang::class, 'instansi_bidang_id');}
}
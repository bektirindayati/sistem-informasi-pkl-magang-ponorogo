<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumentasiMagang extends Model
{
    use HasFactory;
    protected $fillable = ['judul_kegiatan', 'kategori_badge', 'deskripsi', 'foto'];
}
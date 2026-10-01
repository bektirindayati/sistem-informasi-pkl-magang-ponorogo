<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\ScopePendaftaranAdmin;   // tanpa backslash di depan

class PendaftaranMagang extends Model
{
    use HasFactory, ScopePendaftaranAdmin;

    public const STATUS_BOLEH_EDIT = ['draft', 'revisi'];

    public const STATUS_BOLEH_HAPUS = ['draft', 'pending', 'revisi', 'ditolak'];

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

    'surat_pengantar',
    'proposal',
    'status',

    'dinas_tujuan',
    'divisi',

    'alasan_penolakan',
    'alasan_revisi',
];

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
}
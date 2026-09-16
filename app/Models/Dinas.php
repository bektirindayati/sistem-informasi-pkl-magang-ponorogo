<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinas extends Model
{
    use HasFactory;

    protected $table = 'dinases';

    protected $fillable = [
        'nama_dinas',
        'deskripsi',
        'alamat',
        'status',
    ];

    public function bidang()
    {return $this->hasMany(InstansiBidang::class, 'dinas_id');}

    public function users()
    {return $this->hasMany(User::class, 'dinas_id');}

    public function admins()
    {return $this->hasMany(Admin::class, 'dinas_id');}

    public function pendaftarans()
    {return $this->hasMany(PendaftaranMagang::class, 'dinas_id');}
}
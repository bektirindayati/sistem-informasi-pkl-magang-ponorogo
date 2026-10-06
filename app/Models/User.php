<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

   protected $fillable = [
    'name',
    'email',
    'password',
    'google_id',
    'avatar',
    'role',
    'dinas_id',

    // Profil pengguna
    'nama_lengkap',
    'nim_nisn',
    'instansi',
    'jurusan',
    'no_hp',
    'alamat',
    'kabupaten',
    'provinsi',
    ];

    protected $hidden = [
        'password',
        'remember_token',];

    protected function casts(): array
    {return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];}

    public function dinas()
    {return $this->belongsTo(Dinas::class);}

    public function isAdmin(): bool
    {return $this->hasAnyRole(['admin', 'super_admin']);}

    public function isSuperAdmin(): bool
    {return $this->hasRole('super_admin');}

    public function pendaftarans()
    {return $this->hasMany(PendaftaranMagang::class, 'user_id');}
}
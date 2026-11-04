<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Isian profil wajib untuk mendaftar: kolom => label.
     */
    public const PROFIL_WAJIB = [
        'kategori' => 'Kategori pendaftar',
        'nim_nisn' => 'NIM / NISN',
        'instansi' => 'Asal instansi',
        'jurusan'  => 'Jurusan',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'role',
        'dinas_id',

        // Profil pengguna
        'kategori',
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
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['admin', 'super_admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function pendaftarans()
    {
        return $this->hasMany(PendaftaranMagang::class, 'user_id');
    }

    /**
     * Pendaftaran terbaru (id terbesar); otomatis di-cache per request.
     */
    public function pendaftaranTerakhir()
    {
        return $this->hasOne(PendaftaranMagang::class, 'user_id')->latestOfMany();
    }

    /**
     * Label isian profil wajib yang masih kosong.
     */
    public function profilKurang(): array
    {
        return array_values(array_filter(
            self::PROFIL_WAJIB,
            fn (string $label, string $kolom) => blank($this->{$kolom}),
            ARRAY_FILTER_USE_BOTH
        ));
    }

    /**
     * Memeriksa apakah seluruh isian profil wajib sudah lengkap.
     */
    public function profilLengkap(): bool
    {
        return $this->profilKurang() === [];
    }
}

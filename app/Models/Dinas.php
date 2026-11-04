<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinas extends Model
{
    use HasFactory;

    protected $table = 'dinases';

    /** Data kop surat yang sebaiknya terisi: kolom => label. */
    public const KOP_WAJIB = [
        'alamat'  => 'Alamat',
        'telepon' => 'Telepon',
        'website' => 'Website',
        'email'   => 'Email',
    ];

    
protected $fillable = [
    'nama_dinas',
    'deskripsi',
    'alamat',
    'status',
    'logo',
    'telepon',
    'faksimile',
    'email',
    'website',

    // Pejabat penandatangan
    'nama_pejabat',
    'jabatan_pejabat',
    'pangkat_pejabat',
    'golongan_pejabat',
    'nip_pejabat',
];

    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }

    /** Label data kop surat yang masih kosong (array kosong = lengkap). */
    public function kopKurang(): array
    {
        return array_values(array_filter(
            self::KOP_WAJIB,
            fn (string $label, string $kolom) => blank($this->{$kolom}),
            ARRAY_FILTER_USE_BOTH
        ));
    }

    public function bidang()
    {
        return $this->hasMany(InstansiBidang::class, 'dinas_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'dinas_id');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class, 'dinas_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(PendaftaranMagang::class, 'dinas_id');
    }
}
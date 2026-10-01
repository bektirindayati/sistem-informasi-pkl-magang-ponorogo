<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DokumentasiMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_kegiatan',
        'kategori_badge',
        'tempat_pelaksanaan',
        'tanggal_pelaksanaan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pelaksanaan' => 'date',
        ];
    }

    /** Foto kegiatan, urut sesuai urutan unggah. */
    public function fotos(): HasMany
    {
        return $this->hasMany(DokumentasiFoto::class)->orderBy('urutan')->orderBy('id');
    }

    /** URL foto pertama untuk sampul kartu (muat dengan with('fotos') agar tidak N+1). */
    public function getSampulUrlAttribute(): ?string
    {
        return $this->fotos->first()?->url;
    }
}
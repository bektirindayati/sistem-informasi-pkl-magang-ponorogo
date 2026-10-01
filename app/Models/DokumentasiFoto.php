<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumentasiFoto extends Model
{
    protected $fillable = ['dokumentasi_magang_id', 'path', 'uraian', 'urutan'];

    public function dokumentasi(): BelongsTo
    {
        return $this->belongsTo(DokumentasiMagang::class, 'dokumentasi_magang_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
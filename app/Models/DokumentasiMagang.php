<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DokumentasiMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'dinas_id',
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

    /** Dinas pemilik dokumentasi. */
    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }

    /** Foto kegiatan, urut sesuai urutan unggah. */
    public function fotos(): HasMany
    {
        return $this->hasMany(DokumentasiFoto::class)->orderBy('urutan')->orderBy('id');
    }

    /** Urut menurut tanggal ACARA (bukan waktu unggah): kegiatan terbaru dulu. */
    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByDesc('tanggal_pelaksanaan')->orderByDesc('id');
    }

    /**
     * Batasi ke dokumentasi yang boleh dikelola user:
     * super admin = semua, admin dinas = hanya milik dinasnya.
     * Admin tanpa dinas tidak melihat apa pun (bukan malah melihat data tanpa pemilik).
     */
    public function scopeBisaDikelolaOleh(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return filled($user->dinas_id)
            ? $query->where('dinas_id', $user->dinas_id)
            : $query->whereRaw('1 = 0');
    }

    /** URL foto pertama untuk sampul kartu (muat dengan with('fotos') agar tidak N+1). */
    public function getSampulUrlAttribute(): ?string
    {
        return $this->fotos->first()?->url;
    }

    /** Cuplikan dari uraian foto pertama yang terisi (muat dengan with('fotos') agar tidak N+1). */
    public function getRingkasanAttribute(): ?string
    {
        $uraian = $this->fotos->pluck('uraian')->first(fn ($teks) => filled($teks));

        return $uraian
            ? Str::limit(trim(preg_replace('/\s+/', ' ', $uraian)), 140)
            : null;
    }
}
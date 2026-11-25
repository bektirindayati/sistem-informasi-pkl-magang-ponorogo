<?php

namespace App\Services\Surat;

use App\Models\TemplateSurat;
use Illuminate\Support\Facades\Storage;

/** Memilih file template: milik dinas dulu, lalu template bawaan. Null bila tidak ada (pemanggil memakai file sistem). */
class TemplateResolver
{
    public function path(string $jenis, ?int $dinasId): ?string
    {
        $disk = Storage::disk('local');

        return TemplateSurat::aktif()
            ->where('jenis', $jenis)
            ->where(fn ($q) => $q->whereNull('dinas_id')->when($dinasId, fn ($w) => $w->orWhere('dinas_id', $dinasId)))
            ->orderByRaw('dinas_id is null')   // milik dinas lebih dulu
            ->get()
            ->map(fn ($t) => $disk->path($t->file))
            ->first(fn ($path) => is_file($path));
    }
}
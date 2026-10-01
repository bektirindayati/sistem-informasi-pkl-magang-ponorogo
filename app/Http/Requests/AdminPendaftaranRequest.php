<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MemeriksaAksesDinasAdmin;
use App\Models\PendaftaranMagang;
use Illuminate\Foundation\Http\FormRequest;

/** Induk request admin yang bekerja pada satu pendaftaran (route {id}). */
abstract class AdminPendaftaranRequest extends FormRequest
{
    use MemeriksaAksesDinasAdmin;

    private ?PendaftaranMagang $dimuat = null;

    public function pendaftaran(): PendaftaranMagang
    {
        return $this->dimuat ??= PendaftaranMagang::findOrFail($this->route('id'));
    }

    protected function dinasIdTarget()
    {
        return $this->pendaftaran()->dinas_id;
    }
}
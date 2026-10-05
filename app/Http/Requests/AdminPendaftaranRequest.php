<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MemeriksaAksesDinasAdmin;
use App\Models\PendaftaranMagang;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request admin yang bekerja pada satu pendaftaran (route {id}).
 */
class AdminPendaftaranRequest extends FormRequest
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

    protected function pesanAksesDitolak(): string
    {
        return 'Anda tidak memiliki akses ke pendaftaran dinas lain.';
    }

    public function rules(): array
    {
        return [];
    }
}
<?php

namespace App\Http\Requests\Admin\Pendaftar;

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
    if ($this->dimuat !== null) {
        return $this->dimuat;
    }

    $parameter = $this->route('id') ?? $this->route('pendaftaran');

    abort_if($parameter === null, 404, 'Parameter pendaftaran tidak ditemukan.');

    if ($parameter instanceof PendaftaranMagang) {
        return $this->dimuat = $parameter;
    }

    return $this->dimuat = PendaftaranMagang::findOrFail($parameter);
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
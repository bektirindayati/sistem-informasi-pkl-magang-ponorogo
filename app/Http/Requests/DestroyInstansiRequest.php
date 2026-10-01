<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\MemeriksaAksesDinasAdmin;
use App\Models\InstansiBidang;
use Illuminate\Foundation\Http\FormRequest;

/** Hapus bidang. */
class DestroyInstansiRequest extends FormRequest
{
    use MemeriksaAksesDinasAdmin;

    private ?InstansiBidang $dimuat = null;

    public function bidang(): InstansiBidang
    {
        return $this->dimuat ??= InstansiBidang::findOrFail($this->route('id'));
    }

    protected function dinasIdTarget()
    {
        return $this->bidang()->dinas_id;
    }

    protected function pesanAksesDitolak(): string
    {
        return 'Anda tidak memiliki akses untuk menghapus bidang milik dinas lain.';
    }

    public function rules(): array
    {
        return [];
    }
}
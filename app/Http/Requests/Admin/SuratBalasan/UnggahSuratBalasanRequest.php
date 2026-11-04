<?php

namespace App\Http\Requests\Admin\SuratBalasan;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\PendaftaranMagang;

/** Simpan status surat balasan (diproses / diterbitkan) dan, bila ada, PDF resminya. */
class UnggahSuratBalasanRequest extends AksesSuratBalasanRequest
{
    public function rules(): array
    {
        return [
            'status_surat'  => ['required', 'in:diproses,diterbitkan'],
            'surat_balasan' => ['nullable', 'file', 'mimetypes:application/pdf', 'max:5120'],
        ];
    }

    /** Surat tidak bisa diterbitkan tanpa PDF (baru diunggah atau sudah tersimpan). */
   public function withValidator(Validator $validator): void
{
    $validator->after(function (Validator $v) {
        if ($this->input('status_surat') !== 'diterbitkan'
            || $this->hasFile('surat_balasan')) {
            return;
        }

        $path = $this->pendaftaran()->file_surat_balasan;

        $pdfTersimpan = filled($path)
            && Storage::disk(PendaftaranMagang::DISK_SURAT)->exists($path);

        if (! $pdfTersimpan) {
            $v->errors()->add(
                'surat_balasan',
                'Unggah PDF surat balasan resmi sebelum menerbitkan surat.'
            );
        }
    });
}

    public function messages(): array
    {
        return [
            'status_surat.required'   => 'Pilih status surat.',
            'status_surat.in'         => 'Status surat tidak valid.',
            'surat_balasan.file'      => 'Surat balasan harus berupa file.',
            'surat_balasan.mimetypes' => 'Surat balasan harus berformat PDF.',
            'surat_balasan.max'       => 'Ukuran surat balasan maksimal 5 MB.',
        ];
    }
}
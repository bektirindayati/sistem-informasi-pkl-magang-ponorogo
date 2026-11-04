<?php

namespace App\Http\Requests\Admin\Sertifikat;

use App\Http\Requests\Admin\Pendaftar\AdminPendaftaranRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Models\PendaftaranMagang;
use Illuminate\Support\Facades\Storage;

/** Akses admin ke sertifikat: dinas harus sesuai (super admin: semua) dan magangnya sudah selesai. */
class SertifikatAdminRequest extends AdminPendaftaranRequest
{
    public function authorize(): bool
    {
        return parent::authorize() && $this->pendaftaran()->status === 'selesai';
    }

    protected function pesanAksesDitolak(): string
    {
        return 'Sertifikat hanya dapat diurus untuk pendaftaran yang magangnya sudah selesai di dinas Anda.';
    }

    /** Aturan hanya berlaku untuk aksi simpan status (aksi unduh draf & lihat PDF tanpa isian). */
    public function rules(): array
    {
        if (! $this->routeIs('admin.sertifikat.status')) {
            return [];
        }

        return [
            'status_sertifikat' => ['required', 'in:diproses,diterbitkan'],
            'sertifikat'        => ['nullable', 'file', 'mimetypes:application/pdf', 'max:5120'],
        ];
    }

    /** Sertifikat tidak bisa diterbitkan tanpa PDF (baru diunggah atau sudah tersimpan). */
    public function withValidator(Validator $validator): void
{
    $validator->after(function (Validator $v) {
        if ($this->input('status_sertifikat') !== 'diterbitkan'
            || $this->hasFile('sertifikat')) {
            return;
        }

        $path = $this->pendaftaran()->file_sertifikat;

        $pdfTersimpan = filled($path)
            && Storage::disk(PendaftaranMagang::DISK_SURAT)->exists($path);

        if (! $pdfTersimpan) {
            $v->errors()->add(
                'sertifikat',
                'Unggah PDF sertifikat resmi sebelum menerbitkannya.'
            );
        }
    });
}

    public function messages(): array
    {
        return [
            'status_sertifikat.required'   => 'Pilih status sertifikat.',
            'status_sertifikat.in'         => 'Status sertifikat tidak valid.',
            'sertifikat.file'              => 'Sertifikat harus berupa file.',
            'sertifikat.mimetypes'         => 'Sertifikat harus berformat PDF.',
            'sertifikat.max'               => 'Ukuran sertifikat maksimal 5 MB.',
        ];
    }
}
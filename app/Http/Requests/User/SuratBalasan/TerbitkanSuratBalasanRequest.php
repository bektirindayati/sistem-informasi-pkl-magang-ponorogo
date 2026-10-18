<?php

namespace App\Http\Requests\Admin\SuratBalasan;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/** Terbitkan surat: draf harus sudah dibuat, nomor terisi, dan (jika diwajibkan) PDF bertanda tangan diunggah. */
class TerbitkanSuratBalasanRequest extends AksesSuratBalasanRequest
{
    public function rules(): array
    {
        return [
            'pdf_bertanda_tangan' => [
                Rule::requiredIf(fn () => (bool) config('surat.wajib_unggah_ttd')),
                'nullable', 'file', 'mimes:pdf', 'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'pdf_bertanda_tangan.required' => 'Unggah PDF surat yang sudah ditandatangani sebelum menerbitkan.',
            'pdf_bertanda_tangan.mimes'    => 'Berkas harus berformat PDF.',
            'pdf_bertanda_tangan.max'      => 'Ukuran PDF maksimal 5MB.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $surat = $this->pendaftaran()->suratBalasan;

            if (! $surat || ! $surat->file_draf) {
                $validator->errors()->add('surat', 'Buat draf surat dan periksa pratinjaunya terlebih dahulu.');
            } elseif ($surat->sudahTerbit()) {
                $validator->errors()->add('surat', 'Surat ini sudah diterbitkan.');
            } elseif (blank($surat->nomor_surat)) {
                $validator->errors()->add('nomor_surat', 'Nomor surat wajib diisi dan disimpan sebelum surat diterbitkan.');
            }
        });
    }
}
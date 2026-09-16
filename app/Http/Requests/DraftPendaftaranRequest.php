<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isDraft = $this->routeIs('user.pendaftaran.draft');
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        // Saat draft, data boleh belum lengkap.
        // Saat kirim/update, data wajib lengkap.
        $required = $isDraft ? 'nullable' : 'required';

        return [
            'dinas_id' => [
                $required,
                'integer',
                'exists:dinases,id',
            ],

            'instansi_bidang_id' => [
                $required,
                'integer',
                Rule::exists('instansi_bidangs', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'dinas_id',
                            $this->input('dinas_id')
                        );
                    }),
            ],

            'kategori' => [
                $required,
                'string',
                'max:100',
            ],

            'nama_lengkap' => [
                $required,
                'string',
                'max:255',
            ],

            'nim_nisn' => [
                $required,
                'string',
                'max:50',
            ],

            'instansi' => [
                $required,
                'string',
                'max:255',
            ],

            'jurusan' => [
                $required,
                'string',
                'max:255',
            ],

            'no_hp' => [
                $required,
                'numeric',
                'digits_between:10,15',
            ],

            'alamat' => [
                $required,
                'string',
            ],

            'kabupaten' => [
                $required,
                'string',
                'max:255',
            ],

            'provinsi' => [
                $required,
                'string',
                'max:255',
            ],

            'tanggal_mulai' => [
                $required,
                'date',
            ],

            'tanggal_selesai' => [
                $required,
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'surat_pengantar' => [
                ($isDraft || $isUpdate) ? 'nullable' : 'required',
                'file',
                'mimes:pdf',
                'max:2048',
            ],

            'proposal' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'dinas_id.required' => 'Instansi / dinas tujuan wajib dipilih.',
            'dinas_id.exists' => 'Instansi / dinas tujuan yang dipilih tidak tersedia.',

            'instansi_bidang_id.required' => 'Bidang penempatan wajib dipilih.',
            'instansi_bidang_id.exists' => 'Bidang penempatan yang dipilih tidak tersedia atau tidak sesuai dengan dinas tujuan.',

            'kategori.required' => 'Kategori pendaftar wajib dipilih.',
            'kategori.max' => 'Kategori pendaftar maksimal 100 karakter.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',

            'nim_nisn.required' => 'NIM / NISN wajib diisi.',
            'nim_nisn.max' => 'NIM / NISN maksimal 50 karakter.',

            'instansi.required' => 'Asal instansi atau universitas wajib diisi.',
            'instansi.max' => 'Asal instansi atau universitas maksimal 255 karakter.',

            'jurusan.required' => 'Jurusan atau program studi wajib diisi.',
            'jurusan.max' => 'Jurusan atau program studi maksimal 255 karakter.',

            'no_hp.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'no_hp.numeric' => 'Nomor WhatsApp / HP harus berupa angka.',
            'no_hp.digits_between' => 'Nomor WhatsApp / HP harus terdiri dari 10 hingga 15 digit.',

            'alamat.required' => 'Alamat tempat tinggal wajib diisi.',

            'kabupaten.required' => 'Kabupaten / kota wajib diisi.',
            'kabupaten.max' => 'Kabupaten / kota maksimal 255 karakter.',

            'provinsi.required' => 'Provinsi wajib diisi.',
            'provinsi.max' => 'Provinsi maksimal 255 karakter.',

            'tanggal_mulai.required' => 'Tanggal mulai magang wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',

            'tanggal_selesai.required' => 'Tanggal selesai magang wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',

            'surat_pengantar.required' => 'Surat pengantar instansi wajib diunggah.',
            'surat_pengantar.file' => 'Surat pengantar harus berupa file.',
            'surat_pengantar.mimes' => 'Surat pengantar harus berformat PDF.',
            'surat_pengantar.max' => 'Ukuran surat pengantar tidak boleh lebih dari 2MB.',

            'proposal.file' => 'Proposal harus berupa file.',
            'proposal.mimes' => 'Proposal magang harus berformat PDF.',
            'proposal.max' => 'Ukuran proposal magang tidak boleh lebih dari 2MB.',
        ];
    }
}
<?php

namespace App\Http\Requests\Admin\SuratBalasan;

use App\Models\SuratBalasan;
use Illuminate\Validation\Rule;

/** Simpan data surat (lalu draf dibuat ulang). Nomor surat boleh kosong sampai surat diterbitkan. */
class UpdateSuratBalasanRequest extends AksesSuratBalasanRequest
{
    public function rules(): array
    {
        return [
            'nomor_surat'           => ['nullable', 'string', 'max:100'],
            'tanggal_surat'         => ['required', 'date'],
            'nama_instansi'         => ['required', 'string', 'max:255'],
            'kota_instansi'         => ['required', 'string', 'max:100'],
            'jenis_kegiatan'        => ['required', Rule::in(SuratBalasan::JENIS_KEGIATAN)],

            'jabatan_pemohon'       => ['nullable', 'string', 'max:255'],
            'nomor_surat_pemohon'   => ['nullable', 'string', 'max:100'],
            'tanggal_surat_pemohon' => ['nullable', 'date'],

            'nama_penandatangan'    => ['required', 'string', 'max:255'],
            'jabatan_penandatangan' => ['required', 'string', 'max:255'],
            'pangkat_penandatangan' => ['nullable', 'string', 'max:100'],
            'nip_penandatangan'     => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_surat.required'         => 'Tanggal surat wajib diisi.',
            'nama_instansi.required'         => 'Nama kampus / sekolah wajib diisi.',
            'kota_instansi.required'         => 'Kota kampus / sekolah wajib diisi.',
            'jenis_kegiatan.required'        => 'Jenis kegiatan wajib dipilih.',
            'jenis_kegiatan.in'              => 'Jenis kegiatan tidak valid.',
            'nama_penandatangan.required'    => 'Nama penandatangan wajib diisi.',
            'jabatan_penandatangan.required' => 'Jabatan penandatangan wajib diisi.',
        ];
    }

    public function dataSurat(): array
    {
        return $this->validated();
    }
}
<?php

namespace App\Http\Requests;

use App\Models\Dinas;
use App\Models\InstansiBidang;
use App\Models\PendaftaranMagang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * Induk semua request pendaftaran yang punya form (draft, store, update).
 * Aturan field, pesan error, dan penyusunan data disimpan DI SINI saja,
 * jadi kalau ada field baru cukup ubah satu tempat.
 */
abstract class BasePendaftaranRequest extends FormRequest
{
    private ?PendaftaranMagang $terakhir = null;
    private bool $terakhirDimuat = false;

    public function authorize(): bool
    {
        return true;
    }

    /** Pendaftaran terbaru milik user yang sedang login (di-cache per request). */
    public function pendaftaranTerakhir(): ?PendaftaranMagang
    {
        if (! $this->terakhirDimuat) {
            $this->terakhir = PendaftaranMagang::where('user_id', $this->user()->id)
                ->latest('id')
                ->first();
            $this->terakhirDimuat = true;
        }

        return $this->terakhir;
    }

    /** Pesan jika user tidak boleh membuat/mengubah pengajuan, null jika boleh. */
    protected function pesanTerkunci(): ?string
    {
        return match ($this->pendaftaranTerakhir()?->status) {
            'pending'  => 'Pendaftaran kamu masih dalam proses verifikasi.',
            'diterima' => 'Pendaftaran kamu sudah diterima dan belum dapat membuat pengajuan baru.',
            default    => null,
        };
    }

    /** $wajib = 'required' (kirim/update) atau 'nullable' (draft). */
    protected function aturanData(string $wajib): array
    {
        return [
            'dinas_id' => [$wajib, 'integer', 'exists:dinases,id'],

            'instansi_bidang_id' => [
                $wajib,
                'integer',
                Rule::exists('instansi_bidangs', 'id')
                    ->where(fn ($query) => $query->where('dinas_id', $this->input('dinas_id'))),
            ],

            'kategori'     => [$wajib, 'string', 'max:100'],
            'nama_lengkap' => [$wajib, 'string', 'max:255'],
            'nim_nisn'     => [$wajib, 'string', 'max:50'],
            'instansi'     => [$wajib, 'string', 'max:255'],
            'jurusan'      => [$wajib, 'string', 'max:255'],
            'no_hp'        => [$wajib, 'numeric', 'digits_between:10,15'],
            'alamat'       => [$wajib, 'string'],
            'kabupaten'    => [$wajib, 'string', 'max:255'],
            'provinsi'     => [$wajib, 'string', 'max:255'],

            'tanggal_mulai'   => [$wajib, 'date'],
            'tanggal_selesai' => [$wajib, 'date', 'after_or_equal:tanggal_mulai'],
        ];
    }

    /** Surat pengantar wajib/tidak tergantung konteks, proposal selalu opsional. */
    protected function aturanBerkas(bool $suratWajib): array
    {
        return [
            'surat_pengantar' => [$suratWajib ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:2048'],
            'proposal'        => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    /**
     * Data siap simpan (tanpa file). dinas_tujuan & divisi diisi otomatis
     * dari dinas / bidang yang dipilih.
     */
    public function dataPendaftaran(): array
    {
        $data = Arr::except($this->validated(), ['surat_pengantar', 'proposal']);

        if (! empty($data['dinas_id'])) {
            $data['dinas_tujuan'] = Dinas::whereKey($data['dinas_id'])->value('nama_dinas');
        }

        if (! empty($data['instansi_bidang_id'])) {
            $data['divisi'] = InstansiBidang::whereKey($data['instansi_bidang_id'])->value('nama_bidang');
        }

        return $data;
    }

    /** Status yang di-reset setiap pengajuan dikirim / dikirim ulang. */
    protected function dataKirim(): array
    {
        return [
            'status'           => 'pending',
            'alasan_penolakan' => null,
            'alasan_revisi'    => null,
        ];
    }

    public function messages(): array
    {
        return [
            'dinas_id.required' => 'Instansi / dinas tujuan wajib dipilih.',
            'dinas_id.exists'   => 'Instansi / dinas tujuan yang dipilih tidak tersedia.',

            'instansi_bidang_id.required' => 'Bidang penempatan wajib dipilih.',
            'instansi_bidang_id.exists'   => 'Bidang penempatan yang dipilih tidak tersedia atau tidak sesuai dengan dinas tujuan.',

            'kategori.required' => 'Kategori pendaftar wajib dipilih.',
            'kategori.max'      => 'Kategori pendaftar maksimal 100 karakter.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max'      => 'Nama lengkap maksimal 255 karakter.',

            'nim_nisn.required' => 'NIM / NISN wajib diisi.',
            'nim_nisn.max'      => 'NIM / NISN maksimal 50 karakter.',

            'instansi.required' => 'Asal instansi atau universitas wajib diisi.',
            'instansi.max'      => 'Asal instansi atau universitas maksimal 255 karakter.',

            'jurusan.required' => 'Jurusan atau program studi wajib diisi.',
            'jurusan.max'      => 'Jurusan atau program studi maksimal 255 karakter.',

            'no_hp.required'       => 'Nomor WhatsApp / HP wajib diisi.',
            'no_hp.numeric'        => 'Nomor WhatsApp / HP harus berupa angka.',
            'no_hp.digits_between' => 'Nomor WhatsApp / HP harus terdiri dari 10 hingga 15 digit.',

            'alamat.required' => 'Alamat tempat tinggal wajib diisi.',

            'kabupaten.required' => 'Kabupaten / kota wajib diisi.',
            'kabupaten.max'      => 'Kabupaten / kota maksimal 255 karakter.',

            'provinsi.required' => 'Provinsi wajib diisi.',
            'provinsi.max'      => 'Provinsi maksimal 255 karakter.',

            'tanggal_mulai.required' => 'Tanggal mulai magang wajib diisi.',
            'tanggal_mulai.date'     => 'Format tanggal mulai tidak valid.',

            'tanggal_selesai.required'       => 'Tanggal selesai magang wajib diisi.',
            'tanggal_selesai.date'           => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',

            'surat_pengantar.required' => 'Surat pengantar instansi wajib diunggah.',
            'surat_pengantar.file'     => 'Surat pengantar harus berupa file.',
            'surat_pengantar.mimes'    => 'Surat pengantar harus berformat PDF.',
            'surat_pengantar.max'      => 'Ukuran surat pengantar tidak boleh lebih dari 2MB.',

            'proposal.file'  => 'Proposal harus berupa file.',
            'proposal.mimes' => 'Proposal magang harus berformat PDF.',
            'proposal.max'   => 'Ukuran proposal magang tidak boleh lebih dari 2MB.',
        ];
    }
}
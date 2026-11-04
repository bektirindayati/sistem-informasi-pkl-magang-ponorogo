<?php

namespace App\Http\Requests\Pendaftaran;

use App\Http\Requests\Concerns\AturanDataDiri;
use App\Models\Dinas;
use App\Models\InstansiBidang;
use App\Models\PendaftaranMagang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

abstract class BasePendaftaranRequest extends FormRequest
{
    use AturanDataDiri;

    /** Default untuk kirim & draft baru: tidak boleh jika pengajuan terakhir terkunci. */
    public function authorize(): bool
    {
        return $this->pesanTerkunci() === null;
    }

    /**
     * Record yang disimpan: draft milik user bila ada (mis. hasil auto-save),
     * kalau tidak, record baru. (Update menggantinya lewat route {id}.)
     */
    public function pendaftaran(): PendaftaranMagang
    {
        $terakhir = $this->user()->pendaftaranTerakhir;

        return $terakhir?->status === 'draft'
            ? $terakhir
            : new PendaftaranMagang(['user_id' => $this->user()->id]);
    }

    /** Pesan jika user tidak boleh membuat/mengubah pengajuan, null jika boleh. */
    protected function pesanTerkunci(): ?string
    {
        return $this->user()->pendaftaranTerakhir?->pesanTerkunci();
    }

    /** Default: kirim / kirim ulang. Draft menimpa method ini. */
    public function rules(): array
    {
        $suratWajib = empty($this->pendaftaran()->surat_pengantar);

        return array_merge(
            $this->aturanData('required'),
            $this->aturanBerkas($suratWajib)
        );
    }

    /** $wajib = 'required' (kirim/update) atau 'nullable' (draft). */
    protected function aturanData(string $wajib): array
    {
        return array_merge($this->aturanDataDiri($wajib), [
            'dinas_id' => [$wajib, 'integer', 'exists:dinases,id'],

            'instansi_bidang_id' => [
                $wajib,
                'integer',
                Rule::exists('instansi_bidangs', 'id')
                    ->where(fn ($query) => $query->where('dinas_id', $this->input('dinas_id'))),
            ],

            'tanggal_mulai'   => [$wajib, 'date'],
            'tanggal_selesai' => [$wajib, 'date', 'after_or_equal:tanggal_mulai'],

            // Identitas surat pengantar dan tujuan surat balasan
            'nomor_surat_pengantar'   => [$wajib, 'string', 'max:100'],
            'tanggal_surat_pengantar' => [$wajib, 'date', 'before_or_equal:today'],
            'jabatan_penerima'        => [$wajib, 'string', 'max:255'],
            'kota_tujuan'             => [$wajib, 'string', 'max:100'],
        ]);
    }

    /** Surat pengantar wajib/tidak tergantung konteks, proposal selalu opsional. */
    protected function aturanBerkas(bool $suratWajib): array
    {
        return [
            'surat_pengantar' => [$suratWajib ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:2048'],
            'proposal'        => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    /** Status yang ditulis setiap simpan. Default: kirim/kirim ulang. */
    protected function dataStatus(): array
    {
        return [
            'status'           => 'pending',
            'alasan_penolakan' => null,
            'alasan_revisi'    => null,
        ];
    }

    /**
     * Data siap simpan (tanpa file). dinas_tujuan & divisi selalu
     * mengikuti pilihan terbaru (dikosongkan bila pilihan dikosongkan).
     */
    public function dataPendaftaran(): array
    {
        $data = Arr::except($this->validated(), ['surat_pengantar', 'proposal']);

        $data['dinas_tujuan'] = empty($data['dinas_id'])
            ? null
            : Dinas::whereKey($data['dinas_id'])->value('nama_dinas');

        $data['divisi'] = empty($data['instansi_bidang_id'])
            ? null
            : InstansiBidang::whereKey($data['instansi_bidang_id'])->value('nama_bidang');

        return array_merge($data, $this->dataStatus());
    }

    public function messages(): array
    {
        return array_merge($this->pesanDataDiri(), [
            'dinas_id.required' => 'Instansi / dinas tujuan wajib dipilih.',
            'dinas_id.exists'   => 'Instansi / dinas tujuan yang dipilih tidak tersedia.',

            'instansi_bidang_id.required' => 'Bidang penempatan wajib dipilih.',
            'instansi_bidang_id.exists'   => 'Bidang penempatan yang dipilih tidak tersedia atau tidak sesuai dengan dinas tujuan.',

            'tanggal_mulai.required' => 'Tanggal mulai magang wajib diisi.',
            'tanggal_mulai.date'     => 'Format tanggal mulai tidak valid.',

            'tanggal_selesai.required'       => 'Tanggal selesai magang wajib diisi.',
            'tanggal_selesai.date'           => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',

            // Pesan validasi identitas surat pengantar
            'nomor_surat_pengantar.required' => 'Nomor surat pengantar wajib diisi.',
            'nomor_surat_pengantar.string'   => 'Nomor surat pengantar harus berupa teks.',
            'nomor_surat_pengantar.max'      => 'Nomor surat pengantar maksimal 100 karakter.',

            'tanggal_surat_pengantar.required'        => 'Tanggal surat pengantar wajib diisi.',
            'tanggal_surat_pengantar.date'            => 'Format tanggal surat pengantar tidak valid.',
            'tanggal_surat_pengantar.before_or_equal' => 'Tanggal surat pengantar tidak boleh di masa depan.',

            'jabatan_penerima.required' => 'Jabatan penerima surat wajib diisi.',
            'jabatan_penerima.string'   => 'Jabatan penerima surat harus berupa teks.',
            'jabatan_penerima.max'      => 'Jabatan penerima surat maksimal 255 karakter.',

            'kota_tujuan.required' => 'Kota kampus / sekolah wajib diisi.',
            'kota_tujuan.string'   => 'Kota kampus / sekolah harus berupa teks.',
            'kota_tujuan.max'      => 'Kota kampus / sekolah maksimal 100 karakter.',

            'surat_pengantar.required' => 'Surat pengantar instansi wajib diunggah.',
            'surat_pengantar.file'     => 'Surat pengantar harus berupa file.',
            'surat_pengantar.mimes'    => 'Surat pengantar harus berformat PDF.',
            'surat_pengantar.max'      => 'Ukuran surat pengantar tidak boleh lebih dari 2MB.',

            'proposal.file'  => 'Proposal harus berupa file.',
            'proposal.mimes' => 'Proposal magang harus berformat PDF.',
            'proposal.max'   => 'Ukuran proposal magang tidak boleh lebih dari 2MB.',
        ]);
    }
}
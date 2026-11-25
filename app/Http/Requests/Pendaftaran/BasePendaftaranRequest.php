<?php

namespace App\Http\Requests\Pendaftaran;

use App\Http\Requests\Concerns\AturanDataDiri;
use App\Models\Dinas;
use App\Models\InstansiBidang;
use App\Models\PendaftaranMagang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;

abstract class BasePendaftaranRequest extends FormRequest
{
    use AturanDataDiri;

    /**
     * Default untuk kirim dan draft baru:
     * tidak boleh jika akun diblokir atau pengajuan terakhir terkunci.
     */
    public function authorize(): bool
    {
        return !$this->akunDiblokir()
            && $this->pesanTerkunci() === null;
    }

    /** Memeriksa apakah akun pengguna sedang diblokir. */
    protected function akunDiblokir(): bool
    {
        return $this->user()?->isBlocked() ?? false;
    }

    /** Pesan penolakan otorisasi sesuai kondisi akun. */
    protected function pesanAksesDitolak(): string
    {
        if ($this->akunDiblokir()) {
            return 'Akun Anda sedang diblokir oleh administrator. '
                . 'Anda masih dapat melihat status dan riwayat pendaftaran, '
                . 'tetapi tidak dapat membuat atau mengubah pengajuan. '
                . 'Hubungi administrator untuk informasi lebih lanjut.';
        }

        return $this->pesanTerkunci()
            ?? 'Anda tidak memiliki izin untuk melakukan tindakan ini.';
    }

    /** Jika akun diblokir, arahkan kembali ke halaman status. */
    protected function failedAuthorization()
    {
        if ($this->akunDiblokir()) {
            throw new HttpResponseException(
                redirect()
                    ->route('user.pendaftaran.status')
                    ->with('error', $this->pesanAksesDitolak())
            );
        }

        parent::failedAuthorization();
    }

    /**
     * Record yang disimpan: draft milik user bila ada,
     * kalau tidak, record baru.
     */
    public function pendaftaran(): PendaftaranMagang
    {
        $terakhir = $this->user()->pendaftaranTerakhir;

        return $terakhir?->status === 'draft'
            ? $terakhir
            : new PendaftaranMagang(['user_id' => $this->user()->id]);
    }

    /** Pesan jika user tidak boleh membuat/mengubah pengajuan. */
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

    /**
     * Menentukan aturan validasi data pendaftaran.
     * Mendukung pendaftaran mandiri dan kelompok.
     */
    protected function aturanData(string $wajib): array
    {
        return array_merge(
            $this->aturanDataDiri($wajib),
            [
                'tipe' => [
                    $wajib,
                    Rule::in(array_keys(PendaftaranMagang::TIPE)),
                ],

                'dinas_id' => [
                    $wajib,
                    'integer',
                    'exists:dinases,id',
                ],

                'instansi_bidang_id' => [
                    $wajib,
                    'integer',
                    Rule::exists('instansi_bidangs', 'id')
                        ->where(fn ($query) => $query->where(
                            'dinas_id',
                            $this->input('dinas_id')
                        )),
                ],

                'tanggal_mulai' => [$wajib, 'date'],

                'tanggal_selesai' => [
                    $wajib,
                    'date',
                    'after_or_equal:tanggal_mulai',
                ],

                'nomor_surat_pengantar' => [
                    $wajib,
                    'string',
                    'max:100',
                ],

                'tanggal_surat_pengantar' => [
                    $wajib,
                    'date',
                    'before_or_equal:today',
                ],

                'jabatan_penerima' => [
                    $wajib,
                    'string',
                    'max:255',
                ],

                'kota_tujuan' => [
                    $wajib,
                    'string',
                    'max:100',
                ],
            ],
            $this->kelompok()
                ? $this->aturanAnggota($wajib)
                : []
        );
    }

    /** Memeriksa apakah jenis pendaftaran adalah kelompok. */
    protected function kelompok(): bool
    {
        return $this->input('tipe') === 'kelompok';
    }

        /**
     * Aturan validasi anggota kelompok.
     * Anggota harus memiliki data diri yang lengkap.
     */
    protected function aturanAnggota(string $wajib): array
    {
        $aturan = $this->aturanDataDiri('required', 'anggota.*.');
        $aturan['anggota.*.nim_nisn'][] = 'distinct';
        $aturan['anggota.*.nomor_surat_pengantar'] = ['required', 'string', 'max:100'];
        $aturan['anggota.*.id']  = ['nullable', 'integer'];
        $aturan['anggota.*.uid'] = ['nullable', 'string', 'max:40'];

        // PDF surat pengantar tiap anggota: wajib saat kirim bila belum pernah tersimpan di server.
        $tersimpan = $this->anggotaBerkasTersimpan();

        foreach (array_keys((array) $this->input('anggota', [])) as $i) {
            $sudahAda = $tersimpan->contains((int) $this->input("anggota.{$i}.id"));

            $aturan["anggota.{$i}.surat_pengantar"] = [
                $wajib === 'required' && ! $sudahAda ? 'required' : 'nullable',
                'file', 'mimes:pdf', 'max:2048',
            ];
        }

        return ['anggota' => [
            $wajib === 'required' ? 'required' : 'nullable',
            'array', 'min:1', 'max:' . PendaftaranMagang::MAKS_ANGGOTA,
        ]] + $aturan;
    }

        /** ID anggota milik pendaftaran ini yang sudah punya PDF surat pengantar di server. */
    private function anggotaBerkasTersimpan(): Collection
    {
        $p = $this->pendaftaran();

        return $p->exists
            ? $p->anggotas()->whereNotNull('surat_pengantar')->pluck('id')
            : collect();
    }

    /** Surat pengantar wajib/tidak tergantung konteks, proposal selalu opsional. */
    protected function aturanBerkas(bool $suratWajib): array
    {
        return [
            'surat_pengantar' => [
                $suratWajib ? 'required' : 'nullable',
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

            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    /** Status yang ditulis setiap simpan. */
    protected function dataStatus(): array
    {
        return [
            'status' => 'pending',
            'alasan_penolakan' => null,
            'alasan_revisi' => null,
        ];
    }

    /**
     * Data siap simpan tanpa file dan tanpa data anggota.
     * Tipe default adalah mandiri.
     */
    public function dataPendaftaran(): array
    {
        $data = Arr::except($this->validated(), ['surat_pengantar', 'proposal', 'cv', 'anggota']);

        $data['tipe'] = $data['tipe'] ?? 'mandiri';

        $data['dinas_tujuan'] = empty($data['dinas_id'])
            ? null
            : Dinas::whereKey($data['dinas_id'])->value('nama_dinas');

        $data['divisi'] = empty($data['instansi_bidang_id'])
            ? null
            : InstansiBidang::whereKey($data['instansi_bidang_id'])->value('nama_bidang');

        return array_merge($data, $this->dataStatus());
    }

        /** Anggota siap simpan: data + 'berkas' (UploadedFile|null). */
    public function dataAnggota(): array
    {
        if (! $this->kelompok()) {
            return [];
        }

        return collect($this->validated()['anggota'] ?? [])
            ->map(function (array $a) {
                $berkas = $a['surat_pengantar'] ?? null;
                unset($a['surat_pengantar']);

                return $a + ['berkas' => $berkas];
            })
            ->values()->all();
    }

    /** Pesan validasi. */
    public function messages(): array
    {
        return array_merge(
            $this->pesanDataDiri(),
            $this->pesanDataDiri('anggota.*.'),
            [
                'tipe.required' => 'Jenis pendaftaran wajib dipilih.',
                'tipe.in' => 'Jenis pendaftaran tidak valid.',

                'anggota.required' => 'Tambahkan minimal satu anggota kelompok.',
                'anggota.min' => 'Tambahkan minimal satu anggota kelompok.',
                'anggota.max' => 'Anggota kelompok maksimal '
                    . PendaftaranMagang::MAKS_ANGGOTA
                    . ' orang.',

                'anggota.*.nim_nisn.distinct' =>
                    'Anggota ke-:position: NIM / NISN duplikat dengan anggota lain.',
                                    'anggota.*.nomor_surat_pengantar.required' =>
                    'Anggota ke-:position: nomor surat pengantar wajib diisi.',
                'anggota.*.nomor_surat_pengantar.max' =>
                    'Anggota ke-:position: nomor surat pengantar maksimal :max karakter.',

                'anggota.*.surat_pengantar.required' =>
                    'Anggota ke-:position: surat pengantar wajib diunggah.',
                'anggota.*.surat_pengantar.mimes' =>
                    'Anggota ke-:position: surat pengantar harus berformat PDF.',
                'anggota.*.surat_pengantar.max' =>
                    'Anggota ke-:position: ukuran surat pengantar tidak boleh lebih dari 2MB.',

                'dinas_id.required' => 'Instansi / dinas tujuan wajib dipilih.',
                'dinas_id.exists' => 'Instansi / dinas tujuan yang dipilih tidak tersedia.',

                'instansi_bidang_id.required' => 'Bidang penempatan wajib dipilih.',
                'instansi_bidang_id.exists' => 'Bidang penempatan yang dipilih tidak tersedia atau tidak sesuai dengan dinas tujuan.',

                'tanggal_mulai.required' => 'Tanggal mulai magang wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',

                'tanggal_selesai.required' => 'Tanggal selesai magang wajib diisi.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',

                'nomor_surat_pengantar.required' => 'Nomor surat pengantar wajib diisi.',
                'nomor_surat_pengantar.string' => 'Nomor surat pengantar harus berupa teks.',
                'nomor_surat_pengantar.max' => 'Nomor surat pengantar maksimal 100 karakter.',

                'tanggal_surat_pengantar.required' => 'Tanggal surat pengantar wajib diisi.',
                'tanggal_surat_pengantar.date' => 'Format tanggal surat pengantar tidak valid.',
                'tanggal_surat_pengantar.before_or_equal' => 'Tanggal surat pengantar tidak boleh di masa depan.',

                'jabatan_penerima.required' => 'Jabatan penerima surat wajib diisi.',
                'jabatan_penerima.string' => 'Jabatan penerima surat harus berupa teks.',
                'jabatan_penerima.max' => 'Jabatan penerima surat maksimal 255 karakter.',

                'kota_tujuan.required' => 'Kota kampus / sekolah wajib diisi.',
                'kota_tujuan.string' => 'Kota kampus / sekolah harus berupa teks.',
                'kota_tujuan.max' => 'Kota kampus / sekolah maksimal 100 karakter.',

                'surat_pengantar.required' => 'Surat pengantar instansi wajib diunggah.',
                'surat_pengantar.file' => 'Surat pengantar harus berupa file.',
                'surat_pengantar.mimes' => 'Surat pengantar harus berformat PDF.',
                'surat_pengantar.max' => 'Ukuran surat pengantar tidak boleh lebih dari 2MB.',

                'proposal.file' => 'Proposal harus berupa file.',
                'proposal.mimes' => 'Proposal magang harus berformat PDF.',
                'proposal.max' => 'Ukuran proposal magang tidak boleh lebih dari 2MB.',

                'cv.file'  => 'CV harus berupa file.',
                'cv.mimes' => 'CV harus berformat PDF.',
                'cv.max'   => 'Ukuran CV tidak boleh lebih dari 2MB.',
            ]
        );
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

/** Aturan & pesan bersama untuk tambah/ubah dokumentasi kegiatan. */
abstract class BaseDokumentasiRequest extends FormRequest
{
    protected const MAKS_FOTO = 10;
    protected const MAKS_URAIAN = 5000;

    /** Akses dijaga middleware permission:kelola-dokumentasi di route. */
    public function authorize(): bool
    {
        return true;
    }

    protected function aturanKegiatan(): array
    {
        return [
            'judul_kegiatan' => ['required', 'string', 'max:255'],
            'kategori_badge' => ['required', 'string', 'max:255'],
            'tempat_pelaksanaan' => ['required', 'string', 'max:255'],
            'tanggal_pelaksanaan' => ['required', 'date'],
        ];
    }

    /** Foto baru (foto[]) dan uraiannya (uraian[]) dikirim berpasangan menurut urutan. */
    protected function aturanFoto(bool $wajib): array
    {
        return [
            'foto' => [$wajib ? 'required' : 'nullable', 'array', 'max:' . self::MAKS_FOTO],
            'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'uraian' => ['nullable', 'array'],
            'uraian.*' => ['required', 'string', 'max:' . self::MAKS_URAIAN],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if (count($this->file('foto', [])) !== count((array) $this->input('uraian', []))) {
                $validator->errors()->add('uraian', 'Setiap foto harus memiliki uraian.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'judul_kegiatan.required' => 'Judul kegiatan wajib diisi.',
            'judul_kegiatan.max' => 'Judul kegiatan maksimal 255 karakter.',

            'kategori_badge.required' => 'Kategori wajib diisi.',
            'kategori_badge.max' => 'Kategori maksimal 255 karakter.',

            'tempat_pelaksanaan.required' => 'Tempat pelaksanaan wajib diisi.',
            'tempat_pelaksanaan.max' => 'Tempat pelaksanaan maksimal 255 karakter.',

            'tanggal_pelaksanaan.required' => 'Tanggal pelaksanaan wajib diisi.',
            'tanggal_pelaksanaan.date' => 'Format tanggal pelaksanaan tidak valid.',

            'foto.required' => 'Minimal unggah 1 foto dokumentasi.',
            'foto.array' => 'Format unggahan foto tidak valid.',
            'foto.max' => 'Maksimal ' . self::MAKS_FOTO . ' foto per kegiatan.',
            'foto.*.image' => 'Semua file yang diunggah harus berupa gambar.',
            'foto.*.mimes' => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'foto.*.max' => 'Ukuran tiap foto tidak boleh lebih dari 2MB.',
            'foto.*.uploaded' => 'Foto gagal diunggah. Pastikan ukuran tiap foto maksimal 2MB.',

            'uraian.*.required' => 'Uraian untuk setiap foto wajib diisi.',
            'uraian.*.max' => 'Uraian foto maksimal ' . self::MAKS_URAIAN . ' karakter.',
        ];
    }

    public function dataKegiatan(): array
    {
        return Arr::only($this->validated(), [
            'judul_kegiatan', 'kategori_badge', 'tempat_pelaksanaan', 'tanggal_pelaksanaan',
        ]);
    }

    /**
     * Foto baru berpasangan dengan uraiannya.
     *
     * @return array<int, array{file: \Illuminate\Http\UploadedFile, uraian: string}>
     */
    public function fotoBaru(): array
    {
        $uraian = array_values($this->validated('uraian') ?? []);

        return collect($this->file('foto', []))
            ->values()
            ->map(fn ($file, $i) => ['file' => $file, 'uraian' => trim((string) ($uraian[$i] ?? ''))])
            ->all();
    }
}
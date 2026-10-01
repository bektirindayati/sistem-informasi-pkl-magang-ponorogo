<?php

namespace App\Http\Requests;

use App\Models\DokumentasiMagang;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Ubah dokumentasi: edit uraian foto lama (uraian_lama[id]), centang foto
 * lama untuk dihapus (hapus_foto[]), dan/atau tambah foto baru. Setelah
 * diubah, foto harus tersisa 1 sampai 10.
 */
class UpdateDokumentasiRequest extends BaseDokumentasiRequest
{
    private ?DokumentasiMagang $dimuat = null;

    public function dokumentasi(): DokumentasiMagang
    {
        return $this->dimuat ??= DokumentasiMagang::findOrFail($this->route('id'));
    }

    public function rules(): array
    {
        return array_merge($this->aturanKegiatan(), $this->aturanFoto(false), [
            'uraian_lama' => ['nullable', 'array'],
            'uraian_lama.*' => ['nullable', 'string', 'max:' . self::MAKS_URAIAN],

            'hapus_foto' => ['nullable', 'array'],
            'hapus_foto.*' => [
                'integer',
                Rule::exists('dokumentasi_fotos', 'id')
                    ->where('dokumentasi_magang_id', $this->route('id')),
            ],
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        parent::withValidator($validator);

        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $sisa = $this->dokumentasi()->fotos()->reorder()->count()
                - count($this->hapusFotoIds())
                + count($this->file('foto', []));

            if ($sisa < 1) {
                $validator->errors()->add('foto', 'Minimal harus tersisa 1 foto dokumentasi.');
            } elseif ($sisa > self::MAKS_FOTO) {
                $validator->errors()->add('foto', 'Maksimal ' . self::MAKS_FOTO . ' foto per kegiatan.');
            }
        });
    }

    /** @return int[] id foto lama yang akan dihapus */
    public function hapusFotoIds(): array
    {
        return array_values(array_unique(array_map('intval', (array) $this->input('hapus_foto', []))));
    }

    /** @return array<int, string|null> [id foto lama => uraian baru] */
    public function uraianLama(): array
    {
        return array_map(
            fn ($teks) => filled($teks) ? trim($teks) : null,
            $this->validated('uraian_lama') ?? []
        );
    }
}
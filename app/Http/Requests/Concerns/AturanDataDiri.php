<?php

namespace App\Http\Requests\Concerns;

use App\Models\PendaftaranMagang;
use Closure;
use Illuminate\Validation\Rule;

/** Aturan & pesan data diri yang dipakai bersama oleh profil dan pendaftaran. */
trait AturanDataDiri
{
    /** $wajib = 'required' (kirim/update) atau 'nullable' (draft/profil). */
    protected function aturanDataDiri(string $wajib): array
    {
        return [
            'kategori'     => [$wajib, Rule::in(array_keys(PendaftaranMagang::KATEGORI))],
            'nama_lengkap' => [$wajib, 'string', 'max:255'],
            'nim_nisn'     => [$wajib, 'string', 'max:50'],
            'instansi'     => [$wajib, 'string', 'max:255'],
            'jurusan'      => [$wajib, 'string', 'max:255'],
            'no_hp'        => [$wajib, 'numeric', 'digits_between:10,15'],
            'alamat'       => [$wajib, 'string', 'max:1000'],

            // Provinsi & kabupaten boleh ditulis bebas. Khusus provinsi yang ada di
            // config/wilayah.php, kabupaten harus salah satu isinya.
            'provinsi'     => [$wajib, 'string', 'max:100'],
            'kabupaten'    => [$wajib, 'string', 'max:100', $this->kabupatenSesuaiProvinsi()],
        ];
    }

    private function kabupatenSesuaiProvinsi(): Closure
    {
        return function (string $atribut, mixed $nilai, Closure $gagal) {
            $daftar = $this->kabupatenTerdaftar();

            if ($daftar !== null
                && ! in_array(mb_strtolower(trim((string) $nilai)), array_map('mb_strtolower', $daftar), true)) {
                $gagal('Kabupaten / kota tidak sesuai dengan provinsi yang dipilih.');
            }
        };
    }

    /** Daftar kabupaten bila provinsi yang diisi ada di config/wilayah.php; null bila provinsi bebas. */
    private function kabupatenTerdaftar(): ?array
    {
        $provinsi = $this->input('provinsi');

        if (! is_string($provinsi)) {
            return null;
        }

        foreach (config('wilayah', []) as $nama => $daftar) {
            if (mb_strtolower($nama) === mb_strtolower(trim($provinsi))) {
                return $daftar;
            }
        }

        return null;
    }

    protected function pesanDataDiri(): array
    {
        return [
            'kategori.required' => 'Kategori pendaftar wajib dipilih.',
            'kategori.in'       => 'Kategori pendaftar tidak valid.',

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
            'alamat.max'      => 'Alamat maksimal 1000 karakter.',

            'provinsi.required' => 'Provinsi wajib diisi.',
            'provinsi.max'      => 'Provinsi maksimal 100 karakter.',

            'kabupaten.required' => 'Kabupaten / kota wajib diisi.',
            'kabupaten.max'      => 'Kabupaten / kota maksimal 100 karakter.',
        ];
    }
}
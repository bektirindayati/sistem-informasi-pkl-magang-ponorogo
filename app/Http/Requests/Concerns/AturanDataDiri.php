<?php

namespace App\Http\Requests\Concerns;

use App\Models\PendaftaranMagang;
use Illuminate\Validation\Rule;

/** Aturan & pesan data diri: profil, ketua (prefix ''), dan anggota (prefix 'anggota.*.'). */
trait AturanDataDiri
{
    /** kolom => [label, panjang maksimal] */
    private function kolomDataDiri(): array
    {
        return [
            'nama_lengkap' => ['Nama lengkap', 255],
            'nim_nisn'     => ['NIM / NISN', 50],
            'instansi'     => ['Asal instansi', 255],
            'jurusan'      => ['Jurusan / program studi', 255],
        ];
    }

    protected function aturanDataDiri(string $wajib, string $prefix = ''): array
    {
        $aturan = [];

        foreach ($this->kolomDataDiri() as $kolom => [, $maks]) {
            $aturan[$prefix . $kolom] = [$wajib, 'string', "max:$maks"];
        }

        $aturan[$prefix . 'no_hp'] = [$wajib, 'digits_between:10,15'];

        // Kategori hanya untuk ketua / mandiri; anggota mengikuti ketua.
        if ($prefix === '') {
            $aturan['kategori'] = [$wajib, Rule::in(array_keys(PendaftaranMagang::KATEGORI))];
        }

        return $aturan;
    }

    protected function pesanDataDiri(string $prefix = ''): array
    {
        $awal  = $prefix === '' ? '' : 'Anggota ke-:position: ';
        $pesan = [];

        foreach ($this->kolomDataDiri() as $kolom => [$label]) {
            $pesan["{$prefix}{$kolom}.required"] = "{$awal}{$label} wajib diisi.";
            $pesan["{$prefix}{$kolom}.max"]      = "{$awal}{$label} maksimal :max karakter.";
        }

        $pesan["{$prefix}no_hp.required"]       = "{$awal}No. HP wajib diisi.";
        $pesan["{$prefix}no_hp.digits_between"] = "{$awal}No. HP harus berupa angka 10-15 digit.";

        if ($prefix === '') {
            $pesan['kategori.required'] = 'Kategori pendaftar wajib dipilih.';
            $pesan['kategori.in']       = 'Kategori pendaftar tidak valid.';
        }

        return $pesan;
    }
}
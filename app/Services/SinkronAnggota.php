<?php

namespace App\Services;

use App\Models\PendaftaranMagang;
use Illuminate\Support\Arr;

/** Menyamakan anggota di database dengan kiriman form: perbarui, tambah, hapus, beserta PDF surat pengantarnya. */
class SinkronAnggota
{
    /**
     * @param array $baris  hasil BasePendaftaranRequest::dataAnggota()
     * @param array $baru   (referensi) path PDF baru, untuk dibersihkan bila transaksi gagal
     * @return array{peta: array, hapus: string[]}  peta: uid => [id, surat]; hapus: path lama yang boleh dibuang setelah commit
     */
    public function jalankan(PendaftaranMagang $p, array $baris, array &$baru): array
    {
        $lama  = $p->anggotas()->get()->keyBy('id');
        $dipakai = [];
        $peta  = [];
        $hapus = [];

        foreach ($baris as $row) {
            $berkas = Arr::pull($row, 'berkas');
            $uid    = Arr::pull($row, 'uid');
            $id     = Arr::pull($row, 'id');

            // Hanya anggota milik pendaftaran ini yang boleh diperbarui.
            $anggota = $id ? $lama->get((int) $id) : null;

            if ($berkas) {
                $path = $berkas->store('pendaftaran/anggota_surat_pengantar', PendaftaranMagang::DISK_BERKAS);
                $baru[] = $path;

                if ($anggota?->surat_pengantar) {
                    $hapus[] = $anggota->surat_pengantar;
                }

                $row['surat_pengantar'] = $path;
            }

            if ($anggota) {
                $anggota->update($row);
            } else {
                $anggota = $p->anggotas()->create($row);
            }

            $dipakai[] = $anggota->id;
            $peta[$uid ?: $anggota->id] = [
                'id'    => $anggota->id,
                'surat' => $anggota->surat_pengantar ? asset('storage/' . $anggota->surat_pengantar) : null,
            ];
        }

        foreach ($lama->except($dipakai) as $anggota) {
            if ($anggota->surat_pengantar) {
                $hapus[] = $anggota->surat_pengantar;
            }

            $anggota->delete();
        }

        return ['peta' => $peta, 'hapus' => $hapus];
    }
}
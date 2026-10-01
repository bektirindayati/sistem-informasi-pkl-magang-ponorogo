<?php

namespace App\Services;

use App\Models\DokumentasiFoto;
use App\Models\DokumentasiMagang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/** Semua urusan simpan/hapus dokumentasi beserta foto dan uraiannya. */
class DokumentasiService
{
    /**
     * @param  array<int, array{file: \Illuminate\Http\UploadedFile, uraian: string}>  $fotoBaru
     */
    public function buat(array $data, array $fotoBaru): DokumentasiMagang
    {
        return DB::transaction(function () use ($data, $fotoBaru) {
            $dokumentasi = DokumentasiMagang::create($data);
            $this->tambahFoto($dokumentasi, $fotoBaru);

            return $dokumentasi;
        });
    }

    /**
     * @param  array<int, array{file: \Illuminate\Http\UploadedFile, uraian: string}>  $fotoBaru
     * @param  int[]  $hapusIds
     * @param  array<int, string|null>  $uraianLama  [id foto lama => uraian]
     */
    public function ubah(
        DokumentasiMagang $dokumentasi,
        array $data,
        array $fotoBaru,
        array $hapusIds,
        array $uraianLama
    ): void {
        DB::transaction(function () use ($dokumentasi, $data, $fotoBaru, $hapusIds, $uraianLama) {
            $dokumentasi->update($data);
            $this->hapusFoto($dokumentasi, $hapusIds);
            $this->perbaruiUraian($dokumentasi, $uraianLama);
            $this->tambahFoto($dokumentasi, $fotoBaru);
        });
    }

    public function hapus(DokumentasiMagang $dokumentasi): void
    {
        $paths = $dokumentasi->fotos()->pluck('path')->all();

        $dokumentasi->delete(); // baris foto ikut terhapus (cascade)

        Storage::disk('public')->delete($paths);
    }

    private function tambahFoto(DokumentasiMagang $dokumentasi, array $fotoBaru): void
    {
        $urutan = (int) $dokumentasi->fotos()->reorder()->max('urutan');

        foreach ($fotoBaru as $item) {
            $dokumentasi->fotos()->create([
                'path' => $item['file']->store('dokumentasi', 'public'),
                'uraian' => $item['uraian'],
                'urutan' => ++$urutan,
            ]);
        }
    }

    private function hapusFoto(DokumentasiMagang $dokumentasi, array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $foto = $dokumentasi->fotos()->whereIn('id', $ids)->get();

        Storage::disk('public')->delete($foto->pluck('path')->all());
        DokumentasiFoto::destroy($foto->pluck('id')->all());
    }

    /** Hanya foto milik kegiatan ini yang bisa diubah uraiannya. */
    private function perbaruiUraian(DokumentasiMagang $dokumentasi, array $uraianLama): void
    {
        if (empty($uraianLama)) {
            return;
        }

        $dokumentasi->fotos()
            ->whereIn('id', array_keys($uraianLama))
            ->get()
            ->each(fn (DokumentasiFoto $foto) => $foto->update(['uraian' => $uraianLama[$foto->id]]));
    }
}
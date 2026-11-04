<?php

namespace App\Services;

use App\Models\DokumentasiFoto;
use App\Models\DokumentasiMagang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

/** Semua urusan simpan/hapus dokumentasi beserta foto dan uraiannya. */
class DokumentasiService
{
    /**
     * @param  array<int, array{file: \Illuminate\Http\UploadedFile, uraian: string}>  $fotoBaru
     */
    public function buat(array $data, array $fotoBaru): DokumentasiMagang
    {
        $tersimpan = [];

        try {
            return DB::transaction(function () use ($data, $fotoBaru, &$tersimpan) {
                $dokumentasi = DokumentasiMagang::create($data);
                $this->tambahFoto($dokumentasi, $fotoBaru, $tersimpan);

                return $dokumentasi;
            });
        } catch (Throwable $e) {
            // Transaksi gagal: file yang sudah terlanjur diunggah ikut dibuang.
            $this->hapusFile($tersimpan);

            throw $e;
        }
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
        $tersimpan = [];

        try {
            $pathTerhapus = DB::transaction(function () use ($dokumentasi, $data, $fotoBaru, $hapusIds, $uraianLama, &$tersimpan) {
                $dokumentasi->update($data);
                $path = $this->hapusFoto($dokumentasi, $hapusIds);
                $this->perbaruiUraian($dokumentasi, $uraianLama);
                $this->tambahFoto($dokumentasi, $fotoBaru, $tersimpan);

                return $path;
            });
        } catch (Throwable $e) {
            $this->hapusFile($tersimpan);

            throw $e;
        }

        // File lama dihapus SETELAH commit: kalau transaksi gagal, foto lama tetap utuh.
        $this->hapusFile($pathTerhapus);
    }

    public function hapus(DokumentasiMagang $dokumentasi): void
    {
        $paths = $dokumentasi->fotos()->pluck('path')->all();

        $dokumentasi->delete(); // baris foto ikut terhapus (cascade)

        $this->hapusFile($paths);
    }

    /**
     * @param  string[]  $tersimpan  diisi path file yang sudah ditulis ke disk (untuk pembersihan bila gagal)
     */
    private function tambahFoto(DokumentasiMagang $dokumentasi, array $fotoBaru, array &$tersimpan): void
    {
        $urutan = (int) $dokumentasi->fotos()->max('urutan');

        foreach ($fotoBaru as $item) {
            $path = $item['file']->store('dokumentasi', 'public');
            $tersimpan[] = $path;

            $dokumentasi->fotos()->create([
                'path'   => $path,
                'uraian' => $item['uraian'],
                'urutan' => ++$urutan,
            ]);
        }
    }

    /** Hapus baris foto; mengembalikan path file untuk dihapus dari disk SETELAH commit. */
    private function hapusFoto(DokumentasiMagang $dokumentasi, array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $foto = $dokumentasi->fotos()->whereIn('id', $ids)->get();

        DokumentasiFoto::destroy($foto->pluck('id')->all());

        return $foto->pluck('path')->all();
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

    private function hapusFile(array $paths): void
    {
        if (! empty($paths)) {
            Storage::disk('public')->delete($paths);
        }
    }
}
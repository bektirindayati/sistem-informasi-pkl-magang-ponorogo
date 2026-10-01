<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Data lama:
        // tanggal_pelaksanaan diisi dari tanggal unggah,
        // deskripsi lama dipindahkan menjadi uraian foto pertama.
        DB::table('dokumentasi_magangs')
            ->orderBy('id')
            ->each(function ($row) {
                DB::table('dokumentasi_magangs')
                    ->where('id', $row->id)
                    ->update([
                        'tanggal_pelaksanaan' => $row->created_at
                            ? Carbon::parse($row->created_at)->toDateString()
                            : null,
                    ]);

                $fotoId = DB::table('dokumentasi_fotos')
                    ->where('dokumentasi_magang_id', $row->id)
                    ->orderBy('urutan')
                    ->orderBy('id')
                    ->value('id');

                if ($fotoId && filled($row->deskripsi)) {
                    DB::table('dokumentasi_fotos')
                        ->where('id', $fotoId)
                        ->update([
                            'uraian' => $row->deskripsi,
                        ]);
                }
            });
    }

    public function down(): void
    {
        // Tidak menghapus kolom karena kolom-kolom tersebut
        // dibuat oleh migration sebelumnya.
    }
};
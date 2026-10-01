<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumentasi_magang_id')
                ->constrained('dokumentasi_magangs')
                ->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });

        // Pindahkan foto tunggal lama ke tabel baru sebelum kolomnya dibuang.
        DB::table('dokumentasi_magangs')
            ->whereNotNull('foto')
            ->orderBy('id')
            ->each(function ($row) {
                DB::table('dokumentasi_fotos')->insert([
                    'dokumentasi_magang_id' => $row->id,
                    'path' => $row->foto,
                    'urutan' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        Schema::table('dokumentasi_magangs', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }

    public function down(): void
    {
        Schema::table('dokumentasi_magangs', function (Blueprint $table) {
            $table->string('foto')->nullable();
        });

        // Kembalikan foto pertama tiap kegiatan.
        DB::table('dokumentasi_fotos')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->unique('dokumentasi_magang_id')
            ->each(fn ($foto) => DB::table('dokumentasi_magangs')
                ->where('id', $foto->dokumentasi_magang_id)
                ->update(['foto' => $foto->path]));

        Schema::dropIfExists('dokumentasi_fotos');
    }
};
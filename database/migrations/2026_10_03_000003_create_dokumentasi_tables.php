<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur akhir dokumentasi kegiatan (gabungan 5 migrasi lama):
 *  - dokumentasi_magangs : data kegiatan
 *  - dokumentasi_fotos   : foto per kegiatan, masing-masing punya uraian
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi_magangs', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->string('kategori_badge');
            $table->string('tempat_pelaksanaan');
            $table->date('tanggal_pelaksanaan');
            $table->timestamps();
        });

        Schema::create('dokumentasi_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumentasi_magang_id')
                ->constrained('dokumentasi_magangs')
                ->cascadeOnDelete();
            $table->string('path');
            $table->text('uraian')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_fotos');
        Schema::dropIfExists('dokumentasi_magangs');
    }
};
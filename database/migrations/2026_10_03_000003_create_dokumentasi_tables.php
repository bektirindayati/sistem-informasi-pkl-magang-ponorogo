
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur akhir dokumentasi kegiatan:
 * - dokumentasi_magangs: data kegiatan
 * - dokumentasi_fotos: foto per kegiatan
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi_magangs', function (Blueprint $table) {
            $table->id();

            // Dinas pemilik dokumentasi
            $table->foreignId('dinas_id')
                ->nullable()
                ->constrained('dinases')
                ->nullOnDelete();

            // Informasi kegiatan
            $table->string('judul_kegiatan');
            $table->string('kategori_badge');
            $table->string('tempat_pelaksanaan');
            $table->date('tanggal_pelaksanaan');
            $table->time('waktu_pelaksanaan')->nullable();

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

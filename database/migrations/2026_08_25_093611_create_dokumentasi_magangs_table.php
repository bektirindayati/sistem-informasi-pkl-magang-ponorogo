<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi_magangs', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->string('kategori_badge');
            $table->text('deskripsi');
            $table->string('foto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_magangs');
    }
};
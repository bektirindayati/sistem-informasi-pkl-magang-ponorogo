<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur akhir tabel instansi_bidangs (gabungan 2 migrasi lama).
 * Butuh tabel dinases sudah ada lebih dulu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instansi_bidangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dinas_id')->constrained('dinases')->cascadeOnDelete();
            $table->string('nama_bidang');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instansi_bidangs');
    }
};
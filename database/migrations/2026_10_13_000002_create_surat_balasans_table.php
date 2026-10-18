<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Satu surat balasan per pendaftaran: data isian, file draf, file final, dan status terbit. */
return new class extends Migration
{
    public function up(): void
{
    // Tabel sudah dibuat oleh implementasi sebelumnya.
    // Kolom yang kurang ditambahkan melalui migration penyesuaian.
    if (Schema::hasTable('surat_balasans')) {
        return;
    }

    Schema::create('surat_balasans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pendaftaran_magang_id')
            ->unique()
            ->constrained('pendaftaran_magangs')
            ->cascadeOnDelete();

        $table->string('nomor_surat')->nullable();
        $table->date('tanggal_surat')->nullable();
        $table->string('nama_instansi')->nullable();
        $table->string('kota_instansi')->nullable();
        $table->string('jenis_kegiatan')->default('Magang');

        $table->string('jabatan_pemohon')->nullable();
        $table->string('nomor_surat_pemohon')->nullable();
        $table->date('tanggal_surat_pemohon')->nullable();

        $table->string('nama_penandatangan')->nullable();
        $table->string('jabatan_penandatangan')->nullable();
        $table->string('pangkat_penandatangan')->nullable();
        $table->string('nip_penandatangan')->nullable();

        $table->string('status')->default('draf');
        $table->string('file_draf')->nullable();
        $table->string('file_final')->nullable();

        $table->foreignId('diterbitkan_oleh')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('diterbitkan_pada')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
{
    // Sengaja dikosongkan agar tabel lama tidak terhapus.
}
};
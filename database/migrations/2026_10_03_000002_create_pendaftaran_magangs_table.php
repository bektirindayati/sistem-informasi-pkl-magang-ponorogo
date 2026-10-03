<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur akhir tabel pendaftaran_magangs (gabungan 14 migrasi lama).
 * Butuh tabel users, dinases, dan instansi_bidangs sudah ada lebih dulu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_magangs', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dinas_id')->nullable()->constrained('dinases')->nullOnDelete();
            $table->foreignId('instansi_bidang_id')->nullable()->constrained('instansi_bidangs')->nullOnDelete();

            // Data pendaftar (boleh kosong karena disimpan sebagai draft)
            $table->string('kategori')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('nim_nisn')->nullable();
            $table->string('instansi')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();

            // Tujuan magang (nama dinas & bidang disalin saat dikirim)
            $table->string('dinas_tujuan')->nullable();
            $table->string('divisi')->nullable();

            // Periode & berkas
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('surat_pengantar')->nullable();
            $table->string('proposal')->nullable();

            // Status & catatan admin
            $table->enum('status', ['draft', 'pending', 'revisi', 'diterima', 'ditolak', 'selesai'])
                ->default('draft');
            $table->text('alasan_penolakan')->nullable();
            $table->text('alasan_revisi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_magangs');
    }
};
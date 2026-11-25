<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur akhir pendaftaran magang: pengajuan, anggota kelompok, dan berkas sertifikat.
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

            // Jenis pendaftaran dan data ketua/pendaftar (nullable: bisa disimpan sebagai draft)
            $table->enum('tipe', ['mandiri', 'kelompok'])->default('mandiri');
            $table->string('kategori')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('nim_nisn')->nullable();
            $table->string('instansi')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('no_hp')->nullable();

            // Tujuan dan periode (nama dinas & bidang disalin saat dikirim)
            $table->string('dinas_tujuan')->nullable();
            $table->string('divisi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            // Surat pengantar dan berkas
            $table->string('nomor_surat_pengantar', 100)->nullable();
            $table->date('tanggal_surat_pengantar')->nullable();
            $table->string('jabatan_penerima')->nullable();
            $table->string('kota_tujuan', 100)->nullable();
            $table->string('surat_pengantar')->nullable();
            $table->string('proposal')->nullable();

            // Status dan catatan admin
            $table->enum('status', ['draft', 'pending', 'revisi', 'diterima', 'ditolak', 'selesai'])->default('draft');
            $table->text('alasan_penolakan')->nullable();
            $table->text('alasan_revisi')->nullable();

            // Surat balasan (diterima)
            $table->string('file_surat_balasan')->nullable();
            $table->string('status_surat', 20)->default('diproses');               // diproses | diterbitkan
            $table->timestamp('surat_balasan_terbit_at')->nullable();

            // Surat selesai magang
            $table->string('file_surat_selesai')->nullable();
            $table->string('status_surat_selesai')->nullable();
            $table->timestamp('surat_selesai_terbit_at')->nullable();

            // Sertifikat (PDF-nya ada di tabel sertifikat_files)
            $table->string('status_sertifikat', 20)->default('diproses');
            $table->timestamp('sertifikat_terbit_at')->nullable();

            $table->timestamps();

            $table->index(['dinas_id', 'status']);   // daftar admin per dinas
        });

        Schema::create('anggota_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_magang_id')->constrained('pendaftaran_magangs')->cascadeOnDelete();
           $table->string('nama_lengkap');
            $table->string('nim_nisn', 50);
            $table->string('instansi');
            $table->string('jurusan');
            $table->string('no_hp', 20);
            $table->string('nomor_surat_pengantar', 100)->nullable();
            $table->string('surat_pengantar')->nullable();
            $table->timestamps();
        });

        Schema::create('sertifikat_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_magang_id')->constrained('pendaftaran_magangs')->cascadeOnDelete();
            $table->foreignId('anggota_pendaftaran_id')->nullable()->constrained('anggota_pendaftarans')->cascadeOnDelete();   // null = ketua/mandiri
            $table->string('file');   // path di disk privat
            $table->string('nama');   // nama tampilan, mis. Sertifikat_Budi.pdf
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat_files');
        Schema::dropIfExists('anggota_pendaftarans');
        Schema::dropIfExists('pendaftaran_magangs');
    }
};
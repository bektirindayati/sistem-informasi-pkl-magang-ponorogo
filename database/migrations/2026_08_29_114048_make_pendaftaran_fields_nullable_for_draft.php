<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable()->change();
            $table->string('nim_nisn')->nullable()->change();
            $table->string('instansi')->nullable()->change();
            $table->string('jurusan')->nullable()->change();
            $table->string('no_hp')->nullable()->change();

            $table->date('tanggal_mulai')->nullable()->change();
            $table->date('tanggal_selesai')->nullable()->change();

            $table->string('surat_pengantar')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable(false)->change();
            $table->string('nim_nisn')->nullable(false)->change();
            $table->string('instansi')->nullable(false)->change();
            $table->string('jurusan')->nullable(false)->change();
            $table->string('no_hp')->nullable(false)->change();

            $table->date('tanggal_mulai')->nullable(false)->change();
            $table->date('tanggal_selesai')->nullable(false)->change();

            $table->string('surat_pengantar')->nullable(false)->change();
        });
    }
};
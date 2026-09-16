<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('pendaftaran_magangs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('nama_lengkap');
        $table->string('nim_nisn');
        $table->string('instansi');
        $table->string('jurusan');
        $table->string('no_hp');
        $table->date('tanggal_mulai');
        $table->date('tanggal_selesai');
        $table->string('surat_pengantar');
        $table->string('proposal')->nullable();
        $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_magangs');
    }
};

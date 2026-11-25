<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dinas_id')->nullable()->constrained('dinases')->cascadeOnDelete();   // null = template bawaan
            $table->string('jenis', 30);
            $table->string('status', 10)->default('draf');                  // draf | aktif
            $table->string('file');                                         // path di disk privat
            $table->string('nama_asli');
            $table->json('kode')->nullable();                               // kode ${...} yang terdeteksi
            $table->json('peringatan')->nullable();
            $table->string('pratinjau')->nullable();                        // path PDF contoh hasil
            $table->string('pratinjau_status', 10)->default('menunggu');    // menunggu | siap | gagal
            $table->text('pratinjau_pesan')->nullable();
            $table->foreignId('diunggah_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diaktifkan_at')->nullable();
            $table->timestamps();

            $table->index(['dinas_id', 'jenis', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_surats');
    }
};
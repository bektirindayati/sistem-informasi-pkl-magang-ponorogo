<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->string('nomor_surat_balasan', 100)->nullable();
            $table->date('tanggal_surat_balasan')->nullable();
            $table->string('file_surat_balasan')->nullable();
            $table->timestamp('surat_balasan_terbit_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_surat_balasan',
                'tanggal_surat_balasan',
                'file_surat_balasan',
                'surat_balasan_terbit_at',
            ]);
        });
    }
};
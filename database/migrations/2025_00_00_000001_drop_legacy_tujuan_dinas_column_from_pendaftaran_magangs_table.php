<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX: kolom "tujuan_dinas" ini adalah peninggalan awal (kemungkinan
 * percobaan sebelum nama kolom dibetulkan jadi "dinas_tujuan"). Seluruh
 * kode aplikasi (PendaftaranController, StorePendaftaranRequest, semua
 * view show/create/dashboard) konsisten memakai "dinas_tujuan", bukan
 * "tujuan_dinas" -- termasuk migration nullable yang sudah ada
 * sebelumnya yang mengubah dinas_tujuan (bukan tujuan_dinas) jadi
 * nullable. Kolom "tujuan_dinas" ini tidak pernah dibaca/ditulis oleh
 * kode manapun, jadi aman dihapus.
 *
 * PENTING: jalankan migration ini SETELAH memverifikasi sendiri bahwa
 * kolom "dinas_tujuan" (ejaan benar) memang sudah ada di tabel
 * pendaftaran_magangs -- lihat catatan di chat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftaran_magangs', 'tujuan_dinas')) {
                $table->dropColumn('tujuan_dinas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->string('tujuan_dinas')->nullable();
        });
    }
};

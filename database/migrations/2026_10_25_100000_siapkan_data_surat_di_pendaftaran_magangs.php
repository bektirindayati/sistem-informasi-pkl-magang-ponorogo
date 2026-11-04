<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            if (! Schema::hasColumn('pendaftaran_magangs', 'jabatan_penerima')) {
                $table->string('jabatan_penerima')->nullable();
            }

            if (Schema::hasColumn('pendaftaran_magangs', 'kota_instansi')) {
                $table->renameColumn('kota_instansi', 'kota_tujuan');
            } elseif (! Schema::hasColumn('pendaftaran_magangs', 'kota_tujuan')) {
                $table->string('kota_tujuan', 100)->nullable();
            }
        });

        // Nomor & tanggal surat balasan kini ditetapkan dinas di dokumen Word, bukan di sistem.
        foreach (['nomor_surat_balasan', 'tanggal_surat_balasan'] as $kolom) {
            if (Schema::hasColumn('pendaftaran_magangs', $kolom)) {
                Schema::table('pendaftaran_magangs', fn (Blueprint $table) => $table->dropColumn($kolom));
            }
        }
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->dropColumn(['jabatan_penerima', 'kota_tujuan']);
            $table->string('nomor_surat_balasan', 100)->nullable();
            $table->date('tanggal_surat_balasan')->nullable();
        });
    }
};
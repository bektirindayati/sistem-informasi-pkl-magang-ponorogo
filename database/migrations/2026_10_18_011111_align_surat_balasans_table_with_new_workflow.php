<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_balasans', function (Blueprint $table) {
            if (! Schema::hasColumn('surat_balasans', 'nama_instansi')) {
                $table->string('nama_instansi')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'kota_instansi')) {
                $table->string('kota_instansi')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'jenis_kegiatan')) {
                $table->string('jenis_kegiatan')->default('Magang');
            }

            if (! Schema::hasColumn('surat_balasans', 'jabatan_pemohon')) {
                $table->string('jabatan_pemohon')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'nomor_surat_pemohon')) {
                $table->string('nomor_surat_pemohon')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'tanggal_surat_pemohon')) {
                $table->date('tanggal_surat_pemohon')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'nama_penandatangan')) {
                $table->string('nama_penandatangan')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'jabatan_penandatangan')) {
                $table->string('jabatan_penandatangan')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'pangkat_penandatangan')) {
                $table->string('pangkat_penandatangan')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'nip_penandatangan')) {
                $table->string('nip_penandatangan')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'file_draf')) {
                $table->string('file_draf')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'file_final')) {
                $table->string('file_final')->nullable();
            }

            if (! Schema::hasColumn('surat_balasans', 'diterbitkan_pada')) {
                $table->timestamp('diterbitkan_pada')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat_balasans', function (Blueprint $table) {
            $columns = [
                'nama_instansi',
                'kota_instansi',
                'jenis_kegiatan',
                'jabatan_pemohon',
                'nomor_surat_pemohon',
                'tanggal_surat_pemohon',
                'nama_penandatangan',
                'jabatan_penandatangan',
                'pangkat_penandatangan',
                'nip_penandatangan',
                'file_draf',
                'file_final',
                'diterbitkan_pada',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('surat_balasans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
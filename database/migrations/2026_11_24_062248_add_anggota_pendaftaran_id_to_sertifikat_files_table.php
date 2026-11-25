<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sertifikat_files', function (Blueprint $table) {
            $table->foreignId('anggota_pendaftaran_id')
                ->nullable()
                ->after('pendaftaran_magang_id')
                ->constrained('anggota_pendaftarans')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sertifikat_files', function (Blueprint $table) {
            $table->dropConstrainedForeignId('anggota_pendaftaran_id');
        });
    }
};
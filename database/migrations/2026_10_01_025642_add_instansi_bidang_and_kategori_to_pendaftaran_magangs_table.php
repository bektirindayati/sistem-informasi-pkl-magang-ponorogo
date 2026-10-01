<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->foreignId('instansi_bidang_id')
                ->nullable()
                ->after('dinas_id')
                ->constrained('instansi_bidangs')
                ->nullOnDelete();

            $table->string('kategori')
                ->nullable()
                ->after('instansi_bidang_id');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->dropForeign(['instansi_bidang_id']);
            $table->dropColumn(['instansi_bidang_id', 'kategori']);
        });
    }
};
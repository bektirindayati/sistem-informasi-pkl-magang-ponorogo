<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan field untuk struktur dokumentasi yang lebih terarah.
     */
    public function up(): void
    {
        Schema::table('dokumentasi_magangs', function (Blueprint $table) {
            $table->string('tempat_pelaksanaan')->nullable()->after('kategori_badge');
            $table->date('tanggal_pelaksanaan')->nullable()->after('tempat_pelaksanaan');
        });

        Schema::table('dokumentasi_fotos', function (Blueprint $table) {
            $table->text('uraian')->nullable()->after('path');
        });
    }

    /**
     * Kembalikan struktur database seperti sebelumnya.
     */
    public function down(): void
    {
        Schema::table('dokumentasi_fotos', function (Blueprint $table) {
            $table->dropColumn('uraian');
        });

        Schema::table('dokumentasi_magangs', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_pelaksanaan',
                'tanggal_pelaksanaan',
            ]);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumentasi_magangs', function (Blueprint $table) {
            $table->time('waktu_pelaksanaan')->nullable()->after('tanggal_pelaksanaan');
        });
    }

    public function down(): void
    {
        Schema::table('dokumentasi_magangs', function (Blueprint $table) {
            $table->dropColumn('waktu_pelaksanaan');
        });
    }
};
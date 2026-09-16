<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->text('alasan_revisi')
                ->nullable()
                ->after('alasan_penolakan');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->dropColumn('alasan_revisi');
        });
    }
};
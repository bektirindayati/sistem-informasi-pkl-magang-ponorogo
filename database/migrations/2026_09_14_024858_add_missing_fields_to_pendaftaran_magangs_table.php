<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran_magangs', 'divisi')) {
                $table->string('divisi')
                    ->nullable()
                    ->after('tujuan_dinas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftaran_magangs', 'divisi')) {
                $table->dropColumn('divisi');
            }
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftaran_magangs', 'tujuan_dinas')
                && !Schema::hasColumn('pendaftaran_magangs', 'dinas_tujuan')) {
                $table->renameColumn('tujuan_dinas', 'dinas_tujuan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftaran_magangs', 'dinas_tujuan')
                && !Schema::hasColumn('pendaftaran_magangs', 'tujuan_dinas')) {
                $table->renameColumn('dinas_tujuan', 'tujuan_dinas');
            }
        });
    }
};
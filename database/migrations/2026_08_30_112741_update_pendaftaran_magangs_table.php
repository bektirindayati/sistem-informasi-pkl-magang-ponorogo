<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {

            // ALASAN PENOLAKAN
            $table->text('alasan_penolakan')
                ->nullable()
                ->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {

            if (Schema::hasColumn('pendaftaran_magangs', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }

        });
    }
};
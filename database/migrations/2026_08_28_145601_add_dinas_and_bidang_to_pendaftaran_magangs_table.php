<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {

            if (!Schema::hasColumn('pendaftaran_magangs', 'alamat')) {
                $table->text('alamat')
                    ->nullable()
                    ->after('no_hp');
            }

            if (!Schema::hasColumn('pendaftaran_magangs', 'kabupaten')) {
                $table->string('kabupaten')
                    ->nullable()
                    ->after('alamat');
            }

            if (!Schema::hasColumn('pendaftaran_magangs', 'provinsi')) {
                $table->string('provinsi')
                    ->nullable()
                    ->after('kabupaten');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {

            if (Schema::hasColumn('pendaftaran_magangs', 'alamat')) {
                $table->dropColumn('alamat');
            }

            if (Schema::hasColumn('pendaftaran_magangs', 'kabupaten')) {
                $table->dropColumn('kabupaten');
            }

            if (Schema::hasColumn('pendaftaran_magangs', 'provinsi')) {
                $table->dropColumn('provinsi');
            }
        });
    }
};
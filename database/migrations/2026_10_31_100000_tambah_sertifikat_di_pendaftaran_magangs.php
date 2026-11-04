<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            if (! Schema::hasColumn('pendaftaran_magangs', 'file_sertifikat')) {
                $table->string('file_sertifikat')->nullable();
            }
            if (! Schema::hasColumn('pendaftaran_magangs', 'status_sertifikat')) {
                $table->string('status_sertifikat', 20)->default('diproses');
            }
            if (! Schema::hasColumn('pendaftaran_magangs', 'sertifikat_terbit_at')) {
                $table->timestamp('sertifikat_terbit_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->dropColumn(['file_sertifikat', 'status_sertifikat', 'sertifikat_terbit_at']);
        });
    }
};
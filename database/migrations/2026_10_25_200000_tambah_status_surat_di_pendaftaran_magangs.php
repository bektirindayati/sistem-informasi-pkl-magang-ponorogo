<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('pendaftaran_magangs', 'status_surat')) {
            Schema::table('pendaftaran_magangs', function (Blueprint $table) {
                $table->string('status_surat', 20)->default('diproses');
            });
        }

        // Surat yang sudah punya PDF sebelum kolom ini ada dianggap sudah diterbitkan.
        DB::table('pendaftaran_magangs')
            ->whereNotNull('file_surat_balasan')
            ->update(['status_surat' => 'diterbitkan']);
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->dropColumn('status_surat');
        });
    }
};
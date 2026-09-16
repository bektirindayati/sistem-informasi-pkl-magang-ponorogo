<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Data ini dianggap "profil permanen" user (beda dari data di
            // pendaftaran_magangs yang cuma snapshot historis tiap kali
            // daftar). Dipakai untuk auto-fill form pendaftaran berikutnya.
            $table->string('nim_nisn')->nullable()->after('role');
            $table->string('instansi')->nullable()->after('nim_nisn');
            $table->string('jurusan')->nullable()->after('instansi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim_nisn', 'instansi', 'jurusan']);
        });
    }
};
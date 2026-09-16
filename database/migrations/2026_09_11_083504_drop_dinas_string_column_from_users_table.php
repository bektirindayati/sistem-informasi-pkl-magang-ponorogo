<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * FIX: kolom 'dinas' (string, dari migration lama add_dinas_to_users_table)
     * dan relasi Eloquent dinas() (pakai kolom dinas_id) sama-sama bernama
     * "dinas" — Laravel selalu mengutamakan kolom asli daripada menjalankan
     * relasi, jadi $user->dinas mengembalikan STRING, bukan objek Dinas.
     * Ini bikin "$user->dinas->nama_dinas" error ("Attempt to read property
     * on string"). Kolom lama ini sudah tidak dipakai kode manapun sejak
     * pindah ke dinas_id, jadi aman dihapus.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'dinas')) {
                $table->dropColumn('dinas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dinas')->nullable()->after('role');
        });
    }
};
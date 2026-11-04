<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dinases', function (Blueprint $table) {
            // Informasi kontak dinas
            $table->string('telepon', 50)->nullable();
            $table->string('faksimile', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('logo', 255)->nullable();

            // Informasi pejabat dinas
            $table->string('nama_pejabat', 255)->nullable();
            $table->string('jabatan_pejabat', 255)->nullable();
            $table->string('pangkat_pejabat', 100)->nullable();
            $table->string('nip_pejabat', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dinases', function (Blueprint $table) {
            $table->dropColumn([
                'telepon',
                'faksimile',
                'email',
                'website',
                'logo',
                'nama_pejabat',
                'jabatan_pejabat',
                'pangkat_pejabat',
                'nip_pejabat',
            ]);
        });
    }
};
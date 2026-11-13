
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur akhir tabel dinases.
 *
 * Mencakup:
 * - Informasi dinas
 * - Kontak dinas
 * - Informasi pejabat dinas
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dinases', function (Blueprint $table) {
            $table->id();

            // Informasi dinas
            $table->string('nama_dinas');
            $table->text('deskripsi')->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('status')->default(true);

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
            $table->string('golongan_pejabat', 20)->nullable();
            $table->string('nip_pejabat', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dinases');
    }
};

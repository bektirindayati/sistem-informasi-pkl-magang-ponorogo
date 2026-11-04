
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur utama tabel users.
 *
 * Mencakup:
 * - Autentikasi pengguna
 * - Login Google
 * - Role dan relasi dinas
 * - Profil pengguna
 * - Password reset tokens
 * - Sessions
 *
 * Kolom kategori ditambahkan melalui migration terpisah.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Informasi akun
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Autentikasi
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();
            $table->string('avatar')->nullable();

            // Hak akses pengguna
            // user | admin | super_admin
            $table->string('role')->default('user');

            // Relasi admin dengan dinas
            $table->foreignId('dinas_id')
                ->nullable()
                ->constrained('dinases')
                ->nullOnDelete();

            // Profil pengguna
            // Digunakan sebagai data default formulir pendaftaran
            $table->string('nama_lengkap')->nullable();
            $table->string('nim_nisn')->nullable();
            $table->string('instansi')->nullable();
            $table->string('jurusan')->nullable();

            // Informasi kontak
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();

            // Remember token dan timestamps
            $table->rememberToken();
            $table->timestamps();
        });

        // Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

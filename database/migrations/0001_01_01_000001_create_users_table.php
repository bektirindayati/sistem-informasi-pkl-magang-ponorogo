<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur akhir tabel users (gabungan 6 migrasi lama), plus
 * password_reset_tokens dan sessions bawaan Laravel.
 * Butuh tabel dinases sudah ada lebih dulu (foreign key dinas_id).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Nullable karena akun dibuat lewat login Google
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();
            $table->string('avatar')->nullable();

            // Role: user | admin | super_admin (disalin ke Spatie oleh RolePermissionSeeder)
            $table->string('role')->default('user');

            // Admin dinas terikat ke satu dinas; user biasa & super admin kosong
            $table->foreignId('dinas_id')->nullable()->constrained('dinases')->nullOnDelete();

            // Profil akademik, dipakai untuk auto-fill form pendaftaran
            $table->string('nim_nisn')->nullable();
            $table->string('instansi')->nullable();
            $table->string('jurusan')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

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
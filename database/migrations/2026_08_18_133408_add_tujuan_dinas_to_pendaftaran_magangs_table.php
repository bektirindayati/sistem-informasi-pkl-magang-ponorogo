<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('pendaftaran_magangs', function (Blueprint $table) {
        $table->string('tujuan_dinas')->nullable(); // Kolom pilihan instansi pendaftar
    });
}

public function down(): void
{
    Schema::table('pendaftaran_magangs', function (Blueprint $table) {
        $table->dropColumn('tujuan_dinas');
    });
}
};

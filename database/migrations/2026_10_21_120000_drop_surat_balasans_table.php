<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Surat balasan kini disimpan di kolom pendaftaran_magangs.
        Schema::dropIfExists('surat_balasans');
    }

    public function down(): void
    {
        // Tidak dikembalikan: tabel lama sudah tidak dipakai.
    }
};
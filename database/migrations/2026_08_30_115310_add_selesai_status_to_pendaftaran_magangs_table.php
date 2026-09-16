<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pendaftaran_magangs
            MODIFY status ENUM(
                'draft',
                'pending',
                'diterima',
                'ditolak',
                'selesai'
            )
            NOT NULL DEFAULT 'draft'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE pendaftaran_magangs
            MODIFY status ENUM(
                'draft',
                'pending',
                'diterima',
                'ditolak'
            )
            NOT NULL DEFAULT 'draft'
        ");
    }
};
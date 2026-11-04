<?php

use App\Models\Dinas;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumentasi_magangs', function (Blueprint $table) {
            // Nullable: data lama belum punya pemilik. Bila dinas dihapus, dokumentasi tidak ikut terhapus.
            $table->foreignId('dinas_id')
                ->nullable()
                ->after('id')
                ->constrained((new Dinas)->getTable())
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dokumentasi_magangs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dinas_id');
        });
    }
};
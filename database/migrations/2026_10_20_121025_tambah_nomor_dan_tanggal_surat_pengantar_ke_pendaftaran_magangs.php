
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->string('nomor_surat_pengantar')->nullable();
            $table->date('tanggal_surat_pengantar')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_surat_pengantar',
                'tanggal_surat_pengantar',
            ]);
        });
    }
};

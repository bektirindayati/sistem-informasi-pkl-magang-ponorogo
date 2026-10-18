<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kontak, kop surat, dan pejabat penandatangan per dinas (default isian surat balasan).
 * Hanya menambah kolom yang BELUM ada, jadi aman walau sebagian sudah dibuat sebelumnya.
 */
return new class extends Migration
{
    private const KOLOM = [
        'telepon', 'faksimile', 'email', 'website', 'logo',
        'nama_pejabat', 'jabatan_pejabat', 'pangkat_pejabat', 'nip_pejabat',
    ];

    public function up(): void
    {
        Schema::table('dinases', function (Blueprint $table) {
            foreach (self::KOLOM as $nama) {
                if (! Schema::hasColumn('dinases', $nama)) {
                    $table->string($nama)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        $ada = array_values(array_filter(self::KOLOM, fn ($nama) => Schema::hasColumn('dinases', $nama)));

        if ($ada) {
            Schema::table('dinases', fn (Blueprint $table) => $table->dropColumn($ada));
        }
    }
};
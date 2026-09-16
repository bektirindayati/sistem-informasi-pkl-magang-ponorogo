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
        Schema::table('instansi_bidangs', function (Blueprint $table) {
            $table->foreignId('dinas_id')
                ->after('id')
                ->constrained('dinases')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instansi_bidangs', function (Blueprint $table) {
            $table->dropForeign(['dinas_id']);
            $table->dropColumn('dinas_id');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->foreignId('dinas_id')
                ->nullable()
                ->after('user_id')
                ->constrained('dinases')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_magangs', function (Blueprint $table) {
            $table->dropForeign(['dinas_id']);
            $table->dropColumn('dinas_id');
        });
    }
};
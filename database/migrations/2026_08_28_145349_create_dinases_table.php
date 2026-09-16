// Migration Dinas //

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dinases', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dinas');
            $table->text('deskripsi')->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dinases');
    }
};
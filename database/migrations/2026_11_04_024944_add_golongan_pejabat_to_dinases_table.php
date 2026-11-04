
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dinases', function (Blueprint $table) {
            $table->string('golongan_pejabat', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dinases', function (Blueprint $table) {
            $table->dropColumn('golongan_pejabat');
        });
    }
};

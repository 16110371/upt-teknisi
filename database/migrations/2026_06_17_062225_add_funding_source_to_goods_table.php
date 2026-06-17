<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->enum('funding_source', [
                'BOS',
                'BOSDA',
                'Sekolah',
                'Bantuan',
            ])->nullable()->after('is_consumable');
        });
    }

    public function down(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn('funding_source');
        });
    }
};

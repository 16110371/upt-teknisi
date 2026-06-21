<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_units', function (Blueprint $table) {
            // ✅ Hapus foreign key lama
            $table->dropForeign(['unit_id']);

            // ✅ Buat foreign key baru ke good_units
            $table->foreign('unit_id')
                ->references('id')
                ->on('good_units')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('request_units', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->foreign('unit_id')
                ->references('id')
                ->on('infrastructure_units')
                ->cascadeOnDelete();
        });
    }
};

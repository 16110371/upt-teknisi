<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Hapus unique constraint pada code
        Schema::table('goods', function (Blueprint $table) {
            $table->dropUnique('goods_code_unique');
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->foreignId('goods_type_id')
                ->nullable()
                ->after('goods_category_id')
                ->constrained('goods_types')
                ->nullOnDelete();

            $table->string('photo')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropForeign(['goods_type_id']);
            $table->dropColumn(['goods_type_id', 'photo']);
            $table->unique('code');
        });
    }
};

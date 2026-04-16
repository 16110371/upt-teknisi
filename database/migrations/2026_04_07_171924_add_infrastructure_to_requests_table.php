<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->foreignId('infrastructure_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->after('location_id');
            $table->integer('damaged_quantity')
                ->default(1)
                ->after('infrastructure_id');
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['infrastructure_id']);
            $table->dropColumn(['infrastructure_id', 'damaged_quantity']);
        });
    }
};

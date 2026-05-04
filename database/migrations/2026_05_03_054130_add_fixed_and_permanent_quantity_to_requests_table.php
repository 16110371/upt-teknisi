<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->integer('fixed_quantity')->default(0)->after('damaged_quantity');
            $table->integer('permanent_quantity')->default(0)->after('fixed_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn(['fixed_quantity', 'permanent_quantity']);
        });
    }
};

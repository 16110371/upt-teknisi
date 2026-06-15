<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // A, B, C, dst
            $table->string('name');           // Alat, Bahan, dst
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_categories');
    }
};

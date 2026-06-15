<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['masuk', 'keluar', 'alokasi', 'retur']);
            $table->integer('quantity');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reference'); // bisa ke procurement atau allocation
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};

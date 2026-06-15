<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('good_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete(); // lokasi dari UPT
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();     // yang mengalokasikan
            $table->integer('quantity');       // jumlah yang dialokasikan
            $table->date('allocation_date');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('good_allocations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_check_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_check_id')->constrained()->cascadeOnDelete();
            $table->foreignId('infrastructure_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['OK', 'Bermasalah'])->default('OK');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_check_items');
    }
};

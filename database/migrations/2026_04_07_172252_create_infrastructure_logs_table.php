<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infrastructure_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infrastructure_id')->constrained()->cascadeOnDelete();
            $table->foreignId('request_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['rusak', 'selesai', 'manual']);
            $table->integer('quantity');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infrastructure_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('infrastructure_units')->cascadeOnDelete();
            $table->foreignId('request_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['rusak', 'selesai', 'permanent', 'manual']);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_logs');
    }
};

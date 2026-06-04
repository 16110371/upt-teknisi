<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('infrastructure_units')->cascadeOnDelete();
            $table->enum('type', ['rusak', 'diperbaiki', 'permanen'])->default('rusak');
            $table->timestamps();

            // ✅ Cegah duplikat unit per request per type
            $table->unique(['request_id', 'unit_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_units');
    }
};

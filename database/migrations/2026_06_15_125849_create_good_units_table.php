<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('good_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_id')->constrained()->cascadeOnDelete();
            $table->foreignId('good_allocation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('good_units');
    }
};

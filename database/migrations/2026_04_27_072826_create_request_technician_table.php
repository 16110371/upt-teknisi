<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_technician', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // ✅ Cegah duplikat
            $table->unique(['request_id', 'technician_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_technician');
    }
};

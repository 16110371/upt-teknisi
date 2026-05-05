<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infrastructure_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infrastructure_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // PC-TJKT4-001
            $table->enum('status', ['good', 'broken', 'permanent_broken'])->default('good');
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true); // ✅ untuk nonaktifkan unit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infrastructure_units');
    }
};

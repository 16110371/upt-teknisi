<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->cascadeOnDelete();
            $table->integer('threshold_days'); // 7, 30, 60, 90, dst
            $table->timestamp('sent_at');
            $table->timestamps();

            // ✅ Pastikan tidak duplikat per request per threshold
            $table->unique(['request_id', 'threshold_days']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_notification_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procurement_request_id')->constrained()->cascadeOnDelete();
            $table->string('name');            // nama barang
            $table->string('specification')->nullable(); // spesifikasi
            $table->integer('quantity');       // jumlah
            $table->string('unit');            // satuan (pcs, unit, set, dll)
            $table->decimal('estimated_price', 15, 2)->nullable(); // estimasi harga
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_items');
    }
};

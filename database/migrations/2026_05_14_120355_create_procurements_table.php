<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('item_name'); // Nama barang
            $table->integer('quantity')->default(1); // Jumlah
            $table->decimal('estimated_price', 15, 2); // Harga satuan
            // Kita gunakan virtual column untuk total harga agar otomatis terhitung di DB
            $table->decimal('total_price', 15, 2)->virtualAs('quantity * estimated_price');
            $table->text('description')->nullable(); // Spesifikasi/Catatan
            $table->string('status')->default('pending'); // pending, approved, purchased
            $table->date('requested_at')->useCurrent(); // Tanggal pengajuan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};

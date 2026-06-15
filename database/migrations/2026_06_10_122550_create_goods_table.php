<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();  // kode inventaris: A24-TJKT3-25-16
            $table->foreignId('goods_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procurement_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');            // nama barang
            $table->string('brand')->nullable(); // merk
            $table->string('specification')->nullable(); // spesifikasi
            $table->string('unit');            // satuan
            $table->integer('quantity');       // jumlah total
            $table->integer('stock');          // stok tersedia (belum dialokasikan)
            $table->decimal('price', 15, 2)->nullable(); // harga satuan
            $table->date('purchase_date')->nullable();   // tanggal pembelian
            $table->boolean('is_consumable')->default(false); // barang habis pakai?
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};

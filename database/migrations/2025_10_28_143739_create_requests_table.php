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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->date('request_date')->default(now()); // tanggal permintaan
            $table->string('requester_name'); // nama peminta
            $table->string('requester_contact')->nullable(); // kontak opsional
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // kategori
            $table->foreignId('location_id')->constrained()->cascadeOnDelete(); // lokasi
            $table->text('description'); // deskripsi masalah
            $table->enum('status', ['Pending', 'Dikerjakan', 'Selesai'])->default('Pending'); // status
            $table->foreignId('technician_id')->nullable()->constrained()->nullOnDelete(); // teknisi penanggung jawab
            $table->timestamp('handled_at')->nullable(); // tanggal penanganan selesai
            $table->string('photo')->nullable(); // foto kerusakan
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};

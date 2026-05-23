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
        Schema::table('procurements', function (Blueprint $table) {
            // 1. Pastikan dulu kolom location_id ada dengan tipe data yang sama dengan id di tabel locations
            // Jika sudah ada sebelumnya, ganti menjadi unsignedBigInteger agar bisa jadi foreign key
            $table->unsignedBigInteger('location_id')->nullable()->after('description');

            // 2. Sekarang baru pasang foreign key-nya
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('procurements', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
            $table->string('location')->nullable();
        });
    }
};

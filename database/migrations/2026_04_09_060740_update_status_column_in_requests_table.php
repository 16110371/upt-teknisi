<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Ubah ENUM dulu dengan menggabungkan nilai lama & baru
        DB::statement("ALTER TABLE requests MODIFY COLUMN status ENUM('Pending','Proses','Dikerjakan','Menunggu Part','Selesai','Tidak Diperbaiki') NOT NULL DEFAULT 'Pending'");

        // ✅ Baru update data lama
        DB::statement("UPDATE requests SET status = 'Dikerjakan' WHERE status = 'Proses'");

        // ✅ Hapus nilai lama dari ENUM
        DB::statement("ALTER TABLE requests MODIFY COLUMN status ENUM('Pending','Dikerjakan','Menunggu Part','Selesai','Tidak Diperbaiki') NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        DB::statement("UPDATE requests SET status = 'Proses' WHERE status = 'Dikerjakan'");
        DB::statement("ALTER TABLE requests MODIFY COLUMN status ENUM('Pending','Dikerjakan','Selesai') NOT NULL DEFAULT 'Pending'");
    }
};

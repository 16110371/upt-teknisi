<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurements', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan nama file gambar (nullable artinya boleh kosong)
            $table->string('signed_document_photo')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('procurements', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('signed_document_photo');
        });
    }
};

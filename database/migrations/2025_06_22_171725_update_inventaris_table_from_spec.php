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
        Schema::table('inventaris', function (Blueprint $table) {
            // Ubah nama kolom yang relevan
            $table->renameColumn('letak', 'lokasi');
            $table->renameColumn('keterangan', 'catatan');

            // Ubah (modify) tipe kolom status
            $table->enum('status', ['baik', 'rusak', 'hilang'])->default('baik')->change();

            // Tambahkan kolom foto
            $table->string('foto')->nullable()->after('catatan');

            // Hapus kolom lama yang tidak lagi digunakan
            $table->dropColumn('tanggal_beli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            // Mengembalikan perubahan jika migration di-rollback
            $table->renameColumn('lokasi', 'letak');
            $table->renameColumn('catatan', 'keterangan');
            
            $table->enum('status', ['baik', 'rusak', 'hilang', 'maintenance'])->default('baik')->change();

            $table->dropColumn('foto');

            $table->date('tanggal_beli')->nullable();
        });
    }
};

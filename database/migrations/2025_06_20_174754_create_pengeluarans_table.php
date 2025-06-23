<?php
// File: database/migrations/xxxx_xx_xx_create_pengeluarans_table.php

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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pengeluaran');
            $table->string('jenis_pengeluaran');
            $table->decimal('nominal', 12, 2);
            $table->enum('kategori', [
                'listrik', 
                'air', 
                'internet', 
                'maintenance', 
                'gaji', 
                'kebersihan',
                'keamanan',
                'pajak',
                'asuransi',
                'lainnya'
            ]);
            $table->text('keterangan_detail');
            $table->text('catatan_tambahan')->nullable();
            $table->string('bukti_pembayaran')->nullable(); // For receipt upload
            $table->boolean('is_recurring')->default(false); // For recurring expenses
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
            
            // Index for better performance
            $table->index(['tanggal_pengeluaran', 'kategori']);
            $table->index('status_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
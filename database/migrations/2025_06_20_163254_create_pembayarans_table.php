<?php
// File: database/migrations/xxxx_xx_xx_create_pembayarans_table.php

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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('no_kamar');
            $table->string('bulan_tahun'); // Format: 2024-01
            $table->decimal('jumlah_pembayaran', 10, 2);
            $table->date('tanggal_pembayaran');
            $table->enum('status_pembayaran', ['lunas', 'belum_bayar', 'terlambat'])->default('belum_bayar');
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'e_wallet', 'lainnya'])->default('cash');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Prevent duplicate payment for same period
            $table->unique(['tenant_id', 'bulan_tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
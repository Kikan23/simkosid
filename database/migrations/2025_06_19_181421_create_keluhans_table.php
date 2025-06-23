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
        Schema::create('keluhans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('nama_penghuni');
            $table->string('no_kamar');
            $table->date('tanggal_keluhan');
            $table->text('deskripsi_keluhan');
            $table->enum('status_keluhan', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->date('tanggal_penyelesaian')->nullable();
            $table->text('catatan_penyelesaian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhans');
    }
};

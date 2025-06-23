<?php
// File: database/migrations/xxxx_xx_xx_create_pegawais_table.php

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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            $table->text('jobdesk');
            $table->string('no_telepon');
            $table->string('jadwal_kerja');
            $table->enum('status_pegawai', ['aktif', 'tidak_aktif', 'cuti']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
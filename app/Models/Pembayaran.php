<?php
// File: app/Models/Pembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'no_kamar',
        'bulan_tahun',
        'jumlah_pembayaran',
        'tanggal_pembayaran',
        'status_pembayaran',
        'metode_pembayaran',
        'catatan'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'jumlah_pembayaran' => 'decimal:2'
    ];

    // Relationship dengan Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    // Helper method untuk format bulan tahun
    public function getFormattedBulanTahunAttribute()
    {
        $bulanIndo = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        $parts = explode('-', $this->bulan_tahun);
        return $bulanIndo[$parts[1]] . ' ' . $parts[0];
    }

    // Helper method untuk format rupiah
    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_pembayaran, 0, ',', '.');
    }
}
<?php
// File: app/Models/Keluhan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Keluhan extends Model
{
    use HasFactory;

    protected $table = 'keluhans';

    protected $fillable = [
        'tenant_id',
        'nama_penghuni',
        'no_kamar',
        'tanggal_keluhan',
        'jenis_keluhan',
        'deskripsi_keluhan',
        'status_keluhan',
        'tanggal_penyelesaian',
        'catatan_penyelesaian'
    ];

    protected $casts = [
        'tanggal_keluhan' => 'date',
        'tanggal_penyelesaian' => 'date',
    ];

    protected $attributes = [
        'status_keluhan' => 'pending'
    ];

    // Accessor untuk mendapatkan label status
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai'
        ];

        return $labels[$this->status_keluhan] ?? 'Unknown';
    }

    // Accessor untuk mendapatkan badge class status
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'badge-warning',
            'diproses' => 'badge-info',
            'selesai' => 'badge-success'
        ];

        return $classes[$this->status_keluhan] ?? 'badge-secondary';
    }

    // Accessor untuk mendapatkan label jenis keluhan
    public function getJenisKeluhanLabelAttribute()
    {
        $labels = [
            'fasilitas' => 'Fasilitas',
            'kebersihan' => 'Kebersihan',
            'keamanan' => 'Keamanan',
            'layanan' => 'Layanan',
            'lainnya' => 'Lainnya'
        ];

        return $labels[$this->jenis_keluhan] ?? 'Unknown';
    }

    // Method untuk mengecek apakah keluhan sudah selesai
    public function isCompleted()
    {
        return $this->status_keluhan === 'selesai';
    }

    // Method untuk mengecek apakah keluhan sedang diproses
    public function isInProgress()
    {
        return $this->status_keluhan === 'diproses';
    }

    // Method untuk mengecek apakah keluhan masih pending
    public function isPending()
    {
        return $this->status_keluhan === 'pending';
    }

    // Method untuk menghitung durasi penyelesaian keluhan
    public function getDurasiPenyelesaianAttribute()
    {
        if (!$this->tanggal_penyelesaian) {
            return null;
        }

        return $this->tanggal_keluhan->diffInDays($this->tanggal_penyelesaian);
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_keluhan', $status);
    }

    // Scope untuk filter berdasarkan jenis keluhan
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_keluhan', $jenis);
    }

    // Scope untuk keluhan yang masih aktif (belum selesai)
    public function scopeActive($query)
    {
        return $query->whereIn('status_keluhan', ['pending', 'diproses']);
    }

    // Scope untuk keluhan yang sudah selesai
    public function scopeCompleted($query)
    {
        return $query->where('status_keluhan', 'selesai');
    }

    // Scope untuk keluhan berdasarkan bulan
    public function scopeByMonth($query, $month, $year = null)
    {
        if (!$year) {
            $year = Carbon::now()->year;
        }

        return $query->whereMonth('tanggal_keluhan', $month)
                    ->whereYear('tanggal_keluhan', $year);
    }

    // Static method untuk mendapatkan statistik
    public static function getStatistics()
    {
        return [
            'total' => self::count(),
            'pending' => self::byStatus('pending')->count(),
            'diproses' => self::byStatus('diproses')->count(),
            'selesai' => self::byStatus('selesai')->count(),
            'this_month' => self::byMonth(Carbon::now()->month)->count(),
        ];
    }

    // Relasi dengan Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
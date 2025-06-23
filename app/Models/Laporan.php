<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis',
        'periode_awal',
        'periode_akhir',
        'data',
        'catatan',
        'user_id'
    ];

    protected $casts = [
        'data' => 'array',
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter jenis laporan
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    // Scope untuk filter periode
    public function scopePeriode($query, $awal, $akhir)
    {
        return $query->whereBetween('periode_awal', [$awal, $akhir])
                    ->orWhereBetween('periode_akhir', [$awal, $akhir]);
    }
}
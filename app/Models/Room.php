<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kamar',
        'status',
        'kapasitas',
        // tambahkan field lain sesuai kebutuhan 
    ];
}

<?php
// File: app/Models/Pegawai.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pegawai',
        'jobdesk',
        'no_telepon',
        'jadwal_kerja',
        'status_pegawai'
    ];
}
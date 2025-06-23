<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_penyewa',
        'nomor_ktp',
        'telepon',
        'email',
        'kamar_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'status',
        'catatan',
        'foto_ktp',
        'dokumen_kontrak'
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = ['foto_ktp_url', 'dokumen_kontrak_url'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_masuk' => 'date:Y-m-d',
        'tanggal_keluar' => 'date:Y-m-d',
    ];

    /**
     * Get the kamar that owns the tenant.
     * 
     * Relasi ini menghubungkan tenant dengan kamar berdasarkan 'kamar_id' di tabel tenants
     * dengan 'id' di tabel kamars.
     */
    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    /**
     * Get the pembayarans for the tenant.
     */
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'tenant_id');
    }

    /**
     * Get the full URL for the KTP photo.
     */
    public function getFotoKtpUrlAttribute()
    {
        if ($this->foto_ktp) {
            return Storage::disk('public')->url($this->foto_ktp);
        }
        return null;
    }

    /**
     * Get the full URL for the contract document.
     */
    public function getDokumenKontrakUrlAttribute()
    {
        if ($this->dokumen_kontrak) {
            return Storage::disk('public')->url($this->dokumen_kontrak);
        }
        return null;
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif' => 'Aktif',
            'nonaktif' => 'Non-Aktif',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'aktif' => 'badge-success',
            'nonaktif' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}
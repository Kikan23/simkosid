<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kamar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_kamar',
        'tipe_kamar',
        'tarif_bulanan',
        'fasilitas',
        'status',
    ];

    protected $casts = [
        'tarif_bulanan' => 'decimal:2',
    ];

    // Relasi ke semua tenant (untuk history)
    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'kamar_id');
    }

    /**
     * Get the current active tenant for the room.
     */
    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'kamar_id')->where('status', 'aktif');
    }

    /**
     * Get the active tenant for the room.
     */
    public function penghuniAktif()
    {
        return $this->tenants()->where('status', 'aktif')->first();
    }

    // Update status kamar berdasarkan tenant aktif
    public function updateStatusFromTenant()
    {
        $this->update(['status' => $this->tenant()->exists() ? 'dihuni' : 'kosong']);
    }

    // Accessor untuk warna status
    public function getStatusColorAttribute()
    {
        return $this->status === 'dihuni' ? 'danger' : 'success';
    }

    // Accessor untuk label tipe kamar
    public function getTipeKamarLabelAttribute()
    {
        return match($this->tipe_kamar) {
            'standard' => 'Standard',
            'premium' => 'Premium',
            'vip' => 'VIP',
            default => ucfirst($this->tipe_kamar)
        };
    }

    // Scope untuk kamar kosong
    public function scopeKosong($query)
    {
        return $query->where('status', 'kosong');
    }

    // Scope untuk kamar dihuni
    public function scopeDihuni($query)
    {
        return $query->where('status', 'dihuni');
    }

    // Check apakah kamar dihuni
    public function isDihuni()
    {
        return $this->status === 'dihuni';
    }

    // Check apakah kamar kosong
    public function isKosong()
    {
        return $this->status === 'kosong';
    }

}
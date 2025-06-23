<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_pengeluaran',
        'kategori',
        'nominal',
        'tanggal_pengeluaran',
        'keterangan_detail',
        'is_recurring',
        'recurring_interval',
        'status_approval'
    ];

    protected $dates = [
        'tanggal_pengeluaran'
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'date',
        'nominal' => 'decimal:2',
        'is_recurring' => 'boolean'
    ];

    // Accessor for formatted nominal
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    // Accessor for kategori info
    public function getKategoriInfoAttribute()
    {
        $kategoriList = self::getKategoriWithIcons();
        return $kategoriList[$this->kategori] ?? [
            'name' => $this->kategori,
            'icon' => 'fas fa-question',
            'color' => 'secondary'
        ];
    }

    // Static method to get all categories with icons
    public static function getKategoriWithIcons()
    {
        return [
            'makanan' => [
                'name' => 'Makanan & Minuman',
                'icon' => 'fas fa-utensils',
                'color' => 'success'
            ],
            'transportasi' => [
                'name' => 'Transportasi',
                'icon' => 'fas fa-car',
                'color' => 'primary'
            ],
            'kesehatan' => [
                'name' => 'Kesehatan',
                'icon' => 'fas fa-heartbeat',
                'color' => 'danger'
            ],
            'pendidikan' => [
                'name' => 'Pendidikan',
                'icon' => 'fas fa-graduation-cap',
                'color' => 'info'
            ],
            'hiburan' => [
                'name' => 'Hiburan',
                'icon' => 'fas fa-gamepad',
                'color' => 'warning'
            ],
            'belanja' => [
                'name' => 'Belanja',
                'icon' => 'fas fa-shopping-bag',
                'color' => 'secondary'
            ],
            'tagihan' => [
                'name' => 'Tagihan',
                'icon' => 'fas fa-file-invoice-dollar',
                'color' => 'dark'
            ],
            'investasi' => [
                'name' => 'Investasi',
                'icon' => 'fas fa-chart-line',
                'color' => 'success'
            ],
            'asuransi' => [
                'name' => 'Asuransi',
                'icon' => 'fas fa-shield-alt',
                'color' => 'primary'
            ],
            'lainnya' => [
                'name' => 'Lainnya',
                'icon' => 'fas fa-ellipsis-h',
                'color' => 'secondary'
            ]
        ];
    }

    // Scope for current month
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('tanggal_pengeluaran', Carbon::now()->month)
                    ->whereYear('tanggal_pengeluaran', Carbon::now()->year);
    }

    // Scope for last month
    public function scopeLastMonth($query)
    {
        $lastMonth = Carbon::now()->subMonth();
        return $query->whereMonth('tanggal_pengeluaran', $lastMonth->month)
                    ->whereYear('tanggal_pengeluaran', $lastMonth->year);
    }

    // Scope for approved expenses
    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    // Scope for pending expenses
    public function scopePending($query)
    {
        return $query->where('status_approval', 'pending');
    }

    // Scope for recurring expenses
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    // Method to get expenses by category
    public static function getExpensesByCategory()
    {
        return self::selectRaw('kategori, SUM(nominal) as total')
                  ->groupBy('kategori')
                  ->orderBy('total', 'desc')
                  ->get();
    }

    // Method to get monthly expenses
    public static function getMonthlyExpenses($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        
        return self::selectRaw('MONTH(tanggal_pengeluaran) as month, SUM(nominal) as total')
                  ->whereYear('tanggal_pengeluaran', $year)
                  ->groupBy('month')
                  ->orderBy('month')
                  ->get();
    }

    // Method to get total expenses for a specific period
    public static function getTotalExpenses($startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($startDate) {
            $query->where('tanggal_pengeluaran', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('tanggal_pengeluaran', '<=', $endDate);
        }
        
        return $query->sum('nominal');
    }

    // Method to get average monthly expenses
    public static function getAverageMonthlyExpenses($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $monthlyExpenses = self::getMonthlyExpenses($year);
        
        if ($monthlyExpenses->count() == 0) {
            return 0;
        }
        
        return $monthlyExpenses->avg('total');
    }

    // Method to check if expense is overdue (for recurring expenses)
    public function isOverdue()
    {
        if (!$this->is_recurring || !$this->recurring_interval) {
            return false;
        }

        $nextDue = $this->tanggal_pengeluaran->addDays($this->recurring_interval);
        return $nextDue < Carbon::now();
    }

    // Method to get next due date (for recurring expenses)
    public function getNextDueDate()
    {
        if (!$this->is_recurring || !$this->recurring_interval) {
            return null;
        }

        return $this->tanggal_pengeluaran->addDays($this->recurring_interval);
    }
}
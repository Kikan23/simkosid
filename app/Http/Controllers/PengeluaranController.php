<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::query();

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by month
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pengeluaran', $request->bulan);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('jenis_pengeluaran', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan_detail', 'like', '%' . $request->search . '%');
            });
        }

        $pengeluarans = $query->orderBy('tanggal_pengeluaran', 'desc')->paginate(10);

        // Analytics data
        $analytics = $this->getAnalytics();

        // Month list for filter
        $bulanList = $this->getBulanList();

        return view('pengeluaran.index', compact('pengeluarans', 'analytics', 'bulanList'));
    }

    public function create()
    {
        $kategoris = Pengeluaran::getKategoriWithIcons();
        return view('pengeluaran.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required|string|max:255',
            'kategori' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan_detail' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_interval' => 'nullable|integer|min:1',
            'status_approval' => 'required|in:pending,approved,rejected'
        ]);

        Pengeluaran::create($request->all());

        return redirect()->route('pengeluaran.index')
                        ->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function show(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.show', compact('pengeluaran'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $kategoris = Pengeluaran::getKategoriWithIcons();
        return view('pengeluaran.edit', compact('pengeluaran', 'kategoris'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required|string|max:255',
            'kategori' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan_detail' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_interval' => 'nullable|integer|min:1',
            'status_approval' => 'required|in:pending,approved,rejected'
        ]);

        $pengeluaran->update($request->all());

        return redirect()->route('pengeluaran.index')
                        ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')
                        ->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    public function analytics()
    {
        $analytics = $this->getAnalytics();
        
        // Additional analytics data
        $monthlyData = $this->getMonthlyData();
        $categoryData = $this->getCategoryData();
        $recentTransactions = Pengeluaran::orderBy('tanggal_pengeluaran', 'desc')->take(5)->get();

        return view('pengeluaran.analytics', compact('analytics', 'monthlyData', 'categoryData', 'recentTransactions'));
    }

    private function getAnalytics()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastMonthYear = Carbon::now()->subMonth()->year;

        $totalBulanIni = Pengeluaran::whereMonth('tanggal_pengeluaran', $currentMonth)
                                   ->whereYear('tanggal_pengeluaran', $currentYear)
                                   ->sum('nominal');

        $totalBulanLalu = Pengeluaran::whereMonth('tanggal_pengeluaran', $lastMonth)
                                    ->whereYear('tanggal_pengeluaran', $lastMonthYear)
                                    ->sum('nominal');

        $persentasePerubahan = 0;
        if ($totalBulanLalu > 0) {
            $persentasePerubahan = (($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100;
        }

        $pengeluaranKategori = Pengeluaran::select('kategori', DB::raw('count(*) as total'))
                                         ->groupBy('kategori')
                                         ->get();

        return [
            'total_bulan_ini' => $totalBulanIni,
            'total_bulan_lalu' => $totalBulanLalu,
            'persentase_perubahan' => $persentasePerubahan,
            'pengeluaran_kategori' => $pengeluaranKategori
        ];
    }

    private function getBulanList()
    {
        return [
            ['value' => 1, 'text' => 'Januari'],
            ['value' => 2, 'text' => 'Februari'],
            ['value' => 3, 'text' => 'Maret'],
            ['value' => 4, 'text' => 'April'],
            ['value' => 5, 'text' => 'Mei'],
            ['value' => 6, 'text' => 'Juni'],
            ['value' => 7, 'text' => 'Juli'],
            ['value' => 8, 'text' => 'Agustus'],
            ['value' => 9, 'text' => 'September'],
            ['value' => 10, 'text' => 'Oktober'],
            ['value' => 11, 'text' => 'November'],
            ['value' => 12, 'text' => 'Desember'],
        ];
    }

    private function getMonthlyData()
    {
        $monthlyData = [];
        $currentYear = Carbon::now()->year;

        for ($i = 1; $i <= 12; $i++) {
            $total = Pengeluaran::whereMonth('tanggal_pengeluaran', $i)
                               ->whereYear('tanggal_pengeluaran', $currentYear)
                               ->sum('nominal');
            
            $monthlyData[] = [
                'month' => $this->getBulanList()[$i-1]['text'],
                'total' => $total
            ];
        }

        return $monthlyData;
    }

    private function getCategoryData()
    {
        return Pengeluaran::select('kategori', DB::raw('sum(nominal) as total'))
                         ->groupBy('kategori')
                         ->orderBy('total', 'desc')
                         ->get()
                         ->map(function($item) {
                             $kategoriInfo = Pengeluaran::getKategoriWithIcons()[$item->kategori] ?? null;
                             return [
                                 'kategori' => $item->kategori,
                                 'name' => $kategoriInfo['name'] ?? $item->kategori,
                                 'total' => $item->total,
                                 'formatted_total' => 'Rp ' . number_format($item->total, 0, ',', '.')
                             ];
                         });
    }

    public function showModal($id)
    {
        $pengeluaran = \App\Models\Pengeluaran::findOrFail($id);
        // Siapkan data lain jika perlu
        return view('pengeluaran._modal_detail', compact('pengeluaran'));
    }
}
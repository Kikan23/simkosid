<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Kamar;
use App\Models\Pegawai;
use App\Models\Keluhan;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $monthlyExpenses = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyExpenses[] = Pengeluaran::whereMonth('tanggal_pengeluaran', $i)
                                   ->whereYear('tanggal_pengeluaran', now()->year)
                                   ->sum('nominal');
        }

        return view('layouts.kaiadmin', [
            'totalTenants' => Tenant::count(),
            'occupiedRooms' => Kamar::where('status', 'dihuni')->count(),
            'vacantRooms' => Kamar::where('status', 'kosong')->count(),
            'totalEmployees' => Pegawai::count(),
            'recentComplaints' => Keluhan::with('tenant.kamar')->latest()->take(5)->get(),
            'recentPayments' => Pembayaran::with('tenant')->latest()->take(5)->get(),
            'monthlyExpenses' => $monthlyExpenses,
        ]);
    }
}

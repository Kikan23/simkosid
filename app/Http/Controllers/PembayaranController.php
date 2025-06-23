<?php
// File: app/Http/Controllers/PembayaranController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tenant;
use App\Models\kamar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with(['tenant']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }
        
        // Filter by month
        if ($request->filled('bulan_tahun')) {
            $query->where('bulan_tahun', $request->bulan_tahun);
        }
        
        // Filter by tenant
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }
        
        $pembayarans = $query->latest()->paginate(10);
        
        // Get data for filters
        $tenants = tenant::where('status', 'aktif')->get();
        $bulanTahunList = Pembayaran::select('bulan_tahun')
                                ->distinct()
                                ->orderBy('bulan_tahun', 'desc')
                                ->pluck('bulan_tahun');
        
        // Dashboard metrics
        $totalPendapatan = Pembayaran::where('status_pembayaran', 'lunas')
                                ->whereMonth('tanggal_pembayaran', Carbon::now()->month)
                                ->sum('jumlah_pembayaran');
        
        $totalTunggakan = Pembayaran::where('status_pembayaran', '!=', 'lunas')->count();
        
        return view('pembayaran.index', compact(
            'pembayarans', 
            'tenants', 
            'bulanTahunList', 
            'totalPendapatan', 
            'totalTunggakan'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenants = Tenant::where('status', 'aktif')->with('kamar')->get();
        return view('pembayaran.create', compact('tenants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'bulan_tahun' => 'required|date_format:Y-m',
            'jumlah_pembayaran' => 'required|numeric|min:0',
            'tanggal_pembayaran' => 'required|date',
            'status_pembayaran' => 'required|in:lunas,belum_bayar,terlambat',
            'metode_pembayaran' => 'required|in:cash,transfer,e_wallet,lainnya',
            'catatan' => 'nullable|string'
        ]);

        // Get tenant data
        $tenant = Tenant::find($validated['tenant_id']);
        if (!$tenant) {
             return back()->withErrors(['error' => 'Data penghuni tidak ditemukan.'])
                    ->withInput();
        }
        $validated['no_kamar'] = $tenant->kamar->nomor_kamar;

        try {
            $pembayaran = Pembayaran::create($validated);
            
            return redirect()->route('pembayaran.index')
                        ->with('success', 'Pembayaran berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan fatal: ' . $e->getMessage()])
                    ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('tenant');
        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        $tenants = tenant::where('status', 'aktif')->with('kamar')->get();
        return view('pembayaran.edit', compact('pembayaran', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'bulan_tahun' => 'required|date_format:Y-m',
            'jumlah_pembayaran' => 'required|numeric|min:0',
            'tanggal_pembayaran' => 'required|date',
            'status_pembayaran' => 'required|in:lunas,belum_bayar,terlambat',
            'metode_pembayaran' => 'required|in:cash,transfer,e_wallet,lainnya',
            'catatan' => 'nullable|string'
        ]);

        // Get tenant data
        $tenant = Tenant::find($validated['tenant_id']);
        if (!$tenant) {
             return back()->withErrors(['error' => 'Data penghuni tidak ditemukan.'])
                    ->withInput();
        }
        $validated['no_kamar'] = $tenant->kamar->nomor_kamar;

        try {
            $pembayaran->update($validated);
            
            return redirect()->route('pembayaran.index')
                           ->with('success', 'Data pembayaran berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan fatal: ' . $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        
        return redirect()->route('pembayaran.index')
                       ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
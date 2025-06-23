<?php
// File: app/Http/Controllers/KeluhanController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\Tenant;
use Carbon\Carbon;

class KeluhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keluhans = Keluhan::latest()->paginate(10);
        return view('keluhan.index', compact('keluhans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenants = Tenant::where('status', 'aktif')->get();
        return view('keluhan.create', compact('tenants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'tanggal_keluhan' => 'required|date',
            'deskripsi_keluhan' => 'required|string',
            'status_keluhan' => 'required|in:pending,diproses,selesai',
        ]);

        $tenant = Tenant::with('kamar')->findOrFail($validated['tenant_id']);

        $data = [
            'tenant_id' => $validated['tenant_id'],
            'nama_penghuni' => $tenant->nama_penyewa,
            'no_kamar' => $tenant->kamar ? $tenant->kamar->nomor_kamar : 'N/A',
            'tanggal_keluhan' => $validated['tanggal_keluhan'],
            'deskripsi_keluhan' => $validated['deskripsi_keluhan'],
            'status_keluhan' => $validated['status_keluhan'],
        ];

        // Auto-set tanggal penyelesaian jika status selesai
        if ($request->status_keluhan === 'selesai') {
            $data['tanggal_penyelesaian'] = Carbon::now()->format('Y-m-d');
        }

        Keluhan::create($data);

        return redirect()->route('keluhan.index')
                        ->with('success', 'Data keluhan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Keluhan $keluhan)
    {
        return view('keluhan.show', compact('keluhan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keluhan $keluhan)
    {
        $tenants = Tenant::where('status', 'aktif')->get();
        return view('keluhan.edit', compact('keluhan', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Keluhan $keluhan)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'tanggal_keluhan' => 'required|date',
            'deskripsi_keluhan' => 'required|string',
            'status_keluhan' => 'required|in:pending,diproses,selesai',
            'tanggal_penyelesaian' => 'nullable|date|after_or_equal:tanggal_keluhan',
            'catatan_penyelesaian' => 'nullable|string'
        ], [
            'tenant_id.required' => 'Penghuni harus dipilih',
            'tenant_id.exists' => 'Penghuni tidak ditemukan',
            'tanggal_keluhan.required' => 'Tanggal keluhan harus diisi',
            'tanggal_keluhan.date' => 'Format tanggal tidak valid',
            'deskripsi_keluhan.required' => 'Deskripsi keluhan harus diisi',
            'status_keluhan.required' => 'Status keluhan harus dipilih',
            'status_keluhan.in' => 'Status keluhan tidak valid',
            'tanggal_penyelesaian.after_or_equal' => 'Tanggal penyelesaian tidak boleh lebih awal dari tanggal keluhan',
            'tanggal_penyelesaian.date' => 'Format tanggal penyelesaian tidak valid'
        ]);

        $tenant = Tenant::with('kamar')->findOrFail($request->tenant_id);

        // Logic untuk menangani perubahan status
        $data = [
            'tenant_id' => $request->tenant_id,
            'nama_penghuni' => $tenant->nama_penyewa,
            'no_kamar' => $tenant->kamar ? $tenant->kamar->nomor_kamar : 'N/A',
            'tanggal_keluhan' => $request->tanggal_keluhan,
            'deskripsi_keluhan' => $request->deskripsi_keluhan,
            'status_keluhan' => $request->status_keluhan,
        ];
        
        // Jika status diubah ke 'selesai' dan tanggal penyelesaian kosong
        if ($request->status_keluhan === 'selesai' && !$request->tanggal_penyelesaian) {
            $data['tanggal_penyelesaian'] = Carbon::now()->format('Y-m-d');
        } elseif ($request->status_keluhan === 'selesai' && $request->tanggal_penyelesaian) {
            $data['tanggal_penyelesaian'] = $request->tanggal_penyelesaian;
        }
        
        // Jika status diubah dari 'selesai' ke status lain, kosongkan tanggal penyelesaian
        if ($request->status_keluhan !== 'selesai' && $keluhan->status_keluhan === 'selesai') {
            $data['tanggal_penyelesaian'] = null;
            $data['catatan_penyelesaian'] = null;
        } elseif ($request->catatan_penyelesaian) {
            $data['catatan_penyelesaian'] = $request->catatan_penyelesaian;
        }

        $keluhan->update($data);

        // Flash message berdasarkan status
        $message = 'Data keluhan berhasil diupdate!';
        if ($request->status_keluhan === 'selesai' && $keluhan->status_keluhan !== 'selesai') {
            $message = 'Keluhan berhasil diselesaikan!';
        } elseif ($request->status_keluhan === 'diproses' && $keluhan->status_keluhan === 'pending') {
            $message = 'Keluhan berhasil diproses!';
        }

        return redirect()->route('keluhan.index')
                        ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keluhan $keluhan)
    {
        $keluhan->delete();

        return redirect()->route('keluhan.index')
                        ->with('success', 'Data keluhan berhasil dihapus!');
    }

    /**
     * Get keluhan statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total' => Keluhan::count(),
            'pending' => Keluhan::where('status_keluhan', 'pending')->count(),
            'diproses' => Keluhan::where('status_keluhan', 'diproses')->count(),
            'selesai' => Keluhan::where('status_keluhan', 'selesai')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Filter keluhan by status
     */
    public function filterByStatus(Request $request)
    {
        $status = $request->get('status');
        $query = Keluhan::query();

        if ($status && in_array($status, ['pending', 'diproses', 'selesai'])) {
            $query->where('status_keluhan', $status);
        }

        $keluhans = $query->latest()->paginate(10);
        
        return view('keluhan.index', compact('keluhans'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class KamarController extends Controller
{
    // Data master untuk tipe kamar, bisa dipindahkan ke config atau service jika lebih kompleks
    private $tipeKamarData = [
        'Standar' => [
            'tarif_bulanan' => 500000,
            'fasilitas' => 'Kipas angin, kamar mandi luar, Wi-Fi biasa, kasur sederhana.'
        ],
        'Premium' => [
            'tarif_bulanan' => 1000000,
            'fasilitas' => 'AC, kamar mandi dalam, Wi-Fi cepat, lemari, meja belajar, kasur tebal.'
        ],
        'VIP' => [
            'tarif_bulanan' => 2000000,
            'fasilitas' => 'Semua fasilitas premium + kulkas mini, smart TV, luas lebih besar, parkiran, layanan laundry atau bersih-bersih.'
        ]
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kamars = Kamar::orderBy('nomor_kamar', 'asc')->get();
        return view('kamar.index', compact('kamars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kamar.create', ['tipeKamarData' => $this->tipeKamarData]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_kamar' => 'required|string|max:255|unique:kamars,nomor_kamar',
            'tipe_kamar' => ['required', Rule::in(array_keys($this->tipeKamarData))],
            'status' => ['required', Rule::in(['kosong', 'dihuni', 'maintenance'])],
        ]);

        // Ambil data tarif dan fasilitas dari array master
        $tipeData = $this->tipeKamarData[$validated['tipe_kamar']];
        $validated['tarif_bulanan'] = $tipeData['tarif_bulanan'];
        $validated['fasilitas'] = $tipeData['fasilitas'];

        Kamar::create($validated);

        return redirect()->route('kamar.index')->with('success', 'Kamar baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kamar $kamar)
    {
        // Load relasi tenants dan pembayarans
        $kamar->load(['tenants.pembayarans']);

        // Statistik pendapatan
        $pendapatanBulanIni = $kamar->tenants->flatMap->pembayarans
            ->where('tanggal_pembayaran', '>=', now()->startOfMonth())
            ->where('tanggal_pembayaran', '<=', now()->endOfMonth())
            ->sum('jumlah_pembayaran');

        $pendapatanBulanLalu = $kamar->tenants->flatMap->pembayarans
            ->where('tanggal_pembayaran', '>=', now()->subMonth()->startOfMonth())
            ->where('tanggal_pembayaran', '<=', now()->subMonth()->endOfMonth())
            ->sum('jumlah_pembayaran');

        $pendapatanTahunIni = $kamar->tenants->flatMap->pembayarans
            ->where('tanggal_pembayaran', '>=', now()->startOfYear())
            ->where('tanggal_pembayaran', '<=', now()->endOfYear())
            ->sum('jumlah_pembayaran');

        return view('kamar.show', compact('kamar', 'pendapatanBulanIni', 'pendapatanBulanLalu', 'pendapatanTahunIni'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kamar $kamar)
    {
        return view('kamar.edit', [
            'kamar' => $kamar,
            'tipeKamarData' => $this->tipeKamarData
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'nomor_kamar' => ['required', 'string', 'max:255', Rule::unique('kamars')->ignore($kamar->id)],
            'tipe_kamar' => ['required', Rule::in(array_keys($this->tipeKamarData))],
            'status' => ['required', Rule::in(['kosong', 'dihuni', 'maintenance'])],
        ]);

        // Ambil data tarif dan fasilitas dari array master
        $tipeData = $this->tipeKamarData[$validated['tipe_kamar']];
        $validated['tarif_bulanan'] = $tipeData['tarif_bulanan'];
        $validated['fasilitas'] = $tipeData['fasilitas'];
        
        // Logika untuk mencegah mengubah status menjadi 'kosong' jika masih ada tenant aktif
        if ($validated['status'] == 'kosong' && $kamar->tenants()->where('status', 'aktif')->exists()) {
            return back()->with('error', 'Tidak dapat mengubah status menjadi "kosong" karena masih ada penyewa aktif.')->withInput();
        }

        $kamar->update($validated);

        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kamar $kamar)
    {
        if ($kamar->tenants()->exists()) {
            return redirect()->route('kamar.index')->with('error', 'Kamar tidak dapat dihapus karena memiliki riwayat penyewa.');
        }
        
        $kamar->delete();
        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
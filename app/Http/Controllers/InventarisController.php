<?php
// File: app/Http/Controllers/InventarisController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris;
use Illuminate\Support\Facades\Storage;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventaris = Inventaris::latest()->get();
        return view('inventaris.index', compact('inventaris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventaris.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'lokasi' => 'required|string|max:100',
            'status' => 'required|in:baik,rusak,hilang',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('inventaris', 'public');
            $data['foto'] = $path;
        }

        Inventaris::create($data);

        return redirect()->route('inventaris.index')
                        ->with('success', 'Aset inventaris berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventaris $inventaris)
    {
        return view('inventaris.show', compact('inventaris'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaris $inventaris)
    {
        return view('inventaris.edit', compact('inventaris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventaris $inventaris)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'lokasi' => 'required|string|max:100',
            'status' => 'required|in:baik,rusak,hilang',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($inventaris->foto) {
                Storage::disk('public')->delete($inventaris->foto);
            }

            $path = $request->file('foto')->store('inventaris', 'public');
            $data['foto'] = $path;
        }

        $inventaris->update($data);

        return redirect()->route('inventaris.index')
                        ->with('success', 'Aset inventaris berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaris $inventaris)
    {
        if ($inventaris->foto) {
            Storage::disk('public')->delete($inventaris->foto);
        }

        $inventaris->delete();

        return redirect()->route('inventaris.index')
                        ->with('success', 'Aset inventaris berhasil dihapus.');
    }
}
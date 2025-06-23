<?php
// File: app/Http/Controllers/PegawaiController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawais = Pegawai::latest()->get();
        return view('pegawai.index', compact('pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'jobdesk' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'jadwal_kerja' => 'required|string|max:255',
            'status_pegawai' => 'required|in:aktif,tidak_aktif,cuti'
        ]);

        Pegawai::create($validated);

        return redirect()->route('pegawai.index')
                        ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {
        return view('pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'jobdesk' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'jadwal_kerja' => 'required|string|max:255',
            'status_pegawai' => 'required|in:aktif,tidak_aktif,cuti'
        ]);

        $pegawai->update($validated);

        return redirect()->route('pegawai.index')
                        ->with('success', 'Data pegawai berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()->route('pegawai.index')
                        ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('kamar')->latest()->get();
        return view('tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Langsung ambil kamar yang statusnya 'kosong'
        $availableRooms = Kamar::where('status', 'kosong')->get();
        return view('tenants.create', compact('availableRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penyewa' => 'required|string|max:255',
            'nomor_ktp' => 'required|string|max:255|unique:tenants,nomor_ktp',
            'telepon' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'kamar_id' => [
                'required',
                'exists:kamars,id',
                // Pastikan kamar yang dipilih masih tersedia saat form disubmit
                Rule::exists('kamars', 'id')->where(function ($query) {
                    $query->where('status', 'kosong');
                }),
            ],
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'catatan' => 'nullable|string',
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_kontrak' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $dataToCreate = $validated;

            if ($request->hasFile('foto_ktp')) {
                $path = $request->file('foto_ktp')->store('documents/ktp', 'public');
                $dataToCreate['foto_ktp'] = $path;
            }

            if ($request->hasFile('dokumen_kontrak')) {
                $path = $request->file('dokumen_kontrak')->store('documents/contracts', 'public');
                $dataToCreate['dokumen_kontrak'] = $path;
            }
            
            // Tambahkan status 'aktif' secara otomatis
            $dataToCreate['status'] = 'aktif';

            // Buat tenant baru
            $tenant = Tenant::create($dataToCreate);

            // Update status kamar menjadi 'dihuni'
            $kamar = Kamar::find($validated['kamar_id']);
            if ($kamar) {
                $kamar->status = 'dihuni';
                $kamar->save();
            }

            DB::commit();

            return redirect()->route('tenants.index')->with('success', 'Penyewa baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat penyewa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Tenant $tenant)
    {
        $tenant->load('kamar');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($tenant);
        }

        return view('tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        // Kamar yang tersedia adalah semua kamar yang statusnya 'kosong'
        // Ditambah kamar yang saat ini dihuni oleh tenant yang sedang diedit
        $availableRooms = Kamar::where('status', 'kosong')
            ->orWhere('id', $tenant->kamar_id)
            ->get();
            
        return view('tenants.edit', compact('tenant', 'availableRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'nama_penyewa' => 'required|string|max:255',
            'nomor_ktp' => ['required', 'string', 'max:255', Rule::unique('tenants')->ignore($tenant->id)],
            'telepon' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'kamar_id' => [
                'required',
                'exists:kamars,id',
                // Validasi bahwa kamar tujuan tersedia, kecuali itu adalah kamar tenant saat ini
                Rule::exists('kamars', 'id')->where(function ($query) use ($tenant) {
                    $query->where('status', 'kosong')->orWhere('id', $tenant->kamar_id);
                }),
            ],
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status' => 'required|in:aktif,nonaktif',
            'catatan' => 'nullable|string',
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_kontrak' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $dataToUpdate = $validated;

            if ($request->hasFile('foto_ktp')) {
                // Hapus file lama jika ada
                if ($tenant->foto_ktp) {
                    Storage::disk('public')->delete($tenant->foto_ktp);
                }
                $path = $request->file('foto_ktp')->store('documents/ktp', 'public');
                $dataToUpdate['foto_ktp'] = $path;
            }

            if ($request->hasFile('dokumen_kontrak')) {
                // Hapus file lama jika ada
                if ($tenant->dokumen_kontrak) {
                    Storage::disk('public')->delete($tenant->dokumen_kontrak);
                }
                $path = $request->file('dokumen_kontrak')->store('documents/contracts', 'public');
                $dataToUpdate['dokumen_kontrak'] = $path;
            }

            $oldKamarId = $tenant->kamar_id;
            $newKamarId = $validated['kamar_id'];

            // Update data tenant
            $tenant->update($dataToUpdate);

            // Logika update status kamar
            if ($oldKamarId != $newKamarId) {
                // Tenant pindah kamar. Kosongkan kamar lama, huni kamar baru.
                Kamar::where('id', $oldKamarId)->update(['status' => 'kosong']);
                Kamar::where('id', $newKamarId)->update(['status' => 'dihuni']);
            }

            // Jika status tenant menjadi non-aktif, kamarnya menjadi tersedia
            if ($validated['status'] == 'nonaktif') {
                Kamar::where('id', $newKamarId)->update(['status' => 'kosong']);
            }
            // Jika tenant yang sebelumnya non-aktif menjadi aktif kembali di kamar yg sama
            else if ($validated['status'] == 'aktif' && $tenant->wasChanged('status')) {
                Kamar::where('id', $newKamarId)->update(['status' => 'dihuni']);
            }

            DB::commit();

            return redirect()->route('tenants.index')->with('success', 'Data penyewa berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui penyewa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        try {
            DB::beginTransaction();

            $kamarId = $tenant->kamar_id;

            // Hapus tenant
            $tenant->delete();
            
            // Update status kamar yang ditinggalkan menjadi 'kosong'
            $kamar = Kamar::find($kamarId);

            // Pastikan tidak ada tenant aktif lain di kamar itu sebelum mengubah statusnya
            $isRoomStillOccupied = Tenant::where('kamar_id', $kamarId)->where('status', 'aktif')->exists();

            if ($kamar && !$isRoomStillOccupied) {
                $kamar->status = 'kosong';
                $kamar->save();
            }

            DB::commit();

            return redirect()->route('tenants.index')->with('success', 'Penyewa berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus penyewa: ' . $e->getMessage());
            return redirect()->route('tenants.index')->with('error', 'Gagal menghapus penyewa.');
        }
    }

    /**
     * Get tenant details for modal view.
     */
    public function getDetails(Tenant $tenant)
    {
        $tenant->load('kamar');
        return response()->json($tenant);
    }
}
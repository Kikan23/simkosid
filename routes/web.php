<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;
use App\Exports\LaporanOccupancyExport;
use App\Exports\LaporanMaintenanceExport;
use App\Http\Controllers\PengeluaranController;

Route::get('/', function () {
    return view('layouts.kaiadmin');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return view('layouts.kaiadmin');
    }
    return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
})->name('login.post');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    Auth::login($user);
    return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang di dashboard.');
})->name('register.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login')->with('message', 'Berhasil logout!');
})->name('logout');

Route::get('/dashboard', function () {
    return view('layouts.kaiadmin');
})->name('dashboard');

Route::get('/tenants', function () {
    $tenants = \App\Models\Tenant::all();
    return view('tenants.index', compact('tenants'));
})->name('tenants.index');

Route::get('/tenants/create', function () {
    $availableRooms = \App\Models\Kamar::where('status', 'kosong')->get();
    return view('tenants.create', compact('availableRooms'));
})->name('tenants.create');

Route::post('/tenants', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'nama_penyewa' => 'required|string|max:255',
        'nomor_ktp' => 'required|string|max:50',
        'telepon' => 'required|string|max:20',
        'email' => 'nullable|email|max:255',
        'kamar_id' => 'required|exists:kamars,id',
        'tanggal_masuk' => 'required|date',
        'tanggal_keluar' => 'nullable|date',
        // tambahkan field lain sesuai kebutuhan
    ]);
    \App\Models\Tenant::create($validated);
    // Update status kamar menjadi 'dihuni'
    $kamar = \App\Models\Kamar::find($validated['kamar_id']);
    if ($kamar) {
        $kamar->status = 'dihuni';
        $kamar->save();
    }
    return redirect()->route('tenants.index')->with('success', 'Tenant berhasil ditambahkan!');
})->name('tenants.store');

Route::get('/tenants/{id}/edit', function ($id) {
    $tenant = \App\Models\Tenant::findOrFail($id);
    return view('tenants.edit', compact('tenant'));
})->name('tenants.edit');

Route::delete('/tenants/{id}', function ($id) {
    $tenant = \App\Models\Tenant::findOrFail($id);
    $tenant->delete();
    return redirect()->route('tenants.index')->with('success', 'Tenant berhasil dihapus.');
})->name('tenants.destroy');

Route::get('/kamar', function () {
    $kamars = \App\Models\Kamar::all();
    return view('kamar.index', compact('kamars'));
})->name('kamar.index');

Route::get('/kamar/create', function () {
    $tipeKamarData = [
        'Standar' => [
            'fasilitas' => 'Kipas angin, kamar mandi luar, Wi-Fi biasa, kasur sederhana',
            'tarif_bulanan' => 500000
        ],
        'Premium' => [
            'fasilitas' => 'AC, kamar mandi dalam, Wi-Fi cepat, lemari, meja belajar, kasur tebal',
            'tarif_bulanan' => 1000000
        ],
        'VIP' => [
            'fasilitas' => 'Semua fasilitas premium + kulkas mini, smart TV, luas lebih besar, parkiran, layanan laundry atau bersih-bersih',
            'tarif_bulanan' => 2000000
        ],
    ];
    return view('kamar.create', compact('tipeKamarData'));
})->name('kamar.create');

Route::post('/kamar', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'nomor_kamar' => 'required|string|max:50|unique:kamars,nomor_kamar',
        'tipe_kamar' => 'required|in:Standar,Premium,VIP',
        'tarif_bulanan' => 'required|numeric|min:0',
        'status' => 'required|in:kosong,dihuni,maintenance',
        'fasilitas' => 'required|string',
    ]);
    \App\Models\Kamar::create($validated);
    return redirect()->route('kamar.index')->with('success', 'Kamar berhasil ditambahkan!');
})->name('kamar.store');

Route::get('/kamar/{id}/edit', function ($id) {
    $kamar = \App\Models\Kamar::findOrFail($id);
    return view('kamar.edit', compact('kamar'));
})->name('kamar.edit');

Route::delete('/kamar/{id}', function ($id) {
    $kamar = \App\Models\Kamar::findOrFail($id);
    $kamar->delete();
    return redirect()->route('kamar.index')->with('success', 'Kamar berhasil dihapus.');
})->name('kamar.destroy');

Route::get('/inventaris', function () {
    $inventaris = \App\Models\Inventaris::all();
    return view('inventaris.index', compact('inventaris'));
})->name('inventaris.index');

Route::get('/inventaris/create', function () {
    return view('inventaris.create');
})->name('inventaris.create');

Route::post('/inventaris', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'nama_barang' => 'required|string|max:255',
        'lokasi' => 'required|string|max:255',
        'status' => 'required|in:baik,rusak,hilang',
        'catatan' => 'nullable|string',
        'foto' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('foto')) {
        $validated['foto'] = $request->file('foto')->store('inventaris', 'public');
    }

    \App\Models\Inventaris::create($validated);
    return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil ditambahkan!');
})->name('inventaris.store');

Route::get('/inventaris/{id}/edit', function ($id) {
    $inventaris = \App\Models\Inventaris::findOrFail($id);
    return view('inventaris.edit', compact('inventaris'));
})->name('inventaris.edit');

Route::delete('/inventaris/{id}', function ($id) {
    $inventaris = \App\Models\Inventaris::findOrFail($id);
    // Hapus file foto jika ada
    if ($inventaris->foto) {
        Storage::disk('public')->delete($inventaris->foto);
    }
    $inventaris->delete();
    return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
})->name('inventaris.destroy');

Route::get('/pembayaran', function () {
    $pembayarans = \App\Models\Pembayaran::with('tenant')->paginate(10);
    return view('pembayaran.index', compact('pembayarans'));
})->name('pembayaran.index');

Route::get('/pembayaran/create', function () {
    $tenants = \App\Models\Tenant::with('kamar')->get();
    return view('pembayaran.create', compact('tenants'));
})->name('pembayaran.create');

Route::post('/pembayaran', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'tenant_id' => 'required|exists:tenants,id',
        'bulan_tahun' => 'required|date_format:Y-m',
        'jumlah_pembayaran' => 'required|numeric|min:0',
        'tanggal_pembayaran' => 'required|date',
        'status_pembayaran' => 'required|in:lunas,belum_bayar,terlambat',
        'metode_pembayaran' => 'required|in:cash,transfer,e_wallet,lainnya',
        'catatan' => 'nullable|string',
    ]);

    $tenant = \App\Models\Tenant::with('kamar')->findOrFail($validated['tenant_id']);
    $validated['no_kamar'] = $tenant->kamar->nomor_kamar ?? '-';

    \App\Models\Pembayaran::create($validated);
    return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil disimpan!');
})->name('pembayaran.store');

Route::get('/pembayaran/{id}', function ($id) {
    $pembayaran = \App\Models\Pembayaran::with('tenant')->findOrFail($id);
    return view('pembayaran.show', compact('pembayaran'));
})->name('pembayaran.show');

Route::get('/pembayaran/{id}/edit', function ($id) {
    $pembayaran = \App\Models\Pembayaran::with('tenant')->findOrFail($id);
    return view('pembayaran.edit', compact('pembayaran'));
})->name('pembayaran.edit');

Route::delete('/pembayaran/{id}', function ($id) {
    $pembayaran = \App\Models\Pembayaran::findOrFail($id);
    $pembayaran->delete();
    return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
})->name('pembayaran.destroy');

Route::get('/laporan', function () {
    return view('laporan.index');
})->name('laporan.index');

Route::get('/laporan/builder', function (\Illuminate\Http\Request $request) {
    $template = $request->query('template');
    return view('laporan.builder', compact('template'));
})->name('laporan.builder');

Route::post('/laporan/generate', function () {
    // Di sini bisa ditambahkan logic generate laporan
    return redirect()->route('laporan.index')->with('message', 'Laporan berhasil digenerate!');
})->name('laporan.generate');

Route::get('/keluhan', function () {
    $keluhans = \App\Models\Keluhan::paginate(10);
    return view('keluhan.index', compact('keluhans'));
})->name('keluhan.index');

Route::get('/keluhan/create', function () {
    $tenants = \App\Models\Tenant::with('kamar')->get();
    return view('keluhan.create', compact('tenants'));
})->name('keluhan.create');

Route::post('/keluhan', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'tenant_id' => 'required|exists:tenants,id',
        'tanggal_keluhan' => 'required|date',
        'status_keluhan' => 'required|in:pending,diproses,selesai',
        'deskripsi_keluhan' => 'required|string',
    ]);

    // Get tenant for additional info
    $tenant = \App\Models\Tenant::with('kamar')->findOrFail($validated['tenant_id']);
    $keluhanData = [
        'tenant_id' => $tenant->id,
        'nama_penghuni' => $tenant->nama_penyewa,
        'no_kamar' => $tenant->kamar ? $tenant->kamar->nomor_kamar : null,
        'tanggal_keluhan' => $validated['tanggal_keluhan'],
        'status_keluhan' => $validated['status_keluhan'],
        'deskripsi_keluhan' => $validated['deskripsi_keluhan'],
    ];
    
    
    
    \App\Models\Keluhan::create($keluhanData);
    return redirect()->route('keluhan.index')->with('success', 'Keluhan berhasil disimpan.');
})->name('keluhan.store');

Route::get('/keluhan/{id}', function ($id) {
    $keluhan = \App\Models\Keluhan::findOrFail($id);
    return view('keluhan.show', compact('keluhan'));
})->name('keluhan.show');

Route::get('/keluhan/{id}/edit', function ($id) {
    $keluhan = \App\Models\Keluhan::findOrFail($id);
    return view('keluhan.edit', compact('keluhan'));
})->name('keluhan.edit');

Route::delete('/keluhan/{id}', function ($id) {
    $keluhan = \App\Models\Keluhan::findOrFail($id);
    $keluhan->delete();
    return redirect()->route('keluhan.index')->with('success', 'Keluhan berhasil dihapus.');
})->name('keluhan.destroy');

Route::get('/pengeluaran', function () {
    $analytics = [
        'total_bulan_ini' => 0,
        'total_bulan_lalu' => 0,
        'total_tahun_ini' => 0,
        'total_tahun_lalu' => 0,
        'persentase_perubahan' => 0, // Ganti dengan data asli jika diperlukan
        'pengeluaran_kategori' => collect(), // Ganti dengan data asli jika diperlukan
    ];
    $bulanList = [
        ['value' => '01', 'text' => 'Januari'],
        ['value' => '02', 'text' => 'Februari'],
        ['value' => '03', 'text' => 'Maret'],
        ['value' => '04', 'text' => 'April'],
        ['value' => '05', 'text' => 'Mei'],
        ['value' => '06', 'text' => 'Juni'],
        ['value' => '07', 'text' => 'Juli'],
        ['value' => '08', 'text' => 'Agustus'],
        ['value' => '09', 'text' => 'September'],
        ['value' => '10', 'text' => 'Oktober'],
        ['value' => '11', 'text' => 'November'],
        ['value' => '12', 'text' => 'Desember'],
    ];
    $pengeluarans = \App\Models\Pengeluaran::orderByDesc('tanggal_pengeluaran')->paginate(10);
    return view('pengeluaran.index', compact('analytics', 'bulanList', 'pengeluarans'));
})->name('pengeluaran.index');

Route::get('/pengeluaran/analytics', function () {
    // Placeholder for analytics endpoint. Replace with real logic if needed.
    return response()->json(['message' => 'Analytics endpoint placeholder']);
})->name('pengeluaran.analytics');

Route::get('/pengeluaran/create', function () {
    $kategoris = [
        'operasional' => ['name' => 'Operasional'],
        'perawatan' => ['name' => 'Perawatan'],
        'lainnya' => ['name' => 'Lainnya'],
    ]; // Ganti dengan data asli jika diperlukan
    return view('pengeluaran.create', compact('kategoris'));
})->name('pengeluaran.create');

Route::post('/pengeluaran', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'tanggal_pengeluaran' => 'required|date',
        'kategori' => 'required|string',
        'jenis_pengeluaran' => 'required|string',
        'nominal' => 'required|numeric|min:0',
        'keterangan_detail' => 'required|string',
        'catatan_tambahan' => 'nullable|string',
        'bukti_pembayaran' => 'nullable|image|max:2048',
        'is_recurring' => 'nullable|boolean',
        'status_approval' => 'required|in:pending,approved,rejected',
    ]);

    $data = $validated;
    $data['is_recurring'] = $request->has('is_recurring');

    // Handle file upload
    if ($request->hasFile('bukti_pembayaran')) {
        $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('bukti_pengeluaran', 'public');
    }

    
    \App\Models\Pengeluaran::create($data);
    return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil disimpan.');
})->name('pengeluaran.store');

Route::get('/pegawai', function () {
    $pegawais = \App\Models\Pegawai::all();
    return view('pegawai.index', compact('pegawais'));
})->name('pegawai.index');

Route::get('/pegawai/create', function () {
    return view('pegawai.create');
})->name('pegawai.create');

Route::post('/pegawai', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'nama_pegawai' => 'required|string|max:255',
        'jobdesk' => 'required|string',
        'no_telepon' => 'required|string|max:20',
        'jadwal_kerja' => 'required|string',
        'status_pegawai' => 'required|in:aktif,tidak_aktif,cuti',
    ]);
    
    \App\Models\Pegawai::create($validated);
    return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil disimpan.');
})->name('pegawai.store');

Route::get('/pegawai/{id}', function ($id) {
    $pegawai = \App\Models\Pegawai::findOrFail($id);
    return view('pegawai.show', compact('pegawai'));
})->name('pegawai.show');

Route::get('/pegawai/{id}/edit', function ($id) {
    $pegawai = \App\Models\Pegawai::findOrFail($id);
    return view('pegawai.edit', compact('pegawai'));
})->name('pegawai.edit');

Route::delete('/pegawai/{id}', function ($id) {
    $pegawai = \App\Models\Pegawai::findOrFail($id);
    $pegawai->delete();
    return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
})->name('pegawai.destroy');

Route::put('/pegawai/{id}', function (\Illuminate\Http\Request $request, $id) {
    $pegawai = \App\Models\Pegawai::findOrFail($id);
    $validated = $request->validate([
        'nama_pegawai' => 'required|string|max:255',
        'jobdesk' => 'required|string',
        'no_telepon' => 'required|string|max:20',
        'jadwal_kerja' => 'required|string',
        'status_pegawai' => 'required|in:aktif,tidak_aktif,cuti',
    ]);
    $pegawai->update($validated);
    return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diupdate.');
})->name('pegawai.update');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/laporan/maintenance', function () {
    $periodeAwal = date('Y-m-01');
    $periodeAkhir = date('Y-m-t');
    $inventaris = \App\Models\Inventaris::all();
    $statusAset = $inventaris->map(function($item) {
        return [
            'nama_barang' => $item->nama_barang,
            'letak' => $item->letak ?? '-',
            'tanggal_beli' => $item->tanggal_beli ?? '-',
            'status' => $item->status,
            'keterangan' => $item->keterangan ?? '-',
        ];
    })->toArray();
    $dataMaintenance = [
        'status_aset' => $statusAset,
        'jadwal' => [],
        'biaya' => [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
            'data' => [200000, 150000, 300000, 250000],
        ],
    ];
    return view('laporan.maintenance', compact('periodeAwal', 'periodeAkhir', 'dataMaintenance'));
})->name('laporan.maintenance');

Route::get('/laporan/keuangan', function () {
    $periodeAwal = date('Y-m-01');
    $periodeAkhir = date('Y-m-t');
    $dataKeuangan = [
        'transactions' => [],
        'revenue' => [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
            'data' => [1000000, 1200000, 900000, 1500000],
        ],
        'expenses' => [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
            'data' => [500000, 700000, 400000, 800000],
        ],
    ];
    return view('laporan.keuangan', compact('periodeAwal', 'periodeAkhir', 'dataKeuangan'));
})->name('laporan.keuangan');

Route::get('/laporan/keuangan/export', function () {
    return Excel::download(new LaporanKeuanganExport, 'laporan_keuangan.xlsx');
})->name('laporan.keuangan.export');

Route::get('/laporan/occupancy/export', function () {
    return Excel::download(new LaporanOccupancyExport, 'laporan_occupancy.xlsx');
})->name('laporan.occupancy.export');

Route::get('/laporan/occupancy', function () {
    $periodeAwal = date('Y-m-01');
    $periodeAkhir = date('Y-m-t');
    $totalKamar = \App\Models\Kamar::count();
    $kamarTerisi = \App\Models\Kamar::where('status', 'dihuni')->count();
    $kamarKosong = \App\Models\Kamar::where('status', 'kosong')->count();
    $persentaseTerisi = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;
    $dataOccupancy = [
        'stats' => [
            'total_kamar' => $totalKamar,
            'terisi' => $kamarTerisi,
            'kosong' => $kamarKosong,
            'persentase' => $persentaseTerisi,
        ],
        'detail' => [
            ['bulan' => 'Januari', 'terisi' => 20, 'kosong' => 5],
            ['bulan' => 'Februari', 'terisi' => 18, 'kosong' => 7],
            ['bulan' => 'Maret', 'terisi' => 22, 'kosong' => 3],
            ['bulan' => 'April', 'terisi' => 19, 'kosong' => 6],
        ],
        'chart' => [
            'labels' => ['Januari', 'Februari', 'Maret', 'April'],
            'data' => [80, 72, 88, 76],
        ],
    ];
    return view('laporan.occupancy', compact('periodeAwal', 'periodeAkhir', 'dataOccupancy'));
})->name('laporan.occupancy');

Route::get('/laporan/maintenance/export', function () {
    return Excel::download(new LaporanMaintenanceExport, 'laporan_maintenance.xlsx');
})->name('laporan.maintenance.export');

Route::get('/pengeluaran/{id}', function ($id) {
    $pengeluaran = \App\Models\Pengeluaran::findOrFail($id);
    return view('pengeluaran.show', compact('pengeluaran'));
})->name('pengeluaran.show');

Route::get('/pengeluaran/{id}/edit', function ($id) {
    $pengeluaran = \App\Models\Pengeluaran::findOrFail($id);
    $kategoris = [
        'makanan' => ['name' => 'Makanan & Minuman'],
        'transportasi' => ['name' => 'Transportasi'],
        'kesehatan' => ['name' => 'Kesehatan'],
        'pendidikan' => ['name' => 'Pendidikan'],
        'hiburan' => ['name' => 'Hiburan'],
        'belanja' => ['name' => 'Belanja'],
        'tagihan' => ['name' => 'Tagihan'],
        'investasi' => ['name' => 'Investasi'],
        'asuransi' => ['name' => 'Asuransi'],
        'lainnya' => ['name' => 'Lainnya'],
    ];
    return view('pengeluaran.edit', compact('pengeluaran', 'kategoris'));
})->name('pengeluaran.edit');

Route::delete('/pengeluaran/{id}', function ($id) {
    $pengeluaran = \App\Models\Pengeluaran::findOrFail($id);
    $pengeluaran->delete();
    return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
})->name('pengeluaran.destroy');

Route::get('pengeluaran/{id}/modal', [\App\Http\Controllers\PengeluaranController::class, 'showModal'])->name('pengeluaran.showModal');

Route::resource('pengeluaran', \App\Http\Controllers\PengeluaranController::class);

Route::put('pengeluaran/{pengeluaran}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');

Route::get('pegawai/{id}/modal', [\App\Http\Controllers\PegawaiController::class, 'showModal'])->name('pegawai.showModal');
Route::resource('pegawai', \App\Http\Controllers\PegawaiController::class);

Route::post('kamar/{id}/update-status', [\App\Http\Controllers\KamarController::class, 'updateStatus'])->name('kamar.update-status');
// Resource route untuk kamar - menggunakan KamarController
Route::resource('kamar', \App\Http\Controllers\KamarController::class);


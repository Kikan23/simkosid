<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Tenant;
use App\Models\Kamar;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LaporanController extends Controller
{
    /**
     * Menampilkan galeri template laporan.
     */
    public function index()
    {
        return view('laporan.index');
    }

    /**
     * Menampilkan halaman pembangun laporan berdasarkan template.
     */
    public function builder(Request $request)
    {
        $template = $request->get('template');
        if (!in_array($template, ['keuangan', 'okupansi', 'tunggakan'])) {
            abort(404, 'Template laporan tidak ditemukan.');
        }

        return view('laporan.builder', compact('template'));
    }

    /**
     * Menghasilkan dan menampilkan laporan berdasarkan parameter.
     */
    public function generate(Request $request)
    {
        // Validasi dasar
        $validated = $request->validate([
            'template' => 'required|in:keuangan,okupansi,tunggakan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $template = $validated['template'];
        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalSelesai = Carbon::parse($validated['tanggal_selesai']);
        
        $data = [];

        switch ($template) {
            case 'keuangan':
                $data = $this->generateLaporanKeuangan($tanggalMulai, $tanggalSelesai);
                break;
            case 'okupansi':
                $data = $this->generateLaporanOkupansi($tanggalMulai, $tanggalSelesai);
                break;
            case 'tunggakan':
                $data = $this->generateLaporanTunggakan($tanggalMulai, $tanggalSelesai, $request->input('status_tunggakan', []));
                break;
        }

        return view('laporan.show', array_merge($data, [
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai
        ]));
    }

    private function generateLaporanKeuangan(Carbon $tanggalMulai, Carbon $tanggalSelesai)
    {
        $pemasukan = Pembayaran::whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalSelesai])->get();
        $pengeluaran = Pengeluaran::whereBetween('tanggal_pengeluaran', [$tanggalMulai, $tanggalSelesai])->get();

        $totalPemasukan = $pemasukan->sum('jumlah_pembayaran');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $keuntunganBersih = $totalPemasukan - $totalPengeluaran;

        // Executive Summary
        $summary = [
            ['title' => 'Total Pemasukan', 'value' => 'Rp ' . number_format($totalPemasukan, 0, ',', '.'), 'class' => 'bg-success'],
            ['title' => 'Total Pengeluaran', 'value' => 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'), 'class' => 'bg-danger'],
            ['title' => 'Keuntungan Bersih', 'value' => 'Rp ' . number_format($keuntunganBersih, 0, ',', '.'), 'class' => 'bg-info'],
        ];

        // Data Table
        $tableHeaders = ['Tanggal', 'Kategori', 'Deskripsi', 'Pemasukan', 'Pengeluaran'];
        $tableRows = [];
        foreach ($pemasukan as $item) {
            $tableRows[] = [
                $item->tanggal_pembayaran->format('d M Y'),
                'Pembayaran',
                'Pembayaran dari ' . $item->tenant->nama . ' (Kamar ' . $item->no_kamar . ')',
                '<span class="text-success">Rp ' . number_format($item->jumlah_pembayaran, 0, ',', '.') . '</span>',
                '-'
            ];
        }
        foreach ($pengeluaran as $item) {
            $tableRows[] = [
                $item->tanggal_pengeluaran->format('d M Y'),
                ucfirst($item->kategori),
                $item->deskripsi,
                '-',
                '<span class="text-danger">Rp ' . number_format($item->jumlah, 0, ',', '.') . '</span>'
            ];
        }
        // Sort by date
        usort($tableRows, function($a, $b) {
            return strtotime($a[0]) - strtotime($b[0]);
        });

        // Chart Data
        $period = CarbonPeriod::create($tanggalMulai, $tanggalSelesai);
        $labels = [];
        $pemasukanData = [];
        $pengeluaranData = [];

        foreach ($period as $date) {
            $labels[] = $date->format('d M');
            $pemasukanData[] = $pemasukan->where('tanggal_pembayaran', $date)->sum('jumlah_pembayaran');
            $pengeluaranData[] = $pengeluaran->where('tanggal_pengeluaran', $date)->sum('jumlah');
        }

        $chart = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Pemasukan',
                        'data' => $pemasukanData,
                        'backgroundColor' => 'rgba(40, 167, 69, 0.6)',
                        'borderColor' => 'rgba(40, 167, 69, 1)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Pengeluaran',
                        'data' => $pengeluaranData,
                        'backgroundColor' => 'rgba(220, 53, 69, 0.6)',
                        'borderColor' => 'rgba(220, 53, 69, 1)',
                        'borderWidth' => 1
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) { return context.dataset.label + ": Rp " + new Intl.NumberFormat().format(context.raw); }'
                        ]
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'display' => false
                        ],
                        'grid' => [
                            'display' => false
                        ]
                    ],
                    'x' => [
                         'grid' => [
                            'display' => false
                        ]
                    ]
                ]
            ]
        ];

        return [
            'title' => 'Laporan Keuangan',
            'summary' => $summary,
            'table' => ['headers' => $tableHeaders, 'rows' => $tableRows],
            'chart' => $chart,
        ];
    }

    private function generateLaporanOkupansi(Carbon $tanggalMulai, Carbon $tanggalSelesai)
    {
        $semuaKamar = Kamar::all();
        $totalKamar = $semuaKamar->count();
        
        $kamarTerisi = Kamar::where('status', 'dihuni')->count();
        $kamarKosong = $totalKamar - $kamarTerisi;
        $tingkatHunian = ($totalKamar > 0) ? ($kamarTerisi / $totalKamar) * 100 : 0;

        // Executive Summary
        $summary = [
            ['title' => 'Tingkat Hunian', 'value' => number_format($tingkatHunian, 1) . '%', 'class' => 'bg-info'],
            ['title' => 'Kamar Terisi', 'value' => $kamarTerisi, 'class' => 'bg-success'],
            ['title' => 'Kamar Kosong', 'value' => $kamarKosong, 'class' => 'bg-warning'],
        ];

        // Data Table
        $tableHeaders = ['No. Kamar', 'Tipe', 'Status', 'Dihuni oleh', 'Sejak'];
        $tableRows = [];
        foreach ($semuaKamar as $kamar) {
            $penghuni = $kamar->penghuniAktif();
            $tableRows[] = [
                $kamar->no_kamar,
                ucfirst($kamar->tipe),
                '<span class="badge ' . ($kamar->status == 'dihuni' ? 'badge-success' : 'badge-secondary') . '">' . ucfirst($kamar->status) . '</span>',
                $penghuni ? $penghuni->nama : '-',
                $penghuni ? $penghuni->tanggal_masuk->format('d M Y') : '-'
            ];
        }

        // Chart Data (Pie Chart)
        $chart = [
            'type' => 'pie',
            'data' => [
                'labels' => ['Terisi', 'Kosong'],
                'datasets' => [[
                    'data' => [$kamarTerisi, $kamarKosong],
                    'backgroundColor' => ['rgba(40, 167, 69, 0.8)', 'rgba(255, 193, 7, 0.8)'],
                    'borderColor' => '#fff'
                ]]
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                 'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                ],
            ]
        ];

        return [
            'title' => 'Laporan Okupansi',
            'summary' => $summary,
            'table' => ['headers' => $tableHeaders, 'rows' => $tableRows],
            'chart' => $chart,
        ];
    }

    private function generateLaporanTunggakan(Carbon $tanggalMulai, Carbon $tanggalSelesai, array $status)
    {
        if (empty($status)) {
            $status = ['belum_bayar', 'terlambat'];
        }

        $tunggakan = Pembayaran::whereIn('status_pembayaran', $status)
                        ->whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalSelesai])
                        ->with('tenant')
                        ->get();

        $totalTunggakan = $tunggakan->sum('jumlah_pembayaran');
        $jumlahPenunggak = $tunggakan->unique('tenant_id')->count();

        // Executive Summary
        $summary = [
            ['title' => 'Total Tunggakan', 'value' => 'Rp ' . number_format($totalTunggakan, 0, ',', '.'), 'class' => 'bg-danger'],
            ['title' => 'Jumlah Penunggak', 'value' => $jumlahPenunggak . ' Orang', 'class' => 'bg-warning'],
            ['title' => 'Total Transaksi Tertunggak', 'value' => $tunggakan->count(), 'class' => 'bg-info'],
        ];

        // Data Table
        $tableHeaders = ['Penghuni', 'No. Kamar', 'Periode', 'Jumlah', 'Status', 'Tgl. Seharusnya'];
        $tableRows = [];
        foreach ($tunggakan as $item) {
            $tableRows[] = [
                $item->tenant->nama,
                $item->no_kamar,
                $item->formatted_bulan_tahun,
                'Rp ' . number_format($item->jumlah_pembayaran, 0, ',', '.'),
                '<span class="badge ' . ($item->status_pembayaran == 'terlambat' ? 'badge-danger' : 'badge-warning') . '">' . ucfirst(str_replace('_', ' ', $item->status_pembayaran)) . '</span>',
                $item->tanggal_pembayaran->format('d M Y')
            ];
        }

        // Chart Data (kita gunakan data kosong karena tidak relevan)
        $chart = [
            'type' => 'bar',
            'data' => [
                'labels' => ['Tidak ada data untuk ditampilkan'],
                'datasets' => []
            ],
            'options' => ['responsive' => true, 'maintainAspectRatio' => false]
        ];

        return [
            'title' => 'Laporan Tunggakan',
            'summary' => $summary,
            'table' => ['headers' => $tableHeaders, 'rows' => $tableRows],
            'chart' => $chart,
        ];
    }
}
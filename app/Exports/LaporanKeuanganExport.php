<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class LaporanKeuanganExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            ['Tanggal', 'Deskripsi', 'Jumlah', 'Tipe'],
            ['2024-06-01', 'Pembayaran Sewa Kamar A101', 1500000, 'pemasukan'],
            ['2024-06-02', 'Pembelian Alat Kebersihan', 200000, 'pengeluaran'],
            ['2024-06-03', 'Pembayaran Sewa Kamar B202', 1700000, 'pemasukan'],
        ]);
    }
}

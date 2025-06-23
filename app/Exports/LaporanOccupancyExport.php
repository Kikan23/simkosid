<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class LaporanOccupancyExport implements FromCollection
{
    public function collection()
    {
        return collect([
            ['Bulan', 'Kamar Terisi', 'Kamar Kosong', 'Persentase', 'Turnover'],
            ['Januari', 20, 5, '80%', 3],
            ['Februari', 18, 7, '72%', 2],
            ['Maret', 22, 3, '88%', 4],
            ['April', 19, 6, '76%', 2],
        ]);
    }
} 
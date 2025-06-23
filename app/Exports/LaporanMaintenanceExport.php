<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use App\Models\Inventaris;

class LaporanMaintenanceExport implements FromCollection
{
    public function collection()
    {
        $inventaris = Inventaris::all();
        $rows = [
            ['Nama Barang', 'Letak', 'Tanggal Beli', 'Status', 'Keterangan'],
        ];
        foreach ($inventaris as $item) {
            $rows[] = [
                $item->nama_barang,
                $item->letak ?? '-',
                $item->tanggal_beli ?? '-',
                $item->status,
                $item->keterangan ?? '-',
            ];
        }
        return collect($rows);
    }
} 
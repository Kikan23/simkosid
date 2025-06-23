<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventaris;

class InventarisSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_barang' => 'AC Split 1 PK',
                'letak' => 'Kamar 101',
                'tanggal_beli' => '2023-01-15',
                'status' => 'baik',
                'keterangan' => 'AC baru dipasang, kondisi sangat baik'
            ],
            [
                'nama_barang' => 'Kasur Single',
                'letak' => 'Kamar 102',
                'tanggal_beli' => '2023-02-10',
                'status' => 'baik',
                'keterangan' => 'Kasur spring bed ukuran 90x200'
            ],
            [
                'nama_barang' => 'Lemari 2 Pintu',
                'letak' => 'Kamar 103',
                'tanggal_beli' => '2023-01-20',
                'status' => 'rusak',
                'keterangan' => 'Pintu kiri seret, perlu diperbaiki'
            ]
        ];

        foreach ($data as $item) {
            Inventaris::create($item);
        }
    }
}
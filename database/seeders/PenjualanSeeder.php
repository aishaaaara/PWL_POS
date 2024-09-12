<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Transaksi 1
            [
                'penjualan_id' => 1,
                'user_id' => 3, // Kasir
                'pembeli' => 'Andi',
                'penjualan_kode' => 'PNJ001',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 2
            [
                'penjualan_id' => 2,
                'user_id' => 3, // Kasir
                'pembeli' => 'Budi',
                'penjualan_kode' => 'PNJ002',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 3
            [
                'penjualan_id' => 3,
                'user_id' => 3, // Kasir
                'pembeli' => 'Citra',
                'penjualan_kode' => 'PNJ003',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 4
            [
                'penjualan_id' => 4,
                'user_id' => 3, // Kasir
                'pembeli' => 'Dedi',
                'penjualan_kode' => 'PNJ004',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 5
            [
                'penjualan_id' => 5,
                'user_id' => 3, // Kasir
                'pembeli' => 'Eka',
                'penjualan_kode' => 'PNJ005',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 6
            [
                'penjualan_id' => 6,
                'user_id' => 3, // Kasir
                'pembeli' => 'Fajar',
                'penjualan_kode' => 'PNJ006',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 7
            [
                'penjualan_id' => 7,
                'user_id' => 3, // Kasir
                'pembeli' => 'Gita',
                'penjualan_kode' => 'PNJ007',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 8
            [
                'penjualan_id' => 8,
                'user_id' => 3, // Kasir
                'pembeli' => 'Hadi',
                'penjualan_kode' => 'PNJ008',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 9
            [
                'penjualan_id' => 9,
                'user_id' => 3, // Kasir
                'pembeli' => 'Ika',
                'penjualan_kode' => 'PNJ009',
                'penjualan_tanggal' => now(),
            ],
            // Transaksi 10
            [
                'penjualan_id' => 10,
                'user_id' => 3, // Kasir
                'pembeli' => 'Joni',
                'penjualan_kode' => 'PNJ010',
                'penjualan_tanggal' => now(),
            ],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}

<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Detail untuk Transaksi 1 (PNJ001)
            [
                'penjualan_id' => 1,
                'barang_id' => 1, // Barang 1
                'harga' => 700000, // Harga jual barang 1
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 1,
                'barang_id' => 2, // Barang 2
                'harga' => 400000, 
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 1,
                'barang_id' => 3, // Barang 3
                'harga' => 1000000, 
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 2 (PNJ002)
            [
                'penjualan_id' => 2,
                'barang_id' => 4, // Barang 4
                'harga' => 450000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 2,
                'barang_id' => 5, // Barang 5
                'harga' => 350000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 2,
                'barang_id' => 6, // Barang 6
                'harga' => 1800000,
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 3 (PNJ003)
            [
                'penjualan_id' => 3,
                'barang_id' => 7, // Barang 7
                'harga' => 3000000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 3,
                'barang_id' => 8, // Barang 8
                'harga' => 2500000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 3,
                'barang_id' => 9, // Barang 9
                'harga' => 3500000,
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 4 (PNJ004)
            [
                'penjualan_id' => 4,
                'barang_id' => 10, // Barang 10
                'harga' => 700000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 4,
                'barang_id' => 11, // Barang 11
                'harga' => 150000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 4,
                'barang_id' => 12, // Barang 12
                'harga' => 300000,
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 5 (PNJ005)
            [
                'penjualan_id' => 5,
                'barang_id' => 13, // Barang 13
                'harga' => 750000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 5,
                'barang_id' => 14, // Barang 14
                'harga' => 600000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 5,
                'barang_id' => 15, // Barang 15
                'harga' => 500000,
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 6 (PNJ006)
            [
                'penjualan_id' => 6,
                'barang_id' => 1, // Barang 1
                'harga' => 700000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 6,
                'barang_id' => 2, // Barang 2
                'harga' => 400000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 6,
                'barang_id' => 3, // Barang 3
                'harga' => 1000000,
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 7 (PNJ007)
            [
                'penjualan_id' => 7,
                'barang_id' => 4, // Barang 4
                'harga' => 450000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 7,
                'barang_id' => 5, // Barang 5
                'harga' => 350000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 7,
                'barang_id' => 6, // Barang 6
                'harga' => 1800000,
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 8 (PNJ008)
            [
                'penjualan_id' => 8,
                'barang_id' => 7, // Barang 7
                'harga' => 3000000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 8,
                'barang_id' => 8, // Barang 8
                'harga' => 2500000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 8,
                'barang_id' => 9, // Barang 9
                'harga' => 3500000,
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 9 (PNJ009)
            [
                'penjualan_id' => 9,
                'barang_id' => 10, // Barang 10
                'harga' => 700000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 9,
                'barang_id' => 11, // Barang 11
                'harga' => 150000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 9,
                'barang_id' => 12, // Barang 12
                'harga' => 300000,
                'jumlah' => 1,
            ],

            // Detail untuk Transaksi 10 (PNJ010)
            [
                'penjualan_id' => 10,
                'barang_id' => 13, // Barang 13
                'harga' => 750000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 10,
                'barang_id' => 14, // Barang 14
                'harga' => 600000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 10,
                'barang_id' => 15, // Barang 15
                'harga' => 500000,
                'jumlah' => 1,
            ],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Barang dari Supplier 1 (PT Sumber Jaya)
            [
                'barang_id' => 1,
                'kategori_id' => 1, // Asumsikan kategori id 1 adalah Furniture
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Meja Kayu Jati',
                'harga_beli' => 500000,
                'harga_jual' => 700000,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Kursi Tamu Minimalis',
                'harga_beli' => 250000,
                'harga_jual' => 400000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 1,
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Lemari Pakaian',
                'harga_beli' => 800000,
                'harga_jual' => 1000000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 1,
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Dipan Kayu',
                'harga_beli' => 300000,
                'harga_jual' => 450000,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 1,
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Rak Buku ',
                'harga_beli' => 200000,
                'harga_jual' => 350000,
            ],

            // Barang dari Supplier 2 (CV Elektronik Sejahtera)
            [
                'barang_id' => 6,
                'kategori_id' => 2, // Asumsikan kategori id 2 adalah Elektronik
                'barang_kode' => 'BRG006',
                'barang_nama' => 'Televisi LG',
                'harga_beli' => 1500000,
                'harga_jual' => 1800000,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 2,
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Kulkas Polytron',
                'harga_beli' => 2500000,
                'harga_jual' => 3000000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 2,
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Mesin Cuci Samsung',
                'harga_beli' => 2000000,
                'harga_jual' => 2500000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 2,
                'barang_kode' => 'BRG009',
                'barang_nama' => 'AC Samsung',
                'harga_beli' => 3000000,
                'harga_jual' => 3500000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 2,
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Hair Dryer Dyson',
                'harga_beli' => 500000,
                'harga_jual' => 700000,
            ],

            // Barang dari Supplier 3 (PT Sejahtera Negara)
            [
                'barang_id' => 11,
                'kategori_id' => 3, // Asumsikan kategori id 3 adalah Pakaian
                'barang_kode' => 'BRG011',
                'barang_nama' => 'Kemeja Pria',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
            ],
            [
                'barang_id' => 12,
                'kategori_id' => 3,
                'barang_kode' => 'BRG012',
                'barang_nama' => 'Celana Jeans',
                'harga_beli' => 200000,
                'harga_jual' => 300000,
            ],
            [
                'barang_id' => 13,
                'kategori_id' => 3,
                'barang_kode' => 'BRG013',
                'barang_nama' => 'Jaket Kulit',
                'harga_beli' => 500000,
                'harga_jual' => 750000,
            ],
            [
                'barang_id' => 14,
                'kategori_id' => 3,
                'barang_kode' => 'BRG014',
                'barang_nama' => 'Sepatu Sneakers',
                'harga_beli' => 400000,
                'harga_jual' => 600000,
            ],
            [
                'barang_id' => 15,
                'kategori_id' => 3,
                'barang_kode' => 'BRG015',
                'barang_nama' => 'Tas Kulit Pria',
                'harga_beli' => 300000,
                'harga_jual' => 500000,
            ],
        ];

        DB::table('m_barang')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'kategori_kode' => 'FRN001',
                'kategori_nama' => 'Furniture',
            ],
            [
                'kategori_id' => 2,
                'kategori_kode' => 'ELK002',
                'kategori_nama' => 'Elektronik',
            ],
            [
                'kategori_id' => 3,
                'kategori_kode' => 'PJK003',
                'kategori_nama' => 'Pakaian',
            ],
            [
                'kategori_id' => 4,
                'kategori_kode' => 'ATL004',
                'kategori_nama' => 'Alat Tulis',
            ],
            [
                'kategori_id' => 5,
                'kategori_kode' => 'MK005',
                'kategori_nama' => 'Makanan',
            ],
        ];

        DB::table('m_kategori')->insert($data);
    }
}

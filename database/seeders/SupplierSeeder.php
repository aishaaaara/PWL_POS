<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'PT Sumber Jaya',
                'supplier_alamat' => 'Jl. Merdeka No. 2, Sidaorjo',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'CV Elektronik Sejahtera',
                'supplier_alamat' => 'Jl. Bunga Pinang Merah, Malang',
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'PT Sejahtera Negara',
                'supplier_alamat' => 'Jl. Kenanga No. 10, Surabaya',
            ],
        ]; 
        
        DB::table('m_supplier')->insert($data);
    }
}
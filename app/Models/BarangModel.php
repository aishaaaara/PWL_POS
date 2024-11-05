<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    // Menentukan nama tabel yang digunakan
    protected $table = 'm_barang'; 
    
    // Menentukan primary key yang digunakan
    protected $primaryKey = 'barang_id'; 

    // Jika ingin mengizinkan mass assignment untuk field tertentu
    protected $fillable = [
        'kategori_id',
        'barang_kode',
        'barang_nama',
        'harga_beli',
        'harga_jual',
        'image'
    ];

    // Mendefinisikan relasi dengan Kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}

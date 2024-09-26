<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    // Menentukan nama tabel yang digunakan
    protected $table = 'm_kategori'; 
    
    // Menentukan primary key yang digunakan
    protected $primaryKey = 'kategori_id'; 

    // Jika ingin mengizinkan mass assignment untuk field tertentu
    protected $fillable = [
        'kategori_kode',
        'kategori_nama',
    ];
}
?>
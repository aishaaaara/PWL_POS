<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    // Menentukan nama tabel yang digunakan
    protected $table = 'm_level'; 
    
    // Menentukan primary key yang digunakan
    protected $primaryKey = 'level_id'; 
    
    // Jika primary key bukan integer, tambahkan
    // public $incrementing = false; 

    // Jika primary key adalah UUID atau bukan auto-increment, tambahkan
    // protected $keyType = 'string'; 

    // Jika ingin mengizinkan mass assignment untuk field tertentu
    protected $fillable = [
        'level_kode',
        'level_nama',
        // Tambahkan kolom lain yang ingin diisi secara massal
    ];
}
?>
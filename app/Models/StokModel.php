<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokModel extends Model
{
    use HasFactory;
    protected $table = 't_stok';
    protected $primaryKey = 'stok_id'; //primary key dari tabel yang digunakan
    protected $fillable = ['supplier_id', 'barang_id', 'user_id', 'stok_tanggal','stok_jumlah','updates_at'];


    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id');
    }

    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}

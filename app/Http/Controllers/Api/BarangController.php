<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\BarangModel; // Model yang sesuai untuk tabel m_barang
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }

public function store(Request $request)
{
    // Validasi kategori_id dan input lainnya
    $request->validate([
        'kategori_id' => ['required', Rule::exists('m_kategori', 'kategori_id')],
        'barang_kode' => 'required|string|max:10',
        'barang_nama' => 'required|string|max:50',
        'harga_beli' => 'required|numeric',
        'harga_jual' => 'required|numeric',
    ]);

    $data = [
        'kategori_id' => $request->input('kategori_id'),
        'barang_kode' => $request->input('barang_kode'),
        'barang_nama' => $request->input('barang_nama'),
        'harga_beli' => $request->input('harga_beli'),
        'harga_jual' => $request->input('harga_jual'),
    ];

    $barang = BarangModel::create($data);
    return response()->json($barang, 201);
}

    // Fungsi untuk menampilkan data barang tertentu berdasarkan ID
    public function show(BarangModel $barang)
    {
        return response()->json($barang);
    }

    // Fungsi untuk memperbarui data barang tertentu
    public function update(Request $request, BarangModel $barang)
    {

    $barang->update($request->all());
    return BarangModel::find($barang);
}
    
    // Fungsi untuk menghapus data barang tertentu
    public function destroy(BarangModel $barang)
    {
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data barang terhapus',
        ]);
    }
}

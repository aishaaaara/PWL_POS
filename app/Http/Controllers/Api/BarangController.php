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
        // Validasi kategori_id, image, dan input lainnya
        $request->validate([
            'kategori_id' => ['required', Rule::exists('m_kategori', 'kategori_id')],
            'barang_kode' => 'required|string|max:10',
            'barang_nama' => 'required|string|max:50',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validasi untuk gambar
        ]);
    
        $data = [
            'kategori_id' => $request->input('kategori_id'),
            'barang_kode' => $request->input('barang_kode'),
            'barang_nama' => $request->input('barang_nama'),
            'harga_beli' => $request->input('harga_beli'),
            'harga_jual' => $request->input('harga_jual'),
        ];
    
        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->store('images', 'public'); // lokasi penyimpanan di 'images' pada disk 'public'
            $data['image'] = $image->hashName();
        }
    
        $barang = BarangModel::create($data);
        return response()->json($barang, 201);
    }
    
    // Fungsi untuk menampilkan data barang tertentu berdasarkan ID
    public function show(BarangModel $barang)
    {
        return response()->json($barang);
    }
    
    public function update(Request $request, BarangModel $barang)
    {
        $request->validate([
            'kategori_id' => ['sometimes', 'required', Rule::exists('m_kategori', 'kategori_id')],
            'barang_kode' => 'sometimes|required|string|max:10',
            'barang_nama' => 'sometimes|required|string|max:50',
            'harga_beli' => 'sometimes|required|numeric',
            'harga_jual' => 'sometimes|required|numeric',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $data = $request->only(['kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual']);
    
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($barang->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('images/' . $barang->image);
            }
    
            // Simpan gambar baru
            $image = $request->file('image');
            $image->store('images', 'public');
            $data['image'] = $image->hashName();
        }
    
        $barang->update($data);
    
        return response()->json($barang);
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

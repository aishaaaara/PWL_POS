<?php
namespace App\Http\Controllers;

use App\Models\KategoriModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    // Menampilkan halaman awal barang
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif

        $kategori = KategoriModel::all(); // ambil data kategori untuk filter kategori

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    // Ambil data barang dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $barangs = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'kategori_id')->with('kategori');

        // Filter data barang berdasarkan kategori_id
        if ($request->level_id) {
            $barangs->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barangs)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id  . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // Menampilkan halaman form tambah barang
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        $kategori = KategoriModel::all(); // Ambil data kategori untuk ditampilkan di form
        $activeMenu = 'barang'; // Set menu yang sedang aktif

        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data barang baru
    public function store(Request $request)
    {
        $request->validate([
            // barang_kode harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_barang kolom barang_kode
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
            
            // barang_nama harus diisi, berupa string, dan maksimal 100 karakter
            'barang_nama' => 'required|string|max:100',
            
            // harga_beli harus diisi dan berupa angka (untuk harga, lebih cocok menggunakan tipe integer atau numeric)
            'harga_beli' => 'required|numeric',
            
            // harga_jual harus diisi dan berupa angka
            'harga_jual' => 'required|numeric',
            
            // kategori_id harus diisi dan berupa angka
            'kategori_id' => 'required|integer',
        ]);

        // Membuat data barang berdasarkan input yang diberikan
        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id,
        ]);

        // Redirect kembali ke halaman daftar barang dengan pesan sukses
        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }


    // Menampilkan detail barang
    public function show(string $id)
    {
        $barang = BarangModel::with('kategori')->find($id);
        
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];
        
        $page = (object) [
            'title' => 'Detail Barang'
        ];
        
        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

        //menampilkan halaman form edit barang
        public function edit (string $id){
            $barang = BarangModel::find($id);
            $kategori = KategoriModel::all();

            $breadcrumb = (object)[
                'title' => 'Edit Barang',
                'list' => ['Home', 'Barang', 'Edit']
            ];

            $page = (object)[
                'title' => 'Edit Barang'
            ];

            $activeMenu = 'barang'; //set menu sedang aktif

            return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
        } 

        // menyimpan data barang 
        public function update(Request $request, string $id)
        {
            $request->validate([
                // barang_nama harus diisi, berupa string, min 3 karakter, bernilai unik di tabel m_barang kolom barang_nama kecuali untuk barang dengan id yang sedang diedit
                'barang_nama' => 'required|string|min:3|unique:m_barang,barang_nama,' . $id . ',barang_id',
                'barang_kode' => 'required|string|max:50', // kode barang harus diisi, berupa string, dan max 50 karakter
                'harga_beli' => 'required|numeric|min:0', 
                'harga_jual' => 'required|numeric|min:0', 
                'kategori_id' => 'required|integer' // kategori_id harus diisi dan berupa angka
            ]);

            // Mencari barang berdasarkan ID
            $barang = BarangModel::find($id);

            // Update data barang
            $barang->update([
                'barang_nama' => $request->barang_nama,
                'barang_kode' => $request->barang_kode,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'kategori_id' => $request->kategori_id
            ]);

            // Redirect setelah berhasil
            return redirect('/barang')->with('success', 'Data barang berhasil diubah');
        }


        // Menghapus data barang
        public function destroy(string $id)
        {
            // Cek apakah data barang dengan ID yang dimaksud ada atau tidak
            $check = BarangModel::find($id);
            
            if (!$check) {
                return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
            }

            try {
                // Hapus data barang
                BarangModel::destroy($id);
                return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
                return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
        }

        // Fungsi create_ajax()
        public function create_ajax()
        {
            $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
            return view('barang.create_ajax')->with('kategori', $kategori);
        }

        // Proses simpan data melalui ajax
        public function store_ajax(Request $request)
        {
            // Cek apakah request berupa ajax
            if($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'barang_kode' => 'required|string|max:50|unique:m_barang,barang_kode', // kode barang harus diisi, berupa string, dan max 50 karakter
                    'barang_nama' => 'required|string|min:3',
                    'harga_beli' => 'required|numeric|min:0', 
                    'harga_jual' => 'required|numeric|min:0', 
                    'kategori_id' => 'required|integer'
                ];

                // use Illuminate\Support\Facades\Validator;
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()) {
                    return response()->json([
                        'status' => false, // response status, false: error/gagal, true: berhasil
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors(), // pesan error validasi
                    ]);
                }

                // Simpan data barang
                BarangModel::create([
                    'barang_nama' => $request->barang_nama,
                    'barang_kode' => $request->barang_kode,
                    'harga_beli' => $request->harga_beli,
                    'harga_jual' => $request->harga_jual,
                    'kategori_id' => $request->kategori_id
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data barang berhasil disimpan'
                ]);
            }

            return redirect('/');
        }

        // Menampilkan halaman form edit barang ajax
        public function edit_ajax(string $id)
        {
            $barang = BarangModel::find($id);
            $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
            
            return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
        }

        public function update_ajax(Request $request, $id)
        {
            // Cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kategori_id' => 'required|integer',
                    'barang_kode' => 'required|string|max:50|unique:m_barang,barang_kode,' . $id . ',barang_id', 
                    'barang_nama' => 'required|string|min:3',
                    'harga_beli' => 'required|numeric|min:0', 
                    'harga_jual' => 'required|numeric|min:0'
                ];

                // Validasi data
                $validator = Validator::make($request->all(), $rules);
                
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false, // Respon json, true: berhasil, false: gagal
                        'message' => 'Validasi gagal.',
                        'msgField' => $validator->errors() // Menunjukkan field mana yang error
                    ]);
                }

                $check = BarangModel::find($id);
                
                if ($check) {
                    $check->update($request->all());

                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diupdate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
            }
            
            return redirect('/');
        }

        public function confirm_ajax(string $id) 
        {
            $barang = BarangModel::find($id);

            return view('barang.confirm_ajax', ['barang' => $barang]);
        }

        public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request berasal dari AJAX atau permintaan JSON
            if ($request->ajax() || $request->wantsJson()) {
                // Cari barang berdasarkan id
                $barang = BarangModel::find($id);
                
                // Jika barang ditemukan, hapus data
                if ($barang) {
                    $barang->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
            }

            // Jika bukan request AJAX, arahkan kembali ke halaman sebelumnya
            return redirect('/');
        }

        // Detail ajax
        public function show_ajax(string $id)
        {
            $barang = BarangModel::with('kategori')->find($id);

            return view('barang.show_ajax', ['barang' => $barang]);
        }
}
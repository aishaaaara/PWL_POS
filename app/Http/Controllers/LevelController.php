<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class LevelController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level'; 
        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }    

   // Ambil data level dalam bentuk json untuk datatables
   public function list(Request $request)
   {
       $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

       return DataTables::of($levels)
           ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
           ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
            $btn = ''; // Tombol dinonaktifkan
            $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
               $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
               return $btn;
           })
           ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
           ->make(true);
   }

     
    // Menampilkan halaman form tambah Level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Level baru'
        ];

        $level = LevelModel::all(); // Ambil data level untuk ditampilkan di form
        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

        // Menyimpan data level baru
        public function store(Request $request)
        {
            $request->validate([
                // level_kode harus diisi, berupa string, dan bernilai unik di tabel m_level kolom level_kode
                'level_kode' => 'required|string|unique:m_level,level_kode',
                
                // level_nama harus diisi, berupa string, dan maksimal 100 karakter
                'level_nama' => 'required|string|max:100',
            ]);

            // Menyimpan data level baru
            LevelModel::create([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama,
            ]);

            return redirect('/level')->with('success', 'Data level berhasil disimpan');
        }

        // Menampilkan detail level
        public function show(string $id)
        {
            // Mencari level berdasarkan level_id, akan melempar ModelNotFoundException jika tidak ditemukan
            $level = LevelModel::findOrFail($id);

            // Breadcrumb untuk navigasi
            $breadcrumb = (object) [
                'title' => 'Detail Level',
                'list' => ['Home', 'Level', 'Detail']
            ];

            // Judul halaman
            $page = (object) [
                'title' => 'Detail Level'
            ];

            // Menentukan menu yang sedang aktif
            $activeMenu = 'level'; 

            // Mengembalikan view dengan data level
            return view('level.show', [
                'breadcrumb' => $breadcrumb,
                'page' => $page,
                'level' => $level,
                'activeMenu' => $activeMenu
            ]);
}

            // Menampilkan halaman form edit level
            public function edit(string $id)
            {
                $level = LevelModel::findOrFail($id); // Mengambil level berdasarkan ID

                $breadcrumb = (object)[
                    'title' => 'Edit Level',
                    'list' => ['Home', 'Level', 'Edit']
                ];

                $page = (object)[
                    'title' => 'Edit Level'
                ];

                $activeMenu = 'level'; // Set menu yang sedang aktif

                return view('level.edit', [
                    'breadcrumb' => $breadcrumb, 
                    'page' => $page, 
                    'level' => $level, 
                    'activeMenu' => $activeMenu
                ]);
            }

            // Menyimpan data level
            public function update(Request $request, string $id)
            {
                $request->validate([
                    'level_kode' => 'required|string|max:10|unique:m_level,level_kode,' . $id . ',level_id', // Validasi level_kode
                    'level_nama' => 'required|string|max:100', // Validasi level_nama
                ]);

                $level = LevelModel::findOrFail($id); // Mengambil level berdasarkan ID

                // Mengupdate level dengan data baru
                $level->update([
                    'level_kode' => $request->level_kode,
                    'level_nama' => $request->level_nama,
                ]);

                return redirect('/level')->with('success', 'Data level berhasil diubah');
            }

            // Menghapus data level
            public function destroy(string $id)
            {
                // Cek apakah data level dengan ID yang dimaksud ada atau tidak
                $check = LevelModel::find($id);
                
                if (!$check) {
                    return redirect('/level')->with('error', 'Data level tidak ditemukan');
                }

                try {
                    // Hapus data level
                    LevelModel::destroy($id);
                    return redirect('/level')->with('success', 'Data level berhasil dihapus');
                } catch (\Illuminate\Database\QueryException $e) {
                    // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
                    return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
                }
            }

                    // Fungsi create_ajax()
            public function create_ajax()
        {
            return view('level.create_ajax');
        }

        // Proses simpan data melalui ajax
        public function store_ajax(Request $request)
        {
            // Cek apakah request berupa ajax
            if($request->ajax() || $request->wantsJson()) {
                $rules = [
                   // level_kode harus diisi, berupa string, dan bernilai unik di tabel m_level kolom level_kode
                'level_kode' => 'required|string|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
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

                // Simpan data level
                LevelModel::create([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil disimpan'
                ]);
            }

            return redirect('/');
        }

            // Menampilkan halaman form edit level ajax
            public function edit_ajax(string $id)
            {
                $level = LevelModel::find($id);
                
                return view('level.edit_ajax', ['level' => $level]);
            }

    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:10',
                'level_nama' => 'required|string|max:100',
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

            $check = LevelModel::find($id);
            
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
                  $level = LevelModel::find($id);
      
                  return view('level.confirm_ajax', ['level' => $level]);
              }
        
        //delete
        public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request berasal dari AJAX atau permintaan JSON
            if ($request->ajax() || $request->wantsJson()) {
                // Cari level berdasarkan id
                $level = LevelModel::find($id);
                
                // Jika level ditemukan, hapus data
                if ($level) {
                    $level->delete();
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

    public function export_pdf()
    {
        $level = LevelModel::select('level_kode', 'level_nama')
        ->orderBy('level_kode')
        ->get();

        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Level '.date('Y-m-d H:i:s').'.pdf');
    }
        }

<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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

    public function list(Request $request)
{
    $level = LevelModel::select('level_id', 'level_kode', 'level_nama');

    return DataTables::of($level)
        // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
        ->addIndexColumn()
        ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
            // Menghapus tombol "Detail" dan hanya menyisakan "Edit" dan "Delete"
            $btn = '<a href="'.url('/level/' . $level->level_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
            $btn .= '<form class="d-inline-block" method="POST" action="'.url('/level/'.$level->level_id).'">'
                . csrf_field() . method_field('DELETE') .
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
            
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




    }

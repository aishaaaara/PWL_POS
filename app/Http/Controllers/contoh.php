<?php
namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all(); // ambil data level untuk filter level

        return view('users.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');

        // Filter data user berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }


    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // Ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // Set menu yang sedang aktif

        return view('users.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            
            // nama harus diisi, berupa string, dan maksimal 100 karakter
            'nama' => 'required|string|max:100',
            
            // password harus diisi dan minimal 5 karakter
            'password' => 'required|min:5',
            
            // level_id harus diisi dan berupa angka
            'level_id' => 'required|integer',
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    // Menampilkan detail user
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);
        
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];
        
        $page = (object) [
            'title' => 'Detail User'
        ];
        
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('users.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

        //menampilkan halaman form edit user
        public function edit (string $id){
            $user = UserModel::find($id);
            $level = LevelModel::all();

            $breadcrumb = (object)[
                'title' => 'Edit User',
                'list' => ['Home', 'User', 'Edit']
            ];

            $page = (object)[
                'title' => 'Edit User'
            ];

            $activeMenu = 'user'; //set menu sedang aktif

            return view('users.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
        } 

        //menyimpan data user 
        public function update(Request $request, string $id)
        {
            $request->validate([
                // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
                'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
                'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
                'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
                'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
            ]);

            $user = UserModel::find($id);

            $user->update([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
                'level_id' => $request->level_id
            ]);

            return redirect('/user')->with('success', 'Data user berhasil diubah');
        }

        // Menghapus data user
        public function destroy(string $id)
        {
            // Cek apakah data user dengan ID yang dimaksud ada atau tidak
            $check = UserModel::find($id);
            
            if (!$check) {
                return redirect('/user')->with('error', 'Data user tidak ditemukan');
            }

            try {
                // Hapus data user
                UserModel::destroy($id);
                return redirect('/user')->with('success', 'Data user berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
                return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
        }

        // Fungsi create_ajax()
        public function create_ajax()
        {
            $level = LevelModel::select('level_id', 'level_nama')->get();
            return view('users.create_ajax')->with('level', $level);
        }

        // Proses simpan data melalui ajax
        public function store_ajax(Request $request)
        {
            // Cek apakah request berupa ajax
            if($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'level_id' => 'required|integer',
                    'username' => 'required|string|min:3|unique:m_user,username',
                    'nama' => 'required|string|max:100',
                    'password' => 'required|min:6'
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

                // Simpan data user
                UserModel::create([
                    'level_id' => $request->level_id,
                    'username' => $request->username,
                    'nama' => $request->nama,
                    'password' => bcrypt($request->password) // pastikan password terenkripsi
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil disimpan'
                ]);
            }

            return redirect('/');
        }

        // Menampilkan halaman form edit user ajax
        public function edit_ajax(string $id)
        {
            $user = UserModel::find($id);
            $level = LevelModel::select('level_id', 'level_nama')->get();
            
            return view('users.edit_ajax', ['user' => $user, 'level' => $level]);
        }

        public function update_ajax(Request $request, $id)
        {
            // Cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'level_id' => 'required|integer',
                    'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                    'nama' => 'required|max:100',
                    'password' => 'nullable|min:6|max:20'
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

                $check = UserModel::find($id);
                
                if ($check) {
                    if (!$request->filled('password')) { // Jika password tidak diisi, maka hapus dari request
                        $request->request->remove('password');
                    }
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
            $user = UserModel::find($id);

            return view('users.confirm_ajax', ['user' => $user]);
        }

        public function delete_ajax(Request $request, $id)
        {
            // Cek apakah request berasal dari AJAX atau permintaan JSON
            if ($request->ajax() || $request->wantsJson()) {
                // Cari user berdasarkan id
                $user = UserModel::find($id);
                
                // Jika user ditemukan, hapus data
                if ($user) {
                    $user->delete();
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
}
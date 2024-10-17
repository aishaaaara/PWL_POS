<?php
namespace App\Http\Controllers;
use App\Models\LevelModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'User',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'user', 'url' => url('/user')]
            ]
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif
        $level = LevelModel::all(); //mengambil data levle untuk filter level

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level,'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) 
    { 
        $user = UserModel::select('user_id', 'username', 'nama', 'level_id') 
                    ->with('level'); 

        if ($request->level_id){
            $user->where('level_id', $request->level_id);
        }
    
        return DataTables::of($user) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($user) {  // menambahkan kolom aksi 
                // $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/user/'.$user->user_id).'">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                
                $btn  = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn; 
            }) 
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true); 
    } 
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list'  => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah User Baru'
        ];

        $level = LevelModel::all();
        $activeMenu = 'user';

        return view('user.create', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level, 
            'activeMenu' => $activeMenu
        ]);
    }
    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.create_ajax')
        ->with('level', $level);
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username', // Username wajib diisi, minimal 3 karakter, dan unik
            'nama'     => 'required|string|max:100',                     // Nama wajib diisi, maksimal 100 karakter
            'password' => 'required|min:5',                            // Password wajib diisi, minimal 5 karakter
            'level_id' => 'required|integer',                         // Level ID wajib diisi dan berupa angka
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => bcrypt($request->password), // Enkripsi password sebelum disimpan
            'level_id'  => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }
    public function store_ajax(Request $request) {
        // Cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];
    
            // Menggunakan Validator dari Illuminate\Support\Facades\Validator
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
    
            UserModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
    
        redirect('/');
    }
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list'  => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail User'
        ];

        $activeMenu = 'user';

        return view('user.show', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'user' => $user, 
            'activeMenu' => $activeMenu
        ]);
    }
        // Detail ajax
        public function show_ajax(string $id)
        {
            $user = UserModel::find($id);

            return view('user.show_ajax', ['user' => $user]);
        }

    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit User'
        ];

        $activeMenu = 'user';

        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', 
            'nama'     => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer'
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }
    public function update_ajax(Request $request, $id)
    { 
        if ($request->ajax() || $request->wantsJson()) 
        { 
            $rules = [ 
                'level_id' => 'required|integer', 
                'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id', 
                'nama'     => 'required|max:100', 
                'password' => 'nullable|min:6|max:20' 
            ];

            $validator = Validator::make($request->all(), $rules); 
    
            if ($validator->fails()) { 
                return response()->json([ 
                    'status'   => false,    // respon json, true: berhasil, false: gagal 
                    'message'  => 'Validasi gagal.', 
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]); 
            } 
        
            $check = UserModel::find($id); 
            if ($check) { 
                if(!$request->filled('password') ){ 
                    $request->request->remove('password'); 
                } 
                    
                $check->update($request->all()); 
                return response()->json([ 
                    'status'  => true, 
                    'message' => 'Data berhasil diupdate' 
                ]); 
            } else{ 
                return response()->json([ 
                    'status'  => false, 
                    'message' => 'Data tidak ditemukan' 
                ]); 
            } 
        } 
        return redirect('/'); 
    }
    public function destroy (string  $id)
    {
        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'Data User tidak Ditemukan');
        } 
        
        try{
            UserModel::destroy($id);

            return redirect('/user')->with('success', 'Data User Berhasil dihapus');
        } catch (\Illuminate\Database\QueryException){
            return redirect('/user')->with('error', 'Data User Gagal dihapus karena terdapat Tabel lain yang terkait dengan data ini');
        }
    }
    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    }
    public function delete_ajax(Request $request, $id) 
    {
        if($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);

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
        } else {
            return redirect('/');
        }
    }
    public function export_pdf()
    {
        $user = UserModel::select('user_id', 'level_id', 'username', 'nama')
            ->orderBy('level_id')
            ->with('level') // Mengambil relasi level
            ->get();

        // Gunakan Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('user.export_pdf', ['user' => $user]); // Pastikan view ini sesuai
        $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // Set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data_User_' . date("Y-m-d_H-i-s") . '.pdf');
    }

    public function import()
    {
        return view('user.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_user'); // Ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // Load reader file excel
            $reader->setReadDataOnly(true); // Hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // Load file excel
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // Ambil data excel
            $insert = [];
            $importedData = []; // Array untuk menyimpan data yang berhasil diimport
    
            if (count($data) > 1) { // Jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'level_id' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'password' => bcrypt($value['D']),
                            'created_at' => now(),
                        ];
    
                        // Menyimpan data yang berhasil diimport untuk dikembalikan ke client
                        $importedData[] = [
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'password' => bcrypt($value['D']),
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    // Insert data ke database, jika data sudah ada, maka diabaikan
                    UserModel::insertOrIgnore($insert);
    
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport',
                        'data' => $importedData // Mengirim data yang diimport kembali ke client
                    ]);
                }
    
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
    
        return redirect('/');
    }
    
    
    public function export_excel()
    {
        // ambil data user yang akan di export
        $users = UserModel::select('level_id', 'username', 'nama', 'password', 'created_at')
            ->orderBy('level_id')
            ->get();
    
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // ambil sheet yang aktif dan set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Level ID');
        $sheet->setCellValue('C1', 'Username');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Password');
        $sheet->setCellValue('F1', 'Created At');
    
        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header
        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
    
        foreach ($users as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->level_id);
            $sheet->setCellValue('C'.$baris, $value->username);
            $sheet->setCellValue('D'.$baris, $value->nama);
            $sheet->setCellValue('E'.$baris, $value->password); // Hindari menyimpan password plaintext, ini hanya contoh
            $sheet->setCellValue('F'.$baris, $value->created_at);
            $baris++;
            $no++;
        }
    
        // set auto size untuk kolom
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        $sheet->setTitle('Data User'); // set title sheet
    
        // Buat penulis untuk file Excel
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_User_' . date('Y-m-d_H-i-s') . '.xlsx';
    
        // Header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"".$filename."\"");
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    
        // Simpan file ke output
        $writer->save('php://output');
        exit;
    }
}
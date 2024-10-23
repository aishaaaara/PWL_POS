<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\DetailPenjualanModel;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Transaksi',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Transaksi Penjualan', 'url' => url('/penjualan')]
            ]
        ];

        $page = (object) [
            'title' => 'Daftar Transaksi Penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';

        $user = UserModel::all();
        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }
    
    public function list(Request $request)
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode', 'penjualan_tanggal', 'user_id', 'pembeli')
            ->with('user');

        if ($request->user_id) {
            $penjualan->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualan)
            ->addIndexColumn() // Menambahkan kolom index / nomor urut
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Transaksi',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Transaksi Penjualan', 'url' => url('/penjualan')],
                ['name' => 'Transaksi Penjualan', 'url' => url('/tambah')]
            ]
        ];

        $page = (object) [
            'title' => 'Tambah Transaksi baru'
        ];

        $user = UserModel::all();
        $barang = BarangModel::all();
        $activeMenu = 'penjualan';

        return view('penjualan.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'USER$user' => $user,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'penjualan_kode' => 'required|string|max:255',
            'pembeli' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'user' => 'required|exists:m_user,user_id',
            'barang' => 'required|array',
            'barang.*.id' => 'exists:m_barang,barang_id',
            'barang.*.jumlah' => 'nullable|integer|min:1',
        ]);

        $penjualan = new PenjualanModel();
        $penjualan->penjualan_kode = $validatedData['penjualan_kode'];
        $penjualan->pembeli = $validatedData['pembeli'];
        $penjualan->penjualan_tanggal = $validatedData['tanggal'];
        $penjualan->user_id = $validatedData['user'];
        $penjualan->save();

        foreach ($validatedData['barang'] as $item) {
            // Cek apakah barang dipilih tanpa jumlah tertentu
            if (!empty($item['id'])) {
                $barang = BarangModel::find($item['id']);
                $detailPenjualan = new DetailPenjualanModel();
                $detailPenjualan->penjualan_id = $penjualan->penjualan_id;
                $detailPenjualan->barang_id = $item['id'];
                // Periksa apakah jumlah barang telah diisi, jika tidak, maka dianggap 0
                $detailPenjualan->jumlah = isset($item['jumlah']) ? $item['jumlah'] : 0;
                $detailPenjualan->harga = $barang->harga_jual;
                $detailPenjualan->save();
            }
        }

        return redirect('/penjualan')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(string $id)
    {
        $penjualan = PenjualanModel::with(['detail', 'user'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Transaksi Penjualan ',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Transaksi Penjualan', 'url' => url('/penjualan')],
                ['name' => 'Transaksi Penjualan', 'url' => url('/detail')]
            ]
        ];

        $page = (object) [
            'title' => 'Detail Transaksi Penjualan '
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'penjualan' => $penjualan,
            'activeMenu' => $activeMenu
        ]);
    }

        public function create_ajax()
    {
        // Mengambil semua data penjualan dari tabel
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode', 'penjualan_tanggal', 'user_id', 'pembeli')
            ->with('user')->get();
        
        $user = UserModel::all(); 
        $barang = BarangModel::all(); 

        return view('penjualan.create_ajax', compact('penjualan', 'user', 'barang'));
    }

    public function store_ajax(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'penjualan_kode' => 'required|string|max:255',
            'pembeli' => 'required|string|max:255',
            'tanggal' => 'required|date_format:Y-m-d\TH:i', // Format untuk datetime-local
            'barang' => 'required|array',
            'barang.*.id' => 'exists:m_barang,barang_id',
            'barang.*.jumlah' => 'nullable|integer|min:1',
        ]);
    
        // Mendapatkan user yang sedang login
        $user = Auth::user();
    
        // Simpan data penjualan
        $penjualan = new PenjualanModel();
        $penjualan->penjualan_kode = $validatedData['penjualan_kode'];
        $penjualan->pembeli = $validatedData['pembeli'];
        $penjualan->penjualan_tanggal = $validatedData['tanggal'];
        $penjualan->user_id = $user->user_id; // Menggunakan user yang sedang login
        $penjualan->save();
    
        // Simpan detail penjualan
        foreach ($validatedData['barang'] as $item) {
            if (!empty($item['id'])) {
                $barang = BarangModel::find($item['id']);
                $detailPenjualan = new DetailPenjualanModel();
                $detailPenjualan->penjualan_id = $penjualan->penjualan_id;
                $detailPenjualan->barang_id = $item['id'];
                $detailPenjualan->jumlah = isset($item['jumlah']) ? $item['jumlah'] : 0;
                $detailPenjualan->harga = $barang->harga_jual;
                $detailPenjualan->save();
            }
        }
    
        // Cek apakah request adalah AJAX
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Transaksi berhasil disimpan.',
                'penjualan_id' => $penjualan->penjualan_id
            ], 200);
        }
    
        return redirect('/penjualan')->with('success', 'Transaksi berhasil disimpan.');
    }
    
        public function show_ajax(string $id)
        {
            $penjualan = PenjualanModel::with(['detail', 'user'])->find($id); // Sesuaikan dengan relasi yang ada
            return view('penjualan.show_ajax', ['penjualan' => $penjualan]);
        }

        public function confirm_ajax(string $id)
        {
            $penjualan = PenjualanModel::find($id);
            return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
        }

        public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);

            if ($penjualan) {
                DB::beginTransaction();
                try {
                    // Hapus relasi detail jika ada
                    if ($penjualan->detail()->exists()) {
                        $penjualan->detail()->delete(); // Hapus detail transaksi terlebih dahulu
                    }
                    
                    // Hapus penjualan utama
                    $penjualan->delete();
                    
                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_penjualan');
            $reader = IOFactory::createReader('Xlsx'); // Load reader file excel
            $reader->setReadDataOnly(true); // Hanya membaca data
    
            try {
                $spreadsheet = $reader->load($file->getRealPath()); // Load file excel
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => 'Error loading file: ' . $e->getMessage()]);
            }
    
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // Ambil data excel dalam array
            $detailInsert = []; 
            $userId = auth()->user()->id; // Ambil ID pengguna yang sedang login
    
            if (count($data) > 1) { 
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Lewati header pada baris pertama
                        // Buat penjualan jika belum ada
                        $penjualan = PenjualanModel::firstOrCreate(
                            ['penjualan_kode' => $value['A']], // Kode unik
                            [
                                'user_id' => $userId, // Ambil ID pengguna yang sedang login
                                'pembeli' => $value['C'],
                                'penjualan_tanggal' => now(), // Set tanggal saat ini
                                'created_at' => now(),
                            ]
                        );
    
                        // Cek barang berdasarkan nama (case-insensitive)
                        $barang = BarangModel::whereRaw('LOWER(barang_nama) = ?', [strtolower($value['D'])])->first(); // Sesuaikan index kolom
    
                        if ($barang) {
                            $harga = $barang->harga_jual; // Ambil harga dari barang
                            $jumlah = $value['F']; // Jumlah barang yang dibeli
                            $subtotal = $harga * $jumlah; // Hitung subtotal
    
                            $detailInsert[] = [
                                'penjualan_id' => $penjualan->id, // Ambil ID dari penjualan yang baru dibuat
                                'barang_id' => $barang->barang_id, // Ambil ID dari barang yang ditemukan
                                'harga' => $harga, // Ambil harga dari barang
                                'jumlah' => $jumlah, // Jumlah barang yang dibeli
                                'subtotal' => $subtotal, // Hitung subtotal
                                'created_at' => now(),
                            ];
                        } else {
                            // Anda bisa menambahkan logika untuk menangani barang yang tidak ditemukan di sini, jika perlu
                        }
                    }
                }
    
                // Jika ada detail yang akan dimasukkan, lakukan insert
                if (count($detailInsert) > 0) {
                    DetailPenjualanModel::insert($detailInsert);
                }
    
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimpor'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimpor'
                ]);
            }
        }
    
        // Jika bukan permintaan AJAX, berikan respon yang sesuai
        return response()->json(['status' => false, 'message' => 'Request tidak valid']);
    }
    

    public function export_excel() {
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode', 'penjualan_tanggal', 'user_id', 'pembeli')
            ->with('user')->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
    
        // Set header kolom
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Kode');
        $sheet->setCellValue('C1', 'User');
        $sheet->setCellValue('D1', 'Pembeli');
        $sheet->setCellValue('E1', 'Tanggal');
        $sheet->setCellValue('F1', 'Barang');
        $sheet->setCellValue('G1', 'Harga');
        $sheet->setCellValue('H1', 'Jumlah');
        $sheet->setCellValue('I1', 'Subtotal'); // Tambahkan kolom subtotal
    
        $sheet->getStyle('A1:I1')->getFont()->setBold(true); // bold header
    
        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari 2
        foreach ($penjualan as $key => $value) {
            $row_start = $baris; // simpan baris awal untuk penjualan ini
            
            // masukkan data penjualan (selalu masukkan satu kali per penjualan)
            $sheet->setCellValue('A'. $baris, $no);
            $sheet->setCellValue('B'. $baris, $value->penjualan_kode);
            $sheet->setCellValue('C'. $baris, $value->user->nama);
            $sheet->setCellValue('D'. $baris, $value->pembeli);
            $sheet->setCellValue('E'. $baris, $value->penjualan_tanggal);
            
            // masukkan data barang dari detail penjualan
            foreach ($value->detailPenjualan as $detail) {
                $subtotal = $detail->harga * $detail->jumlah; // Hitung subtotal
                $sheet->setCellValue('F'. $baris, $detail->barang->barang_nama); // ambil nama barang dari relasi
                $sheet->setCellValue('G'. $baris, $detail->harga);
                $sheet->setCellValue('H'. $baris, $detail->jumlah);
                $sheet->setCellValue('I'. $baris, $subtotal); // Masukkan subtotal
                $baris++; // pindah ke baris berikutnya untuk setiap barang
            }
            
            $no++;
        }
    
        // set auto size untuk setiap kolom
        foreach(range('A','I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        $sheet->setTitle('Data Penjualan'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan '.date('Y-m-d H:i:s').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output'); // download file excel ke browser
        exit; // keluar proses
    }

    public function export_pdf() {
        // Ambil data penjualan dengan relasi user dan detail penjualan
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode', 'penjualan_tanggal', 'user_id', 'pembeli')
            ->with(['user', 'detailPenjualan.barang']) // Include relasi user dan detail barang
            ->get();
    
        // Load view 'penjualan.export_pdf' dengan data penjualan dan detail
        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        // Set ukuran kertas dan orientasi
        $pdf->setPaper('A4', 'portrait'); 
        $pdf->setOption('isRemoteEnabled', true);
        return $pdf->stream('Data Penjualan '.date('Y-m-d H:i:s').'.pdf');
    }
}
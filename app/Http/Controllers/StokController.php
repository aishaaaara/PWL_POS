<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;


class StokController extends Controller
{
    public function index()
{
    $activeMenu = 'stok'; 
    $breadcrumb = (object) [
        'title' => 'Stok',
        'list' => [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Stok', 'url' => url('/stok')]
        ]
    ];

    $barang = BarangModel::select('barang_id', 'barang_nama', 'kategori_id')->with('kategori')->get(); // Memuat relasi kategori
    $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
    $user = UserModel::select('user_id', 'nama')->get();
    $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get(); // Menambahkan kategori

    return view('stok.index', [
        'activeMenu' => $activeMenu,
        'breadcrumb' => $breadcrumb,
        'barang' => $barang,
        'supplier' => $supplier,
        'user' => $user,
        'kategori' => $kategori // Mengirim data kategori
    ]);
}


public function list(Request $request)
{
    $stok = StokModel::select('stok_id', 'barang_id', 'supplier_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
        ->with(['barang', 'supplier', 'user']); // Memuat relasi dengan barang, supplier, dan user

    // Filter berdasarkan barang
    $barang_id = $request->input('filter_barang');
    if (!empty($barang_id)) {
        $stok->where('barang_id', $barang_id);
    }

    // Filter berdasarkan kategori barang
    $kategori_id = $request->input('filter_kategori');
    if (!empty($kategori_id)) {
        $stok->whereHas('barang', function($query) use ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        });
    }

    return DataTables::of($stok)
        ->addIndexColumn()
        ->addColumn('aksi', function ($stok) {
            $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->editColumn('stok_tanggal', function($stok) {
            return \Carbon\Carbon::parse($stok->stok_tanggal)->format('d-m-Y');
        })
        ->addColumn('barang_nama', function($stok) {
            return $stok->barang->barang_nama;
        })
        ->addColumn('supplier_nama', function($stok) {
            return $stok->supplier->supplier_nama;
        })
        ->addColumn('user_nama', function($stok) {
            return $stok->user->nama;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}



    public function show_ajax(string $id)
    {
        $stok = StokModel::with(['barang.kategori', 'supplier', 'user'])->find($id);
    
        return view('stok.show_ajax', ['stok' => $stok]);
    }
    

    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.create_ajax', [
            'barang' => $barang,
            'supplier' => $supplier,
            'user' => $user
        ]);
    }

    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'barang_id' => ['required', 'integer', 'exists:m_barang,barang_id'],
            'supplier_id' => ['required', 'integer', 'exists:m_supplier,supplier_id'],
            'stok_tanggal' => ['required', 'date'],
            'stok_jumlah' => ['required', 'numeric', 'min:1'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // Cek apakah barang_id sudah ada dalam stok
        $existingStock = StokModel::where('barang_id', $request->barang_id)->first();
        if ($existingStock) {
            return response()->json([
                'status' => false,
                'message' => 'Barang sudah ada di stok',
                'msgField' => ['barang_id' => ['Barang ini sudah ada di stok.']]
            ]);
        }

        // Menambahkan user_id dari user yang login
        $data = $request->all();
        $data['user_id'] = auth()->user()->user_id; // Otomatis mengisi user_id dengan ID user yang login

        // Simpan data stok
        StokModel::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    return redirect('/');
}


// Menampilkan halaman form edit stok ajax
        public function edit_ajax(string $id)
        {
            $stok = StokModel::find($id);
            $supplier = SupplierModel::all();
            $barang = BarangModel::all(); // Pastikan model BarangModel sudah ada

            return view('stok.edit_ajax', [
                'stok' => $stok,
                'supplier' => $supplier,
                'barang' => $barang,
            ]);
        }

 public function update_ajax(Request $request, $id)
 {
     if ($request->ajax() || $request->wantsJson()) {
         $rules = [
             'supplier_id' => 'required|exists:m_supplier,supplier_id',
             'barang_id' => 'required|exists:m_barang,barang_id',
             'stok_jumlah' => 'required|integer|min:1',
         ];

         $validator = Validator::make($request->all(), $rules);
         
         if ($validator->fails()) {
             return response()->json([
                 'status' => false,
                 'message' => 'Validasi gagal.',
                 'msgField' => $validator->errors(),
             ]);
         }

         $check = StokModel::find($id);
         
         if ($check) {
             $check->update([
                 'supplier_id' => $request->supplier_id,
                 'barang_id' => $request->barang_id,
                 'stok_tanggal' => now(),
                 'stok_jumlah' => $request->stok_jumlah,
             ]);

             return response()->json([
                 'status' => true,
                 'message' => 'Data berhasil diupdate',
             ]);
         } else {
             return response()->json([
                 'status' => false,
                 'message' => 'Data tidak ditemukan',
             ]);
         }
     }
     
     return redirect('/');
 }
    
    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);

        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                $stok->delete();
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
        return redirect('/');
    }

    public function export_pdf()
{
    $stok = StokModel::select('stok_tanggal', 'barang_id', 'stok_jumlah', 'user_id', 'supplier_id')
        ->orderBy('stok_tanggal')
        ->with(['barang', 'user', 'supplier']) // Load relasi untuk barang, user, dan supplier
        ->get();

    // Menggunakan Barryvdh\DomPDF\Facade\Pdf
    $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
    $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
    $pdf->setOption('isRemoteEnabled', true); // Set true jika ada gambar dari url
    $pdf->render();

    return $pdf->stream('Data Stok ' . date("Y-m-d H:i:s") . '.pdf');
}

    public function import()
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        // Validasi input
        $rules = [
            'file_stok' => ['required', 'mimes:xlsx,xls', 'max:1024'], // Hanya menerima file Excel dengan ekstensi .xlsx dan .xls
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            $file = $request->file('file_stok'); // Ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // Load reader file excel
            $reader->setReadDataOnly(true); // Hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // Load file excel
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // Ambil data excel
            $insert = [];
            $importedData = []; // Array untuk menyimpan data yang berhasil diimport
            $failedData = []; // Array untuk menyimpan data yang gagal diimport
            
            $user_id = auth()->user()->user_id; // Ambil ID user yang login
            
            if (count($data) > 1) { // Jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Baris ke 1 adalah header, maka lewati
                        try {
                            $stok_tanggal = \Carbon\Carbon::createFromFormat('Y-m-d', $value['C'])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $stok_tanggal = null; // Jika format tidak valid, set null
                        }
                        $stok_jumlah = is_numeric($value['D']) ? $value['D'] : 0;

                        // Periksa apakah stok_tanggal dan stok_jumlah valid
                        if (!$stok_tanggal || $stok_jumlah <= 0) {
                            continue; // Jika tanggal atau jumlah tidak valid, lewati
                        }

                        // Cek apakah barang_id sudah ada di database
                        $barangExists = StokModel::where('barang_id', $value['B'])->exists();
                        if ($barangExists) {
                            // Tambahkan ke data yang gagal diimport
                            $failedData[] = [
                                'supplier_id' => $value['A'],
                                'barang_id' => $value['B'],
                                'stok_tanggal' => $stok_tanggal['C'],
                                'stok_jumlah' => $stok_jumlah['D'],
                                'reason' => 'Barang ID sudah ada'
                            ];
                            continue; // Lewati proses insert jika barang sudah ada
                        }

                        $insert[] = [
                            'supplier_id' => $value['A'], // Mengambil supplier_id dari kolom A
                            'barang_id' => $value['B'], // Mengambil barang_id dari kolom B
                            'stok_tanggal' => $stok_tanggal['C'], 
                            'stok_jumlah' => $stok_jumlah['D'], 
                            'user_id' => $user_id, // Otomatis menggunakan user_id yang login
                            'created_at' => now(), // Waktu sekarang untuk created_at
                        ];

                        // Menyimpan data yang berhasil diimport untuk dikembalikan ke client
                        $importedData[] = [
                            'supplier_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'stok_tanggal' => $stok_tanggal['C'],
                            'stok_jumlah' => $stok_jumlah['D'],
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // Insert data ke database, jika data sudah ada, maka diabaikan
                    StokModel::insertOrIgnore($insert);

                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport',
                        'data' => $importedData, // Mengirim data yang diimport kembali ke client
                        'failed' => $failedData // Data yang gagal diimport
                    ]);
                }
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport',
                    'failed' => $failedData // Mengembalikan data yang gagal
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage()
            ]);
        }
    }

    return redirect('/');
}


        public function export_excel()
        {
            // Ambil data stok yang akan diekspor
            $stok = StokModel::select('stok_id', 'barang_id', 'stok_jumlah', 'user_id', 'supplier_id')
                ->orderBy('stok_id')
                ->with(['barang', 'user', 'supplier']) // Relasi untuk mengambil data barang, user, dan supplier
                ->get();
        
            // Load library Excel
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
        
            // Set header kolom
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Id Stok');
            $sheet->setCellValue('C1', 'Nama Barang');
            $sheet->setCellValue('D1', 'Jumlah Stok');
            $sheet->setCellValue('E1', 'User');
            $sheet->setCellValue('F1', 'Supplier');
        
            // Bold header
            $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        
            $no = 1; // Nomor data
            $baris = 2; // Baris data dimulai dari baris ke-2
        
            foreach ($stok as $value) {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $value->stok_id);
                $sheet->setCellValue('C' . $baris, $value->barang->barang_nama); // Nama barang dari relasi barang
                $sheet->setCellValue('D' . $baris, $value->stok_jumlah);
                $sheet->setCellValue('E' . $baris, $value->user->nama); // Nama user dari relasi user
                $sheet->setCellValue('F' . $baris, $value->supplier->supplier_nama); // Nama supplier dari relasi supplier
                $baris++;
                $no++;
            }
        
            // Set auto size untuk kolom
            foreach (range('A', 'F') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        
            $sheet->setTitle('Data Stok'); // Set title sheet
        
            // Buat penulis untuk file Excel
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data_Stok_' . date('Y-m-d_H-i-s') . '.xlsx';
        
            // Header untuk download file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
        
            // Simpan file ke output
            $writer->save('php://output');
            exit;
        }
        
}
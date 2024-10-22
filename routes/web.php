<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\StokController;


use Illuminate\Support\Facades\Route;

Route::pattern('id','[0-9]+'); 

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register'); 
Route::post('register', [AuthController::class, 'register']); 


Route::middleware(['auth'])->group(function(){ 
    Route::get('/', [WelcomeController::class, 'index']);
    
    //profile 
    Route::group(['prefix' =>'profile','middleware'=>'authorize:ADM,MNG,STF,CUS'],function(){
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/{id}', [ProfileController::class, 'update'])->name('profile.update');
    
    });
    //level
    Route::middleware(['authorize:ADM'])->group(function() { 
        Route::get('/level', [LevelController::class, 'index']); //menampilkan halaman awal level
        Route::post('/level/list', [LevelController::class, 'list']); //menampilkan data level dalam bentuk json untuk db
        Route::get('/level/create', [LevelController::class, 'create']); //menampilkan halaman form tambah level
        Route::post('/level', [LevelController::class, 'store']); //
        Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']); //menampilkan halaman form tambah Level
        Route::post('/level/ajax', [LevelController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/level/{id}', [LevelController::class, 'show']); //menampilkan detail level
        Route::get('/level/{id}/edit', [LevelController::class, 'edit']); //menampilkan halaman edit level
        Route::put('/level/{id}', [LevelController::class, 'update']); //menyimpan perubahan 
        Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); //menampilkan halaman edit Level
        Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); //menghapus data Level
        Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); //menghapus data user
        Route::delete('/level/{id}', [LevelController::class, 'destroy']); //menghapus data level
        Route::get('/level/export_pdf', [LevelController::class, 'export_pdf']);// export excel
        Route::get('/level/import', [LevelController::class, 'import']); //ajax import excel
        Route::post('/level/import_ajax', [LevelController::class, 'import_ajax']); //ajax import excel
        Route::get('/level/export_excel', [LevelController::class, 'export_excel']);// export excel

    });
    
    //user
    Route::middleware(['authorize:ADM,MNG'])->group(function() {
        Route::get('/user', [UserController::class, 'index']); //menampilkan halaman awal user
        Route::post('/user/list', [UserController::class, 'list']); //menampilkan data user dalam bentuk json untuk db 
         Route::get('/user/create', [UserController::class, 'create']); //menampilkan halaman form tambah user
        Route::post('/user', [UserController::class, 'store']); //menampilkan data level baru
        Route::get('/user/create_ajax', [UserController::class, 'create_ajax']); //menampilkan halaman form tambah user
        Route::post('/user/ajax', [UserController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/user/{id}', [UserController::class, 'show']); //menampilkan detail user
        Route::get('/user/{id}/show_ajax', [UserController::class, 'show_ajax']); //menampilkan detail Barang
        Route::get('/user/{id}/edit', [UserController::class, 'edit']); //menampilkan halaman edit user
        Route::put('/user/{id}', [UserController::class, 'update']); //menyimapn perubahan 
        Route::get('/user/{id}/edit_ajax', [UserController::class, 'edit_ajax']); //menampilkan halaman edit user
        Route::put('/user/{id}/update_ajax', [UserController::class, 'update_ajax']);
        Route::get('/user/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); //mnghapus data user
        Route::delete('/user/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/user/{id}', [UserController::class, 'destroy']); //mnghapus data user
        Route::get('/user/export_pdf', [UserController::class, 'export_pdf']);// export excel
        Route::get('/user/import', [UserController::class, 'import']); //ajax import excel
        Route::post('/user/import_ajax', [UserController::class, 'import_ajax']); //ajax import excel
        Route::get('/user/export_excel', [UserController::class, 'export_excel']);// export excel
    });

    //kategori
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function() {
        Route::get('/kategori', [KategoriController::class, 'index']); //menampilkan halaman awal Kategori
        Route::post('/kategori/list', [KategoriController::class, 'list']); //menampilkan data Kategori dalam bentuk json untuk db 
        Route::get('/kategori/create', [KategoriController::class, 'create']); //menampilkan halaman form tambah Kategori
        Route::post('/kategori', [KategoriController::class, 'store']); //menampilkan data Kategori baru
        Route::get('/kategori/create_ajax', [KategoriController::class, 'create_ajax']); //menampilkan halaman form tambah Kategori
        Route::post('/kategori/ajax', [KategoriController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit']); //menampilkan halaman edit Kategori
        Route::put('/kategori/{id}', [KategoriController::class, 'update']); //menyimapn perubahan 
        Route::get('/kategori/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); //menampilkan halaman edit Kategori
        Route::put('/kategori/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('/kategori/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); //mnghapus data Kategori
        Route::delete('/kategori/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']); //mnghapus data Kategori
        Route::get('/kategori/import', [KategoriController::class, 'import']); //ajax import excel
        Route::post('/kategori/import_ajax', [KategoriController::class, 'import_ajax']); //ajax import excel
        Route::get('/kategori/export_excel', [KategoriController::class, 'export_excel']);// export excel
        Route::get('/kategori/export_pdf', [KategoriController::class, 'export_pdf']);// export pdf


    });
    
    //supplier
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function() {
        Route::get('/supplier', [SupplierController::class, 'index']); //menampilkan halaman awal Supplier
        Route::post('/supplier/list', [SupplierController::class, 'list']); //menampilkan data Supplier dalam bentuk json untuk db 
        Route::get('/supplier/create', [SupplierController::class, 'create']); //menampilkan halaman form tambah Supplier
        Route::post('/supplier', [SupplierController::class, 'store']); //menampilkan data Supplier baru
        Route::get('/supplier/create_ajax', [SupplierController::class, 'create_ajax']); //menampilkan halaman form tambah Supplier
        Route::post('/supplier/ajax', [SupplierController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/supplier/{id}/show_ajax', [SupplierController::class, 'show_ajax']); //menampilkan detail Barang
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']); //menampilkan halaman edit Supplier
        Route::put('/supplier/{id}', [SupplierController::class, 'update']); //menyimapn perubahan 
        Route::get('/supplier/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); //menampilkan halaman edit Supplier
        Route::put('/supplier/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
        Route::get('/supplier/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); //mnghapus data Supplier
        Route::delete('/supplier/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']); //mnghapus data Supplier
        Route::get('/supplier/export_pdf', [SupplierController::class, 'export_pdf']);// export excel
        Route::get('/supplier/import', [SupplierController::class, 'import']); //ajax import excel
        Route::post('/supplier/import_ajax', [SupplierController::class, 'import_ajax']); //ajax import excel
        Route::get('/supplier/export_excel', [SupplierController::class, 'export_excel']);// export excel

    });
    // Route untuk Barang
        Route::middleware(['authorize:ADM,MNG,STF,CUS'])->group(function() {
            Route::get('barang/', [BarangController::class, 'index']);
            Route::post('barang/list', [BarangController::class, 'list']);
            Route::get('barang/create', [BarangController::class, 'create']);
            Route::post('barang/', [BarangController::class, 'store']);
            Route::get('barang/create_ajax', [BarangController::class, 'create_ajax']);
            Route::post('barang/ajax', [BarangController::class, 'store_ajax']);
            Route::get('barang/{id}', [BarangController::class, 'show']);
            Route::get('barang/{id}/edit', [BarangController::class, 'edit']);
            Route::put('barang/{id}', [BarangController::class, 'update']);
            Route::get('barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
            Route::put('barang/{id}/update_ajax', [BarangController::class, 'update_ajax']);
            Route::get('barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
            Route::delete('barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
            Route::delete('barang/{id}', [BarangController::class, 'destroy']);
            Route::get('barang/{id}/show_ajax', [BarangController::class, 'show_ajax']);
            Route::get('barang/import', [BarangController::class, 'import']); // ajax form upload excel
            Route::post('barang/import_ajax', [BarangController::class, 'import_ajax']); // ajax import excel
            Route::get('barang/export_excel', [BarangController::class, 'export_excel']); // export excel
            Route::get('barang/export_pdf', [BarangController::class, 'export_pdf']); // export pdf
        });

    //stok
    Route::middleware(['authorize:ADM,MNG,STF,CUS'])->group(function() {
        Route::get('/stok', [StokController::class, 'index']); //menampilkan halaman awal Sarang
        Route::post('/stok/list', [StokController::class, 'list']); //menampilkan data stok dalam bentuk json untuk db 
        Route::post('/stok', [StokController::class, 'store']); //menampilkan data stok baru
        Route::get('/stok/create_ajax', [StokController::class, 'create_ajax']); //menampilkan halaman form tambah stok
        Route::post('/stok/ajax', [StokController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/stok/{id}/show_ajax', [StokController::class, 'show_ajax']); //menampilkan detail stok
        Route::get('/stok/{id}/edit_ajax', [StokController::class, 'edit_ajax']); //menampilkan halaman edit stok
        Route::put('/stok/{id}/update_ajax', [StokController::class, 'update_ajax']);
        Route::get('/stok/{id}/delete_ajax', [StokController::class, 'confirm_ajax']); //mnghapus data stok
        Route::delete('/stok/{id}/delete_ajax', [StokController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/stok/{id}', [StokController::class, 'destroy']); //mnghapus data stok
        Route::get('stok/import', [StokController::class, 'import']); //ajax import excel
        Route::post('stok/import_ajax', [StokController::class, 'import_ajax']);
        Route::get('stok/export_excel', [StokController::class, 'export_excel']);// export excel
        Route::get('stok/export_pdf', [StokController::class, 'export_pdf']);// export pdf

    });

    // penjualan
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::get('/penjualan', [PenjualanController::class, 'index']);          // menampilkan halaman awal penjualan
        Route::post('/penjualan/list', [PenjualanController::class, 'list']);      // menampilkan data penjualan dalam json untuk datables
        Route::get('/penjualan/create', [PenjualanController::class, 'create']);   // menampilkan halaman form tambah penjualan
        Route::post('/penjualan', [PenjualanController::class,'store']);          // menyimpan data penjualan baru
        Route::get('/penjualan/create_ajax', [PenjualanController::class, 'create_ajax']); // Menampilkan halaman form tambah penjualan Ajax
        Route::post('/penjualan/ajax', [PenjualanController::class, 'store_ajax']); // Menampilkan data penjualan baru Ajax
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show']);       // menampilkan detail penjualan
        Route::get('/penjualan/{id}/show_ajax', [PenjualanController::class, 'show_ajax']);
        Route::get('/penjualan/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete penjualan Ajax
        Route::delete('/penjualan/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']); // Untuk hapus data penjualan Ajax
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy']); // menghapus data penjualan
        Route::get('/penjualan/import', [PenjualanController::class, 'import']); //ajax import excel
        Route::post('/penjualan/import_ajax', [PenjualanController::class, 'import_ajax']); //ajax import excel
        Route::get('/penjualan/export_excel',[PenjualanController::class,'export_excel']); // ajax export excel
        Route::get('/penjualan/export_pdf', [PenjualanController::class, 'export_pdf']); //ajax export pdf
    });

});